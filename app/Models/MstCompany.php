<?php

namespace App\Models;

use App\Models\MstPrefecture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstCompany extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'mst_companies';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'zipcode',
    'prefecture_id',
    'city',
    'address',
    'tel',
    'fax',
    'email',
    'report_num'
  ];

  /**
   * [Relations]------------------------------------------
   */
  /**
   * the Prefecture related to Company
   */
  public function prefecture()
  {
    return $this->belongsTo(MstPrefecture::class, 'prefecture_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * [Select Lists]------------------------------------------
   */
  /**
   * Get the company Lists
   */
  public static function companyList()
  {
    $companies = MstCompany::get();
    $companyList = array(
      '' => '▼ 選択してください'
    );
    foreach ($companies as $com) {
      $var = array($com->id => $com->name);
      $companyList = $companyList + $var;
    }
    return $companyList;
  }

  /**
   * Get the company Lists
   */
  public static function companyEmployList($user)
  {
    $companies = MstCompany::where('id', $user['company_id'])->get();
    $companyList = array(
      '' => '▼ 選択してください'
    );
    foreach ($companies as $com) {
      $var = array($com->id => $com->name);
      $companyList = $companyList + $var;
    }
    return $companyList;
  }

  /**
   * Get the prefecture Lists
   */
  public static function prefectureList()
  {
    $prefs = MstPrefecture::get();
    $prefList = array(
      '' => '▼ 選択してください'
    );
    foreach ($prefs as $pref) {
      $var = array($pref->id => $pref->name);
      $prefList = $prefList + $var;
    }
    return $prefList;
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
}
