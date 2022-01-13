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
   * Get the depture Lists
   */
  public static function deptSelectList()
  {
    $depts = MstDept::get();
    $deptList = array(
      '' => '部署選択'
    );
    foreach ($depts as $dept) {
      $var = array($dept->id => $dept->name);
      $deptList = $deptList + $var;
    }
    return $deptList;
  }

  /**
   * Get the depture Lists
   */
  public static function deptSelectEmployList($user)
  {
    $depts = MstDept::where('company_id', $user['company_id'])->get();
    $deptList = array(
      '' => '部署名選択'
    );
    foreach ($depts as $dept) {
      $var = array($dept->id => $dept->name);
      $deptList = $deptList + $var;
    }
    return $deptList;
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
  public static function deptCreateCheckList()
  {
    $depts = MstDept::all();
    $deptList = array();
    foreach ($depts as $dept) {
      $var = array($dept->id => $dept->name);
      $deptList = $deptList + $var;
    }
    return $deptList;
  }

  /**
   * Get the depture Lists - edit
   */
  public static function deptCheckList($user)
  {
    $depts = MstDept::where('company_id', $user['company_id'])->get();
    $deptList = array();
    foreach ($depts as $dept) {
      $var = array($dept->id => $dept->name);
      $deptList = $deptList + $var;
    }
    return $deptList;
  }
}
