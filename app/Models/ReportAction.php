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
   * Get action time
   */
  public static function getActionTime($action)
  {
    if (!empty($action['time1']) && !empty($action['time2'])) {
      $time = $action['time1'] . '〜' . $action['time2'];
    } elseif (!empty($action['time1']) || !empty($action['time2'])) {
      if (!empty($action['time1'])) {
        $time = $action['time1'] . '〜';
      } else {
        $time = '〜' . $action['time2'];
      }
    } else {
      $time = null;
    }
    return $time;
  }
}
