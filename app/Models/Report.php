<?php

namespace App\Models;

use App\Models\ReportAction;
use App\Models\MstDept;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
  public static function getPrev($id, $report, $user)
  {
    $items = null;
    // 従業員の場合
    if ($user['role'] === 0) {
      $items = Report::whereRaw(
        'user_id = :user_id AND report_date < :report_date',
        [
          ':user_id' => $user['id'],
          ':report_date' => $report['report_date']
        ]
      )->orderBy('id', 'desc')->first();
      // 管理者の場合
    } elseif ($user['role'] > 3 && $user['role'] < 8) {
      $items = Report::whereRaw(
        'dept_id = :dept_id AND report_date = :report_date AND id < :id',
        [
          ':dept_id' => $user['dept_id'],
          ':report_date' => $report['report_date'],
          ':id' => $id
        ]
      )->orderBy('id', 'desc')->first();
      // 役員の場合
    } else {
      $items = Report::whereRaw(
        'report_date = :report_date AND id < :id',
        [
          ':report_date' => $report['report_date'],
          ':id' => $id
        ]
      )->orderBy('id', 'desc')->first();
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
  public static function getNext($id, $report, $user)
  {
    $items = null;
    // 従業員の場合
    if ($user['role'] === 0) {
      $items = Report::whereRaw(
        'user_id = :user_id AND report_date > :report_date',
        [
          ':user_id' => $user['id'],
          ':report_date' => $report['report_date']
        ]
      )->first();
      // 管理者の場合
    } elseif ($user['role'] > 3 && $user['role'] < 8) {
      $items = Report::whereRaw(
        'dept_id = :dept_id AND report_date = :report_date AND id > :id',
        [
          ':dept_id' => $user['dept_id'],
          ':report_date' => $report['report_date'],
          ':id' => $id
        ]
      )->first();
      // 役員の場合
    } else {
      $items = Report::whereRaw(
        'report_date = :report_date AND id > :id',
        [
          ':report_date' => $report['report_date'],
          ':id' => $id
        ]
      )->first();
    }

    // 前報告のIDを取得
    if (!is_null($items)) {
      $next = $items['id'];
    } else {
      $next = null;
    }

    return $next;
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
}
