@extends( 'layouts.base' )

@section( 'title', '部署 編集')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1 report">

  {{ Form::model($dept, ['route' => ['mst_dept.update', $id], 'method' => 'PATCH']) }}

  <!-- dept Form -->
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('company_id', '会社選択') }}
      {{ Form::select('company_id', $list['companyList'], null, ['class' => 'browser-default', 'required']) }}
      <div class="input-field">
        <span class="helper-text">※必須項目です</span>
        @error('company_id')
        <span class="helper-text">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('name', null, ['class' => 'validate' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'required']) }}
      {{ Form::label('name', '部署名') }}
      <span class="helper-text">※必須項目です</span>
      @error('name')
      <span class="helper-text">{{ $message }}</span>
      @enderror
    </div>
  </div>
  <div class="divider"></div>
  <h2 class="headline1">日報設定</h2>
  <div class="row">
    {{ Form::label('edit_time', '過去の編集期間') }}
    {{ Form::select('edit_time',  $list['editList'], null, ['class' => 'browser-default']) }}
    <div class="input-field col s12 l6">
      <span class="helper-text">※日報を編集できる期間を設定してください</span>
    </div>
  </div>
  <div class="row">
    {{ Form::label('report_table', '時間制タイトル') }}
    <table class="report-table">
      <tbody>
        <tr class="flex">
          <th class="col-12 col-md-2">
            {{ Form::text('report_text1', null, ['class' => 'validate']) }}
          </th>
          <th class="col-12 col-md-2">
            {{ Form::text('report_text2', null, ['class' => 'validate']) }}
          </th>
          <th class="col-12 col-md-4">
            {{ Form::text('report_text3', null, ['class' => 'validate']) }}
          </th>
          <th class="col-12 col-md-4">
            {{ Form::text('report_text4', null, ['class' => 'validate']) }}
          </th>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="row">
    {{ Form::label('report_num', '報告行数') }}
    {{ Form::select('report_num',  $list['rowList'], null, ['class' => 'browser-default']) }}
    <div class="input-field col s12 l6">
      <span class="helper-text">※デフォルトで表示する時間制の報告行数を設定してください</span>
    </div>
  </div>
  <!-- /.dept Form -->

  <div class="row">
    <div class="col s12 center-align">
      {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
    </div>
  </div>

  {{ Form::close() }}

</section><!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_dept.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
