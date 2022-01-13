<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportAction;
use App\Models\User;
use App\Models\UserDept;
use App\Models\MstCompany;
use App\Models\MstDept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportsController extends Controller
{
  /**
   * 新しいMstCompanyインスタンスの生成
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:web');

    $this->middleware(function ($request, $next) {
      $id = $request->route()->report;
      $user = Auth::user();
      if (!is_null($id)) {
        if ($user['role'] === 0) {
          $report = Report::findOrFail($id);
          if ($user['id'] !== $report['user_id']) {
            abort(404);
          }
        } elseif ($user['role'] === 4) {
          $report = Report::findOrFail($id);
          if ($user['dept_id'] !== $report['dept_id']) {
            abort(404);
          }
        }
      }
      return $next($request);
    });
  }

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
      //$reports = Report::orderByRaw('report_date desc, dept_id desc')->get();
      $reports = Report::whereIn('dept_id', $depts)->orderBy('report_date', 'desc')->get();
    } elseif ($user['role'] === 4) {
      $reports = Report::where('dept_id', $user['dept_id'])->orderByRaw('report_date desc, dept_id desc')->get();
    } else {
      $reports = Report::where('user_id', $user['id'])->orderBy('report_date', 'desc')->get();
    }
    // 検索
    $item = [
      'date' => '',
      'dept' => '',
      'employ' => ''
    ];

    return view(
      'report.index',
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
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $user = Auth::user();
    $item['date'] = date('Y-m-d');
    $item['rows'] = MstDept::findOrFail($user->dept_id)->report_num;
    $report = Report::whereRaw(
      'user_id = :user_id AND report_date = :report_date',
      [
        ':user_id' => $user->id,
        ':report_date' => $item['date']
      ]
    )->first();

    if (!is_null($report)) {
      return redirect()->route('report.edit', $report->id);
    } else {
      return
        view(
          'report.create',
          compact(
            'user',
            'item'
          )
        );
    }
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
        $request['report_date'] = date('Y-m-d');
        // 日報を登録
        $report = new Report;
        $report->fill($request->all())->save();
        $id = $report->id;
        // 時間別の作業内容を登録
        $actions = $request->action_list;
        if (isset($actions)) {
          foreach ($actions as $act) {
            if (array_key_exists('time1', $act) === false) {
              $act['time1'] = null;
            }
            if (array_key_exists('time2', $act) === false) {
              $act['time2'] = null;
            }
            if (array_key_exists('customer', $act) === false) {
              $act['customer'] = null;
            }
            if (array_key_exists('action', $act) === false) {
              $act['action'] = null;
            }
            if (array_key_exists('approach', $act) === false) {
              $act['approach'] = null;
            }
            if ($act['delete_flg'] !== '1') {
              $data = [
                'report_id' => $id,
                'time1' => $act['time1'],
                'time2' => $act['time2'],
                'customer' => $act['customer'],
                'action' => $act['action'],
                'approach' => $act['approach'],
              ];
              ReportAction::create($data);
            }
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'report.show',
        $id
      )
      ->with([
        'message' => '日報を報告しました',
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
    $report = Report::findOrFail($id);
    $report['prev'] = Report::getPrev($id, $report, $user);
    $report['next'] = Report::getNext($id, $report, $user);
    $actions = ReportAction::select('*')
      ->where('report_id', $id)
      ->orderBy('id', 'asc')->get();
    for ($i = 0; $i < count($actions); $i++) {
      $actions[$i]['time'] = ReportAction::getActionTime($actions[$i]);
    }

    return
      view(
        'report.show',
        compact(
          'id',
          'report',
          'actions'
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
    $report = Report::findOrFail($id);
    $actions = ReportAction::where('report_id', $id)->orderBy('id', 'asc')->get();
    for ($i = 0; $i < count($actions); $i++) {
      $actions[$i]['time'] = ReportAction::getActionTime($actions[$i]);
    }

    return
      view(
        'report.edit',
        compact(
          'id',
          'report',
          'actions'
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
        // 日報を登録
        $report = Report::findOrFail($id);
        $report->fill($request->all())->save();
        // 時間別の作業内容を登録
        $actions = $request->action_list;
        if (isset($actions)) {
          foreach ($actions as $act) {
            if ($act['delete_flg'] === '1') {
              $item = ReportAction::where('id', $act['id'])->get();
              if (!is_null($item)) {
                ReportAction::findOrFail($act['id'])->delete();
              }
            } else {
              DB::table('report_actions')
                ->updateOrInsert(
                  ['id' => $act['id']],
                  [
                    'report_id' => $report['id'],
                    'time1' => $act['time1'],
                    'time2' => $act['time2'],
                    'customer' => $act['customer'],
                    'action' => $act['action'],
                    'approach' => $act['approach'],
                  ]
                );
            }
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'report.show',
        $id
      )
      ->with([
        'message' => '日報を更新しました',
        'status' => 'info'
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
      'date' => $request->search['date'],
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
    if (!empty($item['date'])) {
      $var = ' AND report_date = :report_date';
      $param = [':report_date' => $item['date']];
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
    if ($user['role'] === 8) {
      $var = 'id > :id';
      $param = [':id' => 0];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      /*
      $reports = Report::whereIn('dept_id', function ($query) use ($user) {
        $query->select('dept_id')->from('user_depts')->where('user_id', $user['id']);
      })->orderBy('report_date', 'desc')->get();
      */
      $reports = Report::whereRaw($matchVar, $matchParam)
                  ->orderByRaw('report_date desc, dept_id desc')
                  ->get();
    } elseif ($user['role'] === 4) {
      $var = 'dept_id = :dept_id';
      $param = [':dept_id' => $user['dept_id']];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = Report::whereRaw($matchVar, $matchParam)
                  ->orderByRaw('report_date desc, dept_id desc')
                  ->get();
    } else {
      $var = 'user_id = :user_id';
      $param = [':user_id' => $user['id']];
      $matchVar = $var . $matchVar;
      $matchParam = $param + $matchParam;
      $reports = Report::whereRaw($matchVar, $matchParam)
                  ->orderBy('report_date', 'desc')
                  ->get();
    }

    return view(
      'report.index',
      compact(
        'user',
        'reports',
        'search',
        'item'
      )
    );
  }
}
