<?php

namespace App\Models;

use App\Models\MstCompany;
use App\Models\MstDept;
use App\Models\UserDept;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  protected $dates = ['deleted_at'];

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'company_id',
    'dept_id',
    'email',
    'password',
    'role',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * [Relations]------------------------------------------
   */
  /**
   * the depture related to Company
   */
  public function company()
  {
    return $this->belongsTo(MstCompany::class, 'company_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * the depture related to Company
   */
  public function dept()
  {
    return $this->belongsTo(MstDept::class, 'dept_id', 'id')->withDefault([
      'name' => '',
    ]);
  }

  /**
   * [Select Lists]------------------------------------------
   */
  /**
   * Get role Lists
   */
  public static function roleList()
  {
    $role = array(
      '0' => '従業員',
      // '1' => '',
      // '2' => '',
      // '3' => '',
      '4' => '管理職',
      // '5' => '',
      // '6' => '',
      // '7' => '',
      '8' => '役員'
    );
    return $role;
  }

  /**
   * Get the user Lists
   */
  public static function userSelectList()
  {
    $users = User::get();
    $userList = array(
      '' => '社員名選択'
    );
    foreach ($users as $user) {
      $var = array($user->id => $user->name);
      $userList = $userList + $var;
    }
    return $userList;
  }

  /**
   * Get the user employ Lists
   */
  public static function userSelectEmployList($user)
  {
    if ($user['role'] > 3) {
      $users = User::where('dept_id', $user['dept_id'])->get();
    } else {
      $users = User::where('id', $user['id'])->get();
    }
    $userList = array(
      '' => '社員選択'
    );
    foreach ($users as $user) {
      $var = array($user->id => $user->name);
      $userList = $userList + $var;
    }
    return $userList;
  }

  /**
   * [Index Value]------------------------------------------
   */
  /**
   * Get value as a role
   */
  public static function getRole($role)
  {
    switch ($role) {
      case '0':
        $role = "従業員";
        break;
      case '4':
        $role = "管理職";
        break;
      case '8':
        $role = "役員";
        break;
    }
    return $role;
  }

  /**
   * Get value as a dept
   */
  public static function getDept($dept)
  {
    switch ($dept) {
      case '0':
        $dept = "従業員";
        break;
      case '4':
        $dept = "管理職";
        break;
      case '8':
        $dept = "役員";
        break;
    }
    return $dept;
  }
}
