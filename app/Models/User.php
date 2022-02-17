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
    'input_free',
    'input_time',
    'input_pic',
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
      [
        'id' => '0',
        'name' => '従業員',
        'tooltip' => '自分が作成した日報のみ表示されます'
      ],
      // '1' => '',
      // '2' => '',
      // '3' => '',
      [
        'id' => '4',
        'name' => '管理職',
        'tooltip' => '所属している部署の日報が表示されます'
      ],
      // '5' => '',
      // '6' => '',
      // '7' => '',
      [
        'id' => '8',
        'name' => '役員',
        'tooltip' => '閲覧権限で選択した部署の日報が表示されます'
      ],
      // '9' => '',
      // '10' => '',
      // '11' => '',
      [
        'id' => '12',
        'name' => 'システム管理者',
        'tooltip' => 'システムに関する全ての情報を設定できます'
      ],
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
      '' => ''
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
      '' => ''
    );
    foreach ($users as $user) {
      $var = array($user->id => $user->name);
      $userList = $userList + $var;
    }
    return $userList;
  }

  /**
   * [Array]------------------------------------------
   */
  /**
   * Get the company Lists
   */
  public static function getCompanyArray($id)
  {
    $depts = UserDept::where('user_id', $id)->get()->toArray();
    $deptList = [];
    foreach ($depts as $item) {
      $deptList[] = $item['dept_id'];
    }
    return $deptList;
  }

  /**
   * Get data for input type.
   */
  public static function UserInputType($request)
  {
    $itemLists = $request->toArray();
    // フリー入力
    if (array_key_exists('input_free', $itemLists) === false) {
      $request['input_free'] = 0;
    } else {
      $request['input_free'] = 1;
    }
    // 時間制入力
    if (array_key_exists('input_time', $itemLists) === false) {
      $request['input_time'] = 0;
    } else {
      $request['input_time'] = 1;
    }
    // 画像報告
    if (array_key_exists('input_pic', $itemLists) === false) {
      $request['input_pic'] = 0;
    } else {
      $request['input_pic'] = 1;
    }
    return $request;
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
      case '12':
        $role = "システム管理者";
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
      case '12':
        $dept = "システム管理者";
        break;
    }
    return $dept;
  }
}
