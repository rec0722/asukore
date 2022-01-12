<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPrefecture extends Model
{
    use HasFactory;

    /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'mst_prefectures';
}
