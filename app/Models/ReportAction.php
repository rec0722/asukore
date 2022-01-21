<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAction extends Model
{
  use HasFactory;

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'report_actions';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'report_id',
    'time1',
    'time2',
    'customer',
    'action',
    'approach'
  ];

  /**
   * [Array]------------------------------------------
   */
  /**
   * Get the company Lists
   */
  public static function checkArrayData($data)
  {
    if (array_key_exists('time1', $data) === false) {
      $data['time1'] = null;
    }
    if (array_key_exists('time2', $data) === false) {
      $data['time2'] = null;
    }
    if (array_key_exists('customer', $data) === false) {
      $data['customer'] = null;
    }
    if (array_key_exists('action', $data) === false) {
      $data['action'] = null;
    }
    if (array_key_exists('approach', $data) === false) {
      $data['approach'] = null;
    }
    return $data;
  }

  /**
   * [showData]------------------------------------------
   */
  /**
   * Get action time
   */
  public static function getActionTime($action)
  {
    if (!empty($action['time1']) && !empty($action['time2'])) {
      $time = $action['time1'] . '〜' . $action['time2'];
    } elseif (!empty($action['time1']) && empty($action['time2'])) {
      $time = $action['time1'] . '〜';
    } elseif (empty($action['time1']) && !empty($action['time2'])) {
      $time = '〜' . $action['time2'];
    } else {
      $time = null;
    }
    return $time;
  }
}
