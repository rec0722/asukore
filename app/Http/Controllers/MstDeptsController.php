<?php

namespace App\Http\Controllers;

use App\Models\MstDept;
use App\Models\MstCompany;
use Illuminate\Http\Request;

class MstDeptsController extends Controller
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
    $depts = MstDept::all();

    return
      view(
        'mst_dept.index',
        compact(
          'depts',
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
    $list['companyList'] = MstCompany::companyList();
    $list['editList'] = MstDept::editTiming();
    $list['rowList'] = MstDept::reportRow();
    $data = MstDept::getReportTableDefault();

    return view(
      'mst_dept.create',
      compact(
        'list',
        'data'
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
      'company_id' => 'required',
      'name' => 'required|string|max:50',
    ]);

    $dept = new MstDept;
    $dept->fill($request->all())->save();
    $id = $dept->id;

    return redirect()
      ->route('mst_dept.index')
      ->with([
        'message' => '部署を登録しました',
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
    $dept = MstDept::findOrFail($id);
    MstDept::getReportTableData($dept);
    $list['companyList'] = MstCompany::companyList();
    $list['editList'] = MstDept::editTiming();
    $list['rowList'] = MstDept::reportRow();

    return
      view(
        'mst_dept.edit',
        compact(
          'id',
          'dept',
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
      'company_id' => 'required',
      'name' => 'required|string|max:50',
    ]);

    $dept = MstDept::findOrFail($id);
    $dept->fill($request->all())->save();

    return redirect()
      ->route('mst_dept.index')
      ->with([
        'message' => '部署を変更しました',
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
    MstDept::findOrFail($id)->delete();

    return redirect()
      ->route('mst_dept.index')
      ->with([
        'message' => '部署を削除しました',
        'status' => 'danger'
      ]);
  }
}
