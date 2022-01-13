<?php

namespace App\Http\Controllers;

use App\Models\MstGroup;
use App\Models\MstCompanyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MstGroupsController extends Controller
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
    $groups = MstGroup::all();
    for ($i = 0; $i < count($groups); $i++) {
      $groups[$i]['company'] = MstCompanyGroup::where('group_id', $groups[$i]['id'])->get();
    }

    return view(
      'mst_group.index',
      compact(
        'groups',
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
    $list['companyList'] = MstGroup::companyList();

    return view(
      'mst_group.create',
      compact(
        'list'
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
      'name' => 'required|string|max:50'
    ]);

    try {
      DB::transaction(function () use ($request) {
        $data = [
          'name' => $request->name
        ];
        // グループ登録
        $group = MstGroup::create($data);
        $id = $group->id;
        // 閲覧権限登録
        $lists = $request->company_id;
        if (!empty($lists)) {
          foreach ($lists as $item) {
            $data = [
              'group_id' => $id,
              'company_id' => $item
            ];
            MstCompanyGroup::create($data);
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'mst_group.index'
      )
      ->with([
        'message' => 'グループを登録しました',
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
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $group = MstGroup::findOrFail($id);
    $group['companyList'] = MstCompanyGroup::where('group_id', $id)->get()->toArray();
    $list['companyList'] = MstGroup::companyList();

    return view(
      'mst_group.edit',
      compact(
        'id',
        'group',
        'list'
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
      'name' => 'required|string|max:50'
    ]);

    try {
      DB::transaction(function () use ($request, $id) {
        $data = [
          'name' => $request->name
        ];
        // グループ登録
        $group = MstGroup::findOrFail($id);
        $group->fill($data)->save();
        // 閲覧権限登録
        $lists = $request->company_id;
        if (!empty($lists)) {
          MstCompanyGroup::where('group_id', $id)->delete();
          foreach ($lists as $item) {
            $data = [
              'group_id' => $id,
              'company_id' => $item
            ];
            MstCompanyGroup::create($data);
          }
        }
      }, 2);
    } catch (Throwable $e) {
      Log::error($e);
      throw ($e);
    }

    return redirect()
      ->route(
        'mst_group.index'
      )
      ->with([
        'message' => 'グループを更新しました',
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
    MstGroup::findOrFail($id)->delete();
    $groups = MstCompanyGroup::where('group_id', $id)->get();
    foreach($groups as $item) {
      MstCompanyGroup::findOrFail($item['id'])->delete();
    }

    return redirect()
      ->route('mst_group.index')
      ->with([
        'message' => 'グループ情報を削除しました',
        'status' => 'danger'
      ]);
  }
}
