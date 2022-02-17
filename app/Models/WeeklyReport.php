<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
  use HasFactory;

  protected $dates = [
    'report_date1',
    'report_date2',
    'deleted_at'
  ];

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'weekly_reports';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'dept_id',
    'title',
    'report_date1',
    'report_date2',
    'this_week',
    'next_week'
  ];

  /*
  * [Relations]------------------------------------------
  */

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
   * Get situation Lists
   */
  public static function situationList()
  {
    $situation = array(
      '0' => '出勤',
      // '1' => '',
      // '2' => '',
      '3' => '公休',
      // '4' => '',
      // '5' => '',
      '6' => '有給',
      // '7' => '',
      // '8' => '',
      '9' => '祝日',
    );
    return $situation;
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
      $items = WeeklyReport::whereRaw(
        'user_id = :user_id AND report_date1 < :report_date1',
        [
          ':user_id' => $user['id'],
          ':report_date1' => $report['report_date1']
        ]
      )->orderBy('id', 'desc')->first();
      // 管理者の場合
    } elseif ($user['role'] > 3 && $user['role'] < 8) {
      $items = WeeklyReport::whereRaw(
        'dept_id = :dept_id AND report_date1 = :report_date1 AND id < :id',
        [
          ':dept_id' => $user['dept_id'],
          ':report_date1' => $report['report_date1'],
          ':id' => $id
        ]
      )->orderBy('id', 'desc')->first();
      // 役員の場合
    } elseif ($user['role'] > 7) {
      $items = WeeklyReport::whereRaw(
        'report_date1 = :report_date1 AND id < :id',
        [
          ':report_date1' => $report['report_date1'],
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
      $items = WeeklyReport::whereRaw(
        'user_id = :user_id AND report_date1 > :report_date1',
        [
          ':user_id' => $user['id'],
          ':report_date1' => $report['report_date1']
        ]
      )->first();
      // 管理者の場合
    } elseif ($user['role'] > 3 && $user['role'] < 8) {
      $items = WeeklyReport::whereRaw(
        'dept_id = :dept_id AND report_date1 = :report_date1 AND id > :id',
        [
          ':dept_id' => $user['dept_id'],
          ':report_date1' => $report['report_date1'],
          ':id' => $id
        ]
      )->first();
      // 役員の場合
    } elseif ($user['role'] > 7) {
      $items = WeeklyReport::whereRaw(
        'report_date1 = :report_date1 AND id > :id',
        [
          ':report_date1' => $report['report_date1'],
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
}
