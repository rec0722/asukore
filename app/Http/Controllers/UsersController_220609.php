<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDept;
use App\Models\MstCompany;
use App\Models\MstCompanyGroup;
use App\Models\MstDept;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersController extends Controller
{
  /**
   * 新しいMstCompanyインスタンスの生成
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:web');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::all();
    for ($i = 0; $i < count($users); $i++) {
      $users[$i]['role'] = User::getRole($users[$i]['role']);
    }

    return view(
      'mst_user.index',
      compact(
        'users',
      )
    );
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $user['companyList'] = MstCompany::companyList();
    $user['deptSelect'] = MstDept::deptSelectList();
    $user['deptCheck'] = MstDept::deptCreateCheckList($user);
    $user['role'] = '0';
    $user['roleList'] = User::roleList();

    return view(
      'mst_user.create',
      compact(
        'user'
      )
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:50',
      'company_id' => 'required',
      'dept_id' => 'required',
      'role' => 'required',
    ]);

    $id = '';

    try {
      DB::transaction(function () use ($request) {
        global $id;
        $data = [
          'name' => $request->name,
          'company_id' => $request->company_id,
          'dept_id' => $request->dept_id,
          'role' => $request->role,
          'input_free' => $request->input_free,
          'input_time' => $request->input_time,
          'input_pic' => $request->input_pic,
          'email' => $request->email,
          'password' => Hash::make($request->password)
        ];
        // ユーザ登録
        $user = User::create($data);
        $id = $user->id;
        // 閲覧権限登録
        $depts = $request->dept;
        if (!empty($depts)) {
          foreach ($depts as $dept) {
            $data = [
              'user_id' => $id,
              'dept_id' => $dept
            ];
            UserDept::create($data);
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'mst_user.show',
        $id
      )
      ->with([
        'message' => 'ユーザを登録しました',
        'status' => 'info'
      ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::findOrFail($id);
    $user['role'] = User::getRole($user['role']);

    return
      view(
        'mst_user.show',
        compact(
          'id',
          'user'
        )
      );
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = User::findOrFail($id);
    $userDept = UserDept::select('dept_id')->where('user_id', $id)->get()->toArray();
    $group = MstCompanyGroup::where('company_id', $user['company_id'])->first();
    if (!empty($group)) {
      $user['group'] = MstCompanyGroup::getGroupArray($group['group_id']);
    } else {
      $user['group'] = null;
    }
    if ($user['role'] > 7) {
      $user['companyList'] = MstCompany::companyList();
    } else {
      $user['companyList'] = MstCompany::companyEmployList($user);
    }
    $user['deptSelect'] = MstDept::deptSelectEmployList($user);
    $user['deptCheck'] = MstDept::deptCheckList($user);
    $user['roleList'] = User::roleList();

    return view(
      'mst_user.edit',
      compact(
        'id',
        'user',
        'userDept'
      )
    );
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:50',
      'company_id' => 'required',
      'dept_id' => 'required',
      'role' => 'required',
    ]);

    try {
      DB::transaction(function () use ($request, $id) {
        $request = User::UserInputType($request);
        $data = [
          'name' => $request->name,
          'company_id' => $request->company_id,
          'dept_id' => $request->dept_id,
          'role' => $request->role,
          'input_free' => $request->input_free,
          'input_time' => $request->input_time,
          'input_pic' => $request->input_pic,
          'email' => $request->email,
        ];

        if (!is_null($request->password)) {
          $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
          ]);
          $data['password'] = Hash::make($request->password);
        }
        // ユーザ登録
        $user = User::findOrFail($id);
        $user->fill($data)->save();
        // 閲覧権限登録
        $depts = $request->dept;
        if (!empty($depts)) {
          UserDept::where('user_id', $id)->delete();
          foreach ($depts as $dept) {
            $data = [
              'user_id' => $id,
              'dept_id' => $dept
            ];
            UserDept::create($data);
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'mst_user.show',
        $id
      )
      ->with([
        'message' => 'ユーザ情報を更新しました',
        'status' => 'info'
      ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    User::findOrFail($id)->delete();
    Report::where('user_id', $id)->delete();

    return redirect()
      ->route('mst_user.index')
      ->with([
        'message' => 'ユーザを削除しました',
        'status' => 'danger'
      ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getDept(Request $request)
  {
    $depts = MstDept::where('company_id', $request->company_id)->get();
    return $depts;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function trash()
  {
    $users = User::onlyTrashed()->whereNotNull('id')->get();

    return view(
      'mst_user.trash',
      compact(
        'users',
      )
    );
  }

  /**
   * Restore a resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function restore(Request $request)
  {
    User::onlyTrashed()->where('id', $request->id)->restore();
    Report::onlyTrashed()->where('user_id', $request->id)->restore();

    return redirect()
      ->route('mst_user.index');
  }

   /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function forceDelete()
  {
    User::onlyTrashed()->forceDelete();
    Report::onlyTrashed()->forceDelete();

    return redirect()
      ->route('mst_user.index');
  }
}
