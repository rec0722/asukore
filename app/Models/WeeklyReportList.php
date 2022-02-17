<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReportList extends Model
{
  use HasFactory;

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'weekly_report_lists';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'weekly_report_id',
    'date',
    'weekday',
    'situation',
    'work_flg',
    'action'
  ];

  /**
   * [Array]------------------------------------------
   */
  /**
   * Get the action Lists
   */
  public static function checkArrayData($data)
  {
    if (array_key_exists('date', $data) === false) {
      $data['date'] = null;
    }
    if (array_key_exists('weekday', $data) === false) {
      $data['weekday'] = null;
    }
    if (array_key_exists('work_flg', $data) === false) {
      $data['work_flg'] = null;
    } elseif (array_key_exists('work_flg', $data) === true) {
      if ($data['work_flg'] === '3') {
        $data['action'] = '公休';
      } elseif ($data['work_flg'] === '6') {
        $data['action'] = '有給';
      } elseif ($data['work_flg'] === '9') {
        $data['action'] = '祝日';
      }
    }
    if (array_key_exists('action', $data) === false) {
      $data['action'] = null;
    }
    return $data;
  }

  /**
   * Get the action Lists
   */
  public static function getActionRow($data)
  {
    $action = "";
    if (!empty($data['time1']) || !empty($data['time2']) || !empty($data['customer'] || $data['action'])) {
      $action .= "\n";
    }
    if (!empty($data['time1']) && !empty($data['time2'])) {
      $action .= $data['time1'] . '〜' . $data['time2'];
    }
    if (!empty($data['customer'])) {
      $action .=  ' ' . $data['customer'] . ' ';
    }
    if (!empty($data['action'])) {
      $action .= ' ' . $data['action'];
    }
    return $action;
  }
}
