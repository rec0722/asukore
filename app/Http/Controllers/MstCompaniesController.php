<?php

namespace App\Http\Controllers;

use App\Models\MstCompany;
use Illuminate\Http\Request;

class MstCompaniesController extends Controller
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
    $companies = MstCompany::all();

    return view(
      'mst_company.index',
      compact(
        'companies',
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
    $list['prefList'] = MstCompany::prefectureList();

    return
      view(
        'mst_company.create',
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
      'name' => 'required|string|max:50',
    ]);

    $company = new MstCompany;
    $company->fill($request->all())->save();
    $id = $company->id;

    return redirect()
      ->route(
        'mst_company.show',
        $id
      )
      ->with([
        'message' => '会社情報を完了しました',
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
    $company = MstCompany::findOrFail($id);

    return
      view(
        'mst_company.show',
        compact(
          'id',
          'company'
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
    $company = MstCompany::findOrFail($id);
    $list['prefList'] = MstCompany::prefectureList();

    return
      view(
        'mst_company.edit',
        compact(
          'id',
          'company',
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
      'name' => 'required|string|max:50',
    ]);

    $company = MstCompany::findOrFail($id);
    $company->fill($request->all())->save();

    return redirect()
      ->route(
        'mst_company.show',
        $id
      )
      ->with([
        'message' => '会社情報を完了しました',
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
    MstCompany::findOrFail($id)->delete();

    return redirect()
      ->route('mst_company.index')
      ->with([
        'message' => '会社情報を削除しました',
        'status' => 'danger'
      ]);
  }
}
