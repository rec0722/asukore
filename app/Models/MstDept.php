<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDept extends Model
{
  use HasFactory;

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'mst_depts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'company_id',
    'name',
    'edit_time',
    'report_text1',
    'report_text2',
    'report_text3',
    'report_text4',
    'report_num'
  ];

  /**
   * [Relations]------------------------------------------
   */

  /**
   * the Company related to Deptures
   */
  public function company()
  {
    return $this->belongsTo(MstCompany::class, 'company_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * [Select Lists]------------------------------------------
   */
  /**
   * Get the depture Lists - MstUser create
   */
  public static function selectDeptList($user, $sql, $type)
  {
    if ($user['role'] >= 8 && $sql === 'all') {
      $depts = MstDept::get();
    } elseif ($user['role'] >= 8 && $sql === 'spec') {
      $userDept = UserDept::select('dept_id')->where('user_id', $user['id'])->get()->toArray();
      $depts = MstDept::whereIn('id', $userDept)->get();
    }else {
      $depts = MstDept::where('company_id', $user['company_id'])->get();
    }
    $deptList = MstDept::arrayDeptList($depts, $type);
    return $deptList;
  }

  /**
   * created Option Lists
   */
  public static function arrayDeptList($data, $type)
  {
    if ($type === 1) {
      $deptList = ['' => ''];
    } else {
      $deptList = [];
    }
    foreach ($data as $item) {
      $deptList[$item['id']] = $item['name'];
    }
    return $deptList;
  }

  /**
   * Get edit Timing
   */
  public static function editTiming()
  {
    $rows = [
      '0' => '当日のみ',
      '1' => '1日前',
      '2' => '2日前',
      '3' => '3日前',
      '4' => '4日前',
      '5' => '5日前',
      '6' => '6日前',
      '7' => '7日前',
    ];
    return $rows;
  }

  /**
   * Get report Row
   */
  public static function reportRow()
  {
    $rows = [
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5',
      '6' => '6',
      '7' => '7',
      '8' => '8',
      '9' => '9',
      '10' => '10',
    ];
    return $rows;
  }

  /**
   * [check Lists]------------------------------------------
   */
  /**
   * Get the depture Lists - create
   */
  public static function checkDeptList()
  {
    $depts = MstDept::all();
    $deptList = MstDept::arrayDeptList($depts, 2);
    return $deptList;
  }

  /**
   * [data]------------------------------------------
   */
  /**
   * Get the default Report Table
   */
  public static function getReportTableDefault()
  {
    $data = [
      'report_text1' => '時間',
      'report_text2' => 'お客様名',
      'report_text3' => '作業内容',
      'report_text4' => '契約・販売・作業・打ち合わせ結果'
    ];
    return $data;
  }

  /**
   * Get the default Report Table
   */
  public static function getReportTableData($data)
  {
    $data = [
      'text1' => $data['report_text1'],
      'text2' => $data['report_text2'],
      'text3' => $data['report_text3'],
      'text4' => $data['report_text4']
    ];
    return $data;
  }
}
