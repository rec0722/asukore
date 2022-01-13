<?php

namespace App\Models;

use App\Models\MstCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstGroup extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'mst_groups';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name'
  ];

  /**
   * [Relations]------------------------------------------
   */
  /**
   * the company related to MstCompanies
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
   * Get the company Lists
   */
  public static function companyList()
  {
    $companies = MstCompany::get();
    $companyList = array();
    foreach ($companies as $com) {
      $var = array($com->id => $com->name);
      $companyList = $companyList + $var;
    }
    return $companyList;
  }
}
