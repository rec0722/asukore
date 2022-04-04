<?php

namespace App\Http\Controllers;

use App\Models\WeeklyReport;
use App\Models\WeeklyReportList;
use App\Models\Report;
use App\Models\User;
use App\Models\MstDept;
use App\Models\ReportAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use DateTime;

class WeeklyReportsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // 権限別のselectを取得
    $user = Auth::user();
    if ($user['role'] === 8) {
      $search['deptSelect'] = MstDept::deptSelectList();
      $search['userSelect'] = User::userSelectList();
    } else {
      $search['deptSelect'] = MstDept::deptSelectEmployList($user);
      $search['userSelect'] = User::userSelectEmployList($user);
    }
    // レポート一覧取得
    if ($user['role'] === 8) {
      $depts = User::getCompanyArray($user['id']);
      $reports = WeeklyReport::whereIn('dept_id', $depts)->orderBy('report_date1', 'desc')->get();
    } elseif ($user['role'] === 4) {
      $reports = WeeklyReport::where('dept_id', $user['dept_id'])->orderByRaw('report_date1 desc, dept_id desc')->get();
    } else {
      $reports = WeeklyReport::where('user_id', $user['id'])->orderBy('report_date1', 'desc')->get();
    }
    // 検索
    $item = [
      'date1' => '',
      'date2' => '',
      'dept' => '',
      'employ' => ''
    ];

    return view(
      'report_weekly.index',
      compact(
        'user',
        'reports',
        'search',
        'item'
      )
    );
  }

  /**
   * Show the form for creating a new resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    $user = Auth::user();
    $list = array(
      'situationList' => WeeklyReport::situationList(),
      'date1' => null,
      'date2' => null,
      'this_week' => null,
      'next_week' => null
    );

    if (empty($request->date1) || empty($request->date2)) {
      $data = array();
      $plan = array();
    } else {
      // 週報の期間を取得
      $date1 = $request->date1;
      $date2 = $request->date2;
      $list['date1'] = date('Y-m-d', strtotime($date1));
      $list['date2'] = date('Y-m-d', strtotime($date2));
      $list['plan1'] = date('Y-m-d', strtotime($list['date1'] . '+7 day'));
      $list['plan2'] = date('Y-m-d', strtotime($list['date2'] . '+7 day'));

      // 同期間で週報がないか確認
      $report = WeeklyReport::whereRaw(
        'user_id = :user_id AND report_date1 = :report_date1 AND report_date2 = :report_date2',
        [
          ':user_id' => $user['id'],
          ':report_date1' => $list['date1'],
          ':report_date2' => $list['date2']
        ]
      )->first();

      // 週報があった場合、既存のデータを取得
      if (!is_null($report)) {
        return redirect()
        ->route(
          'weekly-report.edit',
          $report['id']
        );
      // 週報がなかった場合、日報データを反映
      } else {
        // 今週の業務を取得
        $cnt = 0;
        for ($i = $list['date1']; $i <= $list['date2']; $i = date('Y-m-d', strtotime($i . '+1 day'))) {
          $week = array('日', '月', '火', '水', '木', '金', '土');
          $datetime = new DateTime($i);
          $weekday = $week[(int)$datetime->format('w')];
          $plans = Report::whereRaw(
            'user_id = :user_id AND report_date = :report_date',
            [
              ':user_id'  => $user['id'],
              ':report_date' => $i
            ]
          )->first();
          if (!is_null($plans)) {
            $action = $plans['todays_plan'];
            $actions = ReportAction::where('report_id', $plans['id'])->get();
            if (!empty($actions)) {
              foreach ($actions as $act) {
                $action .= WeeklyReportList::getActionRow($act);
              }
            }
          } else {
            $action = null;
          }
          $data[$cnt] =
            [
              'date' => date('n/j', strtotime($i)),
              'weekday' => $weekday,
              'action' => $action,
            ];
          $cnt++;
        }

        // 来週の予定を取得
        $cnt = 0;
        for ($i = $list['plan1']; $i <= $list['plan2']; $i = date('Y-m-d', strtotime($i . '+1 day'))) {
          $week = array('日', '月', '火', '水', '木', '金', '土');
          $datetime = new DateTime($i);
          $weekday = $week[(int)$datetime->format('w')];
          $plans = Report::select('todays_plan')
            ->whereRaw(
              'user_id = :user_id AND report_date = :report_date',
              [
                ':user_id'  => $user['id'],
                ':report_date' => $i
              ]
            )
            ->first();
          if (!is_null($plans)) {
            $action = $plans['todays_plan'];
          } else {
            $action = null;
          }
          $plan[$cnt] =
            [
              'date' => date('n/j', strtotime($i)),
              'weekday' => $weekday,
              'action' => $action,
            ];
          $cnt++;
        }
      }
    }

    return
      view(
        'report_weekly.create',
        compact(
          'user',
          'data',
          'plan',
          'list'
        )
      );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $id = '';

    try {
      DB::transaction(function () use ($request) {
        global $id;
        // データ整形
        $request['user_id'] = Auth::user()->id;
        $request['dept_id'] = Auth::user()->dept_id;
        // 週報を登録
        $report = new WeeklyReport;
        $report->fill($request->all())->save();
        $id = $report->id;
        // 今週の作業を登録
        $actions = $request->action_list;
        if (isset($actions)) {
          foreach ($actions as $act) {
            $act = WeeklyReportList::checkArrayData($act);
            $data = [
              'weekly_report_id' => $id,
              'date' => $act['date'],
              'weekday' => $act['weekday'],
              'situation' => 0,
              'work_flg' => $act['work_flg'],
              'action' => $act['action'],
            ];
            WeeklyReportList::create($data);
          }
        }
        // 来週の予定を登録
        $plans = $request->action_plan;
        if (isset($plans)) {
          foreach ($plans as $plan) {
            $plan = WeeklyReportList::checkArrayData($plan);
            $data = [
              'weekly_report_id' => $id,
              'date' => $plan['date'],
              'weekday' => $plan['weekday'],
              'situation' => 2,
              'work_flg' => $plan['work_flg'],
              'action' => $plan['action'],
            ];
            WeeklyReportList::create($data);
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'weekly-report.show',
        $id
      )
      ->with([
        'message' => '週報を保存しました',
        'status' => 'info'
      ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = Auth::user();
    $report = WeeklyReport::findOrFail($id);
    $report['prev'] = WeeklyReport::getPrev($id, $report, $user);
    $report['next'] = WeeklyReport::getNext($id, $report, $user);
    $actions = WeeklyReportList::whereRaw(
      'weekly_report_id = :weekly_report_id AND situation = :situation',
      [
        ':weekly_report_id' => $id,
        ':situation' => 0
      ]
    )->orderBy('id', 'asc')->get();
    $plans = WeeklyReportList::whereRaw(
      'weekly_report_id = :weekly_report_id AND situation = :situation',
      [
        ':weekly_report_id' => $id,
        ':situation' => 2
      ]
    )->orderBy('id', 'asc')->get();

    return
      view(
        'report_weekly.show',
        compact(
          'id',
          'report',
          'actions',
          'plans'
        )
      );
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = Auth::user();
    $report = WeeklyReport::findOrFail($id);
    $list['situationList'] = WeeklyReport::situationList();
    $item['date1'] = date('Y-m-d', strtotime($report['report_date1']));
    $item['date2'] = date('Y-m-d', strtotime($report['report_date2']));
    $item['plan1'] = date('Y-m-d', strtotime($item['date1'] . '+7 day'));
    $item['plan2'] = date('Y-m-d', strtotime($item['date2'] . '+7 day'));

    // 今週の業務を取得
    $report['action'] = WeeklyReportList::whereRaw(
      'weekly_report_id = :weekly_report_id AND situation = :situation',
      [
        ':weekly_report_id'  => $id,
        ':situation' => 0
      ]
    )->get();

    // 来週の予定を取得
    $report['plan'] = WeeklyReportList::whereRaw(
      'weekly_report_id = :weekly_report_id AND situation = :situation',
      [
        ':weekly_report_id'  => $id,
        ':situation' => 2
      ]
    )->get();

    return
      view(
        'report_weekly.edit',
        compact(
          'id',
          'user',
          'report',
          'list'
        )
      );
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    try {
      DB::transaction(function () use ($request, $id) {
        // 週報を保存
        $report = WeeklyReport::findOrFail($id);
        $report->fill($request->all())->save();
        // 今週の業務を保存
        $actions = $request->action_list;
        if (isset($actions)) {
          foreach ($actions as $act) {
            $act = WeeklyReportList::checkArrayData($act);
            DB::table('weekly_report_lists')
              ->updateOrInsert(
                [
                  'id' => $act['id']
                ],
                [
                  'work_flg' => $act['work_flg'],
                  'action' => $act['action'],
                ]
              );
          }
        }
        // 来週の予定を登録
        $plans = $request->action_plan;
        if (isset($plans)) {
          foreach ($plans as $plan) {
            $act = WeeklyReportList::checkArrayData($plan);
            DB::table('weekly_report_lists')
              ->updateOrInsert(
                [
                  'id' => $act['id']
                ],
                [
                  'work_flg' => $act['work_flg'],
                  'action' => $act['action'],
                ]
              );
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'weekly-report.show',
        $id
      )
      ->with([
        'message' => '週報を保存しました',
        'status' => 'info'
      ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    WeeklyReport::findOrFail($id)->delete();
    WeeklyReportList::where('weekly_report_id', $id)->delete();

    return redirect()
      ->route('weekly-report.index')
      ->with([
        'message' => '週報を削除しました',
        'status' => 'danger'
      ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function search(Request $request)
  {
    // 検索結果
    $item = [
      'date1' => $request->search['date1'],
      'date2' => $request->search['date2'],
      'dept' => $request->search['dept'],
      'employ' => $request->search['employ'],
    ];

    // 権限別のselectを取得
    $user = Auth::user();
    if ($user['role'] > 7) {
      $search['deptSelect'] = MstDept::deptSelectList();
      $search['userSelect'] = User::userSelectList();
    } else {
      $search['deptSelect'] = MstDept::deptSelectEmployList($user);
      $search['userSelect'] = User::userSelectEmployList($user);
    }

    // 検索項目で検索結果を変更
    $match = [];
    $matchVar = '';
    $matchParam = [];
    if (!empty($item['date1']) && !empty($item['date2'])) {
      $var = ' AND report_date1 >= :report_date1 AND report_date2 <= :report_date2 ';
      $param = [
        ':report_date1' => $item['date1'],
        ':report_date2' => $item['date2']
      ];
      $matchVar = $matchVar . $var;
      $matchParam = $matchParam + $param;
    } else if (!empty($item['date1']) && empty($item['date2'])) {
      $var = ' AND report_date1 >= :report_date1';
      $param = [':report_date1' => $item['date1']];
      $matchVar = $matchVar . $var;
      $matchParam = $matchParam + $param;
    } else if (empty($item['date1']) && !empty($item['date2'])) {
      $var = ' AND report_date2 <= :report_date2';
      $param = [':report_date2' => $item['date2']];
      $matchVar = $matchVar . $var;
      $matchParam = $matchParam + $param;
    }
    if (!empty($item['dept'])) {
      $var = ' AND dept_id = :dept_id';
      $param = [':dept_id' => $item['dept']];
      $matchVar = $matchVar . $var;
      $matchParam = $matchParam + $param;
    }
    if (!empty($item['employ'])) {
      $var = ' AND user_id = :user_id';
      $param = [':user_id' => $item['employ']];
      $matchVar = $matchVar . $var;
      $matchParam = $matchParam + $param;
    }

    // レポート一覧取得
    if ($user['role'] === 12) {
      $var = 'id > :id';
      $param = [':id' => 0];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = WeeklyReport::whereRaw($matchVar, $matchParam)
        ->orderByRaw('report_date1 desc, dept_id desc')
        ->get();
    } elseif ($user['role'] === 8) {
      $var = 'id > :id';
      $param = [':id' => 0];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = WeeklyReport::whereRaw($matchVar, $matchParam)
        ->orderByRaw('report_date1 desc, dept_id desc')
        ->get();
    } elseif ($user['role'] === 4) {
      $var = 'dept_id = :dept_id';
      $param = [':dept_id' => $user['dept_id']];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = WeeklyReport::whereRaw($matchVar, $matchParam)
        ->orderByRaw('report_date1 desc, dept_id desc')
        ->get();
    } else {
      $var = 'user_id = :user_id';
      $param = [':user_id' => $user['id']];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = WeeklyReport::whereRaw($matchVar, $matchParam)
        ->orderBy('report_date1', 'desc')
        ->get();
    }

    return view(
      'report_weekly.index',
      compact(
        'user',
        'reports',
        'search',
        'item'
      )
    );
  }
}
