<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDept extends Model
{
    use HasFactory;

    /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'user_depts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_id',
    'dept_id'
  ];
}
