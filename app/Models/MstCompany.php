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
   * Get company Lists
   */
  public static function selectCompanyList()
  {
    $companies = MstCompany::get();
    $companyList = MstCompany::arrayCompanyList($companies, 1);
    return $companyList;
  }

  /**
   * Get the company Lists
   */
  public static function selectEmployList($user)
  {
    if ($user['role'] > 7) {
      $companies = MstCompany::get();
    } else {
      $companies = MstCompany::where('dept_id', $user['dept_id'])->get();
    }
    $companyList = MstCompany::arrayCompanyList($companies, 2);
    return $companyList;
  }

  /**
   * created Option Lists
   */
  public static function arrayCompanyList($data, $type)
  {
    if ($type === 1) {
      $companyList = ['' => '▼ 選択してください'];
    } else {
      $companyList = ['' => ''];
    }
    foreach ($data as $item) {
      $companyList[$item['id']] = $item['name'];
    }
    return $companyList;
  }

  /**
   * Get the prefecture Lists
   */
  public static function prefectureList()
  {
    $prefs = MstPrefecture::get();
    $prefList = ['' => '▼ 選択してください'];
    foreach ($prefs as $pref) {
      $prefList[$pref['id']] = $pref['name'];
    }
    return $prefList;
  }
}
