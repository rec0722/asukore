<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportAction;
use App\Models\ReportImage;
use App\Models\User;
use App\Models\MstDept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
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
    // 検索項目を取得
    $search['deptSelect'] = MstDept::selectDeptList($user, 'spec', 1);
    $search['userSelect'] = User::selectUserList($user);
    // sessionを保有しているか確認
    $session = Report::getSessionData();
    // レポート一覧取得
    $reports = Report::indexReportList($user, $session);
    // 検索
    $item = [
      'date1' => $session['date1'],
      'date2' => $session['date2'],
      'dept' => $session['dept'],
      'employ' => $session['employ']
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
    $item['dateList'] = Report::getReportDate();
    $item['rows'] = MstDept::findOrFail($user->dept_id)->report_num;
    $item['text1'] = MstDept::findOrFail($user->dept_id)->report_text1;
    $item['text2'] = MstDept::findOrFail($user->dept_id)->report_text2;
    $item['text3'] = MstDept::findOrFail($user->dept_id)->report_text3;
    $item['text4'] = MstDept::findOrFail($user->dept_id)->report_text4;
    $userInfo = User::findOrFail($user->id);
    $item = Report::getInputType($item, 'free', $userInfo);
    $item = Report::getInputType($item, 'time', $userInfo);
    $item = Report::getInputType($item, 'pic', $userInfo);
    // スマホを判定し、時間入力方式を決定
    $item['agent'] = new Agent();
    // dateListがない場合、当日のレポートを表示
    if (empty($item['dateList'])) {
      // 当日のレポートを編集
      $report = Report::whereRaw(
        'user_id = :user_id AND report_date = :report_date',
        [
          ':user_id' => $user['id'],
          ':report_date' => date('Y-m-d'),
        ]
      )->first();

      return redirect()->route('report.edit', $report['id']);
    } else {
      // 新規の日報作成画面を表示
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
        // 別タブで開いている等、既存データがないか確認
        $matchThese = 'user_id = :user_id AND dept_id = :dept_id AND report_date = :report_date';
        $matchParam = [
          ':user_id' => $request['user_id'],
          ':dept_id' => $request['dept_id'],
          ':report_date' => $request['report_date'],
        ];
        $report = Report::whereRaw($matchThese, $matchParam)->first();
        // 日報が空の場合、新しいデータを登録
        if (empty($report)) {
          $report = new Report;
        }
        $report->fill($request->all())->save();
        $id = $report->id;
        // 時間別の作業内容を登録
        $actions = $request->action_list;
        if (isset($actions)) {
          foreach ($actions as $act) {
            $act = ReportAction::checkArrayData($act);
            if ($act['delete_flg'] !== '1' && count(array_filter($act)) !== 0) {
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
        // 画像報告を登録
        if ($request->hasFile('todays_image')) {
          $images = $request->file('todays_image');
          $file = ReportImage::uploadFile($images, $id);
          $data = [
            'report_id' => $id,
            'file' => $file,
            'sort' => 1,
          ];
          ReportImage::create($data);
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
    $authUser = Auth::user();
    $authUser['edit_time'] = $authUser['dept']['edit_time'];
    $report = Report::findOrFail($id);
    $report['prev'] = Report::getPrev($id, $report, $authUser);
    $report['next'] = Report::getNext($id, $report, $authUser);
    $actions = ReportAction::where('report_id', $id)->orderBy('id', 'asc')->get();
    for ($i = 0; $i < count($actions); $i++) {
      $actions[$i]['time'] = ReportAction::getActionTime($actions[$i]);
    }
    $images = ReportImage::where('report_id', $id)->orderBy('sort', 'asc')->get();
    for ($i = 0; $i < count($images); $i++) {
      $images[$i]['url'] = ReportImage::getFileUrl($images[$i], $id);
    }
    // 編集期間の判定
    if ($report['user_id'] === $authUser['id']) {
      $date['today'] = date('Y-m-d');
      $date['min'] = date('Y-m-d', strtotime('-' . $authUser['edit_time'] . 'day'));
      $date['report'] = date('Y-m-d', strtotime($report['report_date']));
      if ($date['min'] <= $date['report'] && $date['report'] <= $date['today']) {
        $report['edit'] = true;
      } else {
        $report['edit'] = false;
      }
    } else {
      $report['edit'] = false;
    }

    return
      view(
        'report.show',
        compact(
          'id',
          'report',
          'actions',
          'images'
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
    $images = ReportImage::where('report_id', $id)->orderBy('sort', 'asc')->get();
    for ($i = 0; $i < count($images); $i++) {
      $images[$i]['url'] = ReportImage::getFileUrl($images[$i], $id);
    }
    $userInfo = User::findOrFail(Auth::user()->id);
    $item = array();
    $item['text1'] = MstDept::findOrFail(Auth::user()->dept_id)->report_text1;
    $item['text2'] = MstDept::findOrFail(Auth::user()->dept_id)->report_text2;
    $item['text3'] = MstDept::findOrFail(Auth::user()->dept_id)->report_text3;
    $item['text4'] = MstDept::findOrFail(Auth::user()->dept_id)->report_text4;
    $item = Report::getInputType($item, 'free', $userInfo);
    $item = Report::getInputType($item, 'time', $userInfo);
    $item = Report::getInputType($item, 'pic', $userInfo);
    // スマホを判定し、時間入力方式を決定
    $item['agent'] = new Agent();

    return
      view(
        'report.edit',
        compact(
          'id',
          'report',
          'actions',
          'images',
          'item'
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
        // 画像報告を登録
        if ($request->hasFile('todays_image')) {
          $images = $request->file('todays_image');
          $file = ReportImage::uploadFile($images, $id);
          $data = [
            'report_id' => $id,
            'file' => $file,
            'sort' => 1,
          ];
          ReportImage::create($data);
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
   * Delete Images
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function deleteImg(Request $request)
  {
    $images = ReportImage::where('id', $request->id)->first();
    $images['url'] = ReportImage::getDeleteUrl($images);
    Storage::disk('public')->delete($images['url']);
    $images->delete();
    return $images;
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

    // sessionを保存
    $sessionData = [
      'date1' => $request->search['date1'],
      'date2' => $request->search['date2'],
      'dept' => $request->search['dept'],
      'employ' => $request->search['employ'],
    ];
    $request->session()->put($sessionData);

    return redirect()->route('report.index');
  }
}
