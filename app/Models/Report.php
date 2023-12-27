<?php

namespace App\Models;

use App\Models\ReportAction;
use App\Models\MstDept;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Report extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = [
    'report_date',
    'deleted_at'
  ];

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'reports';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'dept_id',
    'user_id',
    'report_date',
    'todays_plan',
    'tomorrow_plan',
    'notices'
  ];

  /*
  * [Relations]------------------------------------------
  */
  /**
   * the reportAction related to Report
   */
  public function reportAction()
  {
    return $this->hasMany(ReportAction::class);
  }

  /**
   * the User related to Report
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * the Dept related to Report
   */
  public function dept()
  {
    return $this->belongsTo(MstDept::class, 'dept_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * [Select Lists]------------------------------------------
   */
  /**
   * Get the Report Date Lists
   */
  public static function getReportDate()
  {
    // 編集期間を取得
    $user = Auth::user();
    $user['edit_time'] = $user['dept']['edit_time'];
    // 当日をセット
    // 編集期間中の日にちをセット
    for ($i = 0; $i <= $user['edit_time']; $i++) {
      $date['date'] = date('Y-m-d', strtotime('-' . $i . 'days'));
      $date['date_str'] = date('Y年m月d日', strtotime('-' . $i . 'days'));
      $dateList[$date['date']] = $date['date_str'];
    }
    // 報告済みの日付を除く
    $report = Report::where('user_id', $user->id)->orderBy('report_date', 'desc')->limit('10')->get();
    foreach ($report as $item) {
      $date = date('Y-m-d', strtotime($item['report_date']));
      if (array_key_exists($date, $dateList)) {
        unset($dateList[$date]);
      }
    }
    return $dateList;
  }

  /**
   * [Get property]------------------------------------------
   */
  /**
   * Get action time(Previous)
   */
  public static function getPrev($id, $report, $auth)
  {
    $items = null;
    // 検索条件を取得
    $session = Report::getSessionData();
    $match = Report::getSearchData($session, $auth);
    $match = Report::setPagingParam($match, $auth, $id, '<');
    if (!empty($match['var'])) {
      $items = Report::whereRaw($match['var'], $match['param'])->orderBy('id', 'desc')->first();
    } else {
      if ($auth['role'] === 12) {
        // システム管理者の場合
        $items = Report::where('id', '<', $id)->first();
      } elseif ($auth['role'] === 8) {
        // 役員の場合
        $depts = User::arrayUserDeptList($auth['id']);
        $items = Report::where('report_date', $report['report_date'])
          ->where('id', '<', $id)
          ->whereIn('dept_id', $depts)
          ->first();
      } elseif ($auth['role'] === 4) {
        // 管理者の場合
        $items = Report::whereRaw(
          'dept_id = :dept_id AND report_date = :report_date AND id < :id',
          [
            ':dept_id' => $auth['dept_id'],
            ':report_date' => $report['report_date'],
            ':id' => $id
          ]
        )->first();
      } else {
        // 従業員の場合
        $items = Report::whereRaw(
          'user_id = :user_id AND report_date < :report_date',
          [
            ':user_id' => $auth['id'],
            ':report_date' => $report['report_date']
          ]
        )->orderBy('id', 'desc')->first();
      }
    }

    // 前報告のIDを取得
    if (!is_null($items)) {
      $prev = $items['id'];
    } else {
      $prev = null;
    }

    return $prev;
  }

  /**
   * Get action time(Next)
   */
  public static function getNext($id, $report, $auth)
  {
    $items = null;
    // 検索条件を取得
    $session = Report::getSessionData();
    $match = Report::getSearchData($session, $auth);
    $match = Report::setPagingParam($match, $auth, $id, '>');
    if (!empty($match['var'])) {
      $items = Report::whereRaw($match['var'], $match['param'])->orderBy('id', 'asc')->first();
    } else {
      if ($auth['role'] === 12) {
        // システム管理者の場合
        $items = Report::where('id', '>', $id)->first();
      } elseif ($auth['role'] === 8) {
        // 役員の場合
        $depts = User::arrayUserDeptList($auth['id']);
        $items = Report::where('report_date', $report['report_date'])
          ->where('id', '>', $id)
          ->whereIn('dept_id', $depts)
          ->first();
      } elseif ($auth['role'] === 4) {
        // 管理者の場合
        $items = Report::whereRaw(
          'dept_id = :dept_id AND report_date = :report_date AND id > :id',
          [
            ':dept_id' => $auth['dept_id'],
            ':report_date' => $report['report_date'],
            ':id' => $id
          ]
        )->first();
      } else {
        // 従業員の場合
        $items = Report::whereRaw(
          'user_id = :user_id AND report_date > :report_date',
          [
            ':user_id' => $auth['id'],
            ':report_date' => $report['report_date']
          ]
        )->first();
      }
    }

    // 前報告のIDを取得
    if (!is_null($items)) {
      $next = $items['id'];
    } else {
      $next = null;
    }

    return $next;
  }

  public static function setPagingParam($data, $auth, $id, $type)
  {
    if (!empty($data['var']) && $auth['role'] === 4) {
      // 管理者の場合、部署IDを付与
      $data['var'] = $data['var'] . ' AND dept_id = :dept_id ';
      $data['param'][':dept_id'] = $auth['dept_id'];
    } elseif (!empty($data['var']) && $auth['role'] === 0) {
      // 従業員の場合、ユーザIDを付与
      $data['var'] = $data['var'] . ' AND user_id = :user_id ';
      $data['param'][':user_id'] = $auth['id'];
    }
    // 現在の投稿IDを追加
    if (!empty($data['var'])) {
      $data['var'] = $data['var'] . ' AND id ' . $type . ' :id ';
      $data['param'][':id'] = $id;
    }
    return $data;
  }

  /**
   * Get value as a role
   */
  public static function getInputType($item, $string, $user)
  {
    $strName = 'input_' . $string;
    $item[$strName] = $user->$strName;
    if ($item[$strName] === 1) {
      $item[$string] = 'report-type-block';
    } else {
      $item[$string] = 'report-type-none';
    }
    return $item;
  }

  /**
   * Get session for search
   */
  public static function getSessionData()
  {
    // 初期値を設定
    $data = [
      'date1' => '',
      'date2' => '',
      'dept' => '',
      'employ' => '',
      'keyword' => ''
    ];
    // sessionの値を確認し、保有している場合には設定
    if (session()->exists('date1')) {
      $data['date1'] = session()->get('date1');
    }
    if (session()->exists('date2')) {
      $data['date2'] = session()->get('date2');
    }
    if (session()->exists('dept')) {
      $data['dept'] = session()->get('dept');
    }
    if (session()->exists('employ')) {
      $data['employ'] = session()->get('employ');
    }
    if (session()->exists('keyword')) {
      $data['keyword'] = session()->get('keyword');
    }
    return $data;
  }

  /**
   * [Index Lists]------------------------------------------
   */
  public static function indexReportList($auth, $session)
  {
    // ページごとの表示件数
    $dispNum = 20;
    // 検索条件を取得
    $match = Report::getSearchData($session, $auth);
    $match = Report::setSearchParam($match, $auth);
    // 検索条件がある場合
    if (!empty($match['var'])) {
      $reportList = Report::whereRaw($match['var'], $match['param'])->orderByRaw('report_date desc, dept_id asc, id desc')->paginate($dispNum);
    } else {
      // 検索条件がない場合、権限ごとに取得
      if ($auth['role'] === 12) {
        // システム管理者
        $reportList = Report::orderByRaw('report_date desc, dept_id desc, id desc')->paginate($dispNum);
      } elseif ($auth['role'] === 8) {
        // 役員
        $depts = User::arrayUserDeptList($auth['id']);
        $reportList = Report::whereIn('dept_id', $depts)->orderByRaw('report_date desc, dept_id desc, id desc')->paginate($dispNum);
      } elseif ($auth['role'] === 4) {
        // 管理者
        $reportList = Report::where('dept_id', $auth['dept_id'])->orderByRaw('report_date desc, id desc')->paginate($dispNum);
      } else {
        // 従業員
        $reportList = Report::where('user_id', $auth['id'])->orderBy('report_date', 'desc')->paginate($dispNum);
      }
    }

    return $reportList;
  }

  public static function getSearchData($session, $auth)
  {
    // 検索条件の配列を設定
    $match = [
      'var' => [],
      'param' => [],
    ];

    // 日付で検索した場合
    if (!empty($session['date1']) && !empty($session['date2'])) {
      array_push($match['var'], 'report_date BETWEEN :report_date1 AND :report_date2');
      $match['param'][':report_date1'] = $session['date1'];
      $match['param'][':report_date2'] = $session['date2'];
    } else if (!empty($session['date1']) && empty($session['date2'])) {
      array_push($match['var'], 'report_date >= :report_date1');
      $match['param'][':report_date1'] = $session['date1'];
    } else if (empty($session['date1']) && !empty($session['date2'])) {
      array_push($match['var'], 'report_date <= :report_date2');
      $match['param'][':report_date2'] = $session['date2'];
    }
    // 部署で検索した場合
    if (!empty($session['dept'])) {
      if (!empty($match['var'])) {
        array_push($match['var'], 'AND');
      }
      array_push($match['var'], 'dept_id = :dept_id');
      $match['param'][':dept_id'] = $session['dept'];
    }
    // 社員で検索した場合
    if (!empty($session['employ'])) {
      if (!empty($match['var'])) {
        array_push($match['var'], 'AND');
      }
      array_push($match['var'], 'user_id = :user_id');
      $match['param'][':user_id'] = $session['employ'];
    }
    // キーワードで検索した場合
    if (!empty($session['keyword'])) {
      if (!empty($match['var'])) {
        array_push($match['var'], 'AND');
      }
      array_push($match['var'], 'todays_plan LIKE :keyword1 OR tomorrow_plan LIKE :keyword2 OR notices LIKE :keyword3');
      $match['param'][':keyword1'] = $session['keyword'];
      $match['param'][':keyword2'] = $session['keyword'];
      $match['param'][':keyword3'] = $session['keyword'];
    }
    $match['var'] = implode(' ', $match['var']);
    return $match;
  }

  public static function setSearchParam($data, $auth)
  {
    if (!empty($data['var']) && $auth['role'] === 4) {
      // 管理者の場合、部署IDを付与
      $data['var'] = $data['var'] . ' AND dept_id = :dept_id ';
      $data['param'][':dept_id'] = $auth['dept_id'];
    } elseif (!empty($data['var']) && $auth['role'] === 0) {
      // 従業員の場合、ユーザIDを付与
      $data['var'] = $data['var'] . ' AND user_id = :user_id ';
      $data['param'][':user_id'] = $auth['id'];
    }
    return $data;
  }
}
