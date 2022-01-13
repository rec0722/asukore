<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCompanyGroup extends Model
{
  use HasFactory;

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'mst_company_groups';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'group_id',
    'company_id'
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
   * Get the group Lists
   */
  public static function groupList()
  {
    $groups = MstGroup::get();
    $groupList = array(
      '' => '▼ 選択してください'
    );
    foreach ($groups as $item) {
      $var = array($item->id => $item->name);
      $groupList = $groupList + $var;
    }
    return $groupList;
  }

  /**
   * [Array]------------------------------------------
   */
  /**
   * Get the group Lists
   */
  public static function getGroupArray($id)
  {
    $groups = MstCompanyGroup::where('group_id', $id)->get()->toArray();
    $groupList = [];
    foreach ($groups as $item) {
      $groupList[] = $item['company_id'];
    }
    return $groupList;
  }
}
