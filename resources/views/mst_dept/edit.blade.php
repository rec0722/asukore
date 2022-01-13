@extends( 'layouts.base' )

@section( 'title', '部署 編集')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1 h-adr">

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
  <div class="row">
    {{ Form::label('report_num', '報告行数') }}
    {{ Form::select('report_num',  $list['rowList'], null, ['class' => 'browser-default']) }}
    <div class="input-field col s12 l6">
      <span class="helper-text">※時間単位の報告の行数を設定してください</span>
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
