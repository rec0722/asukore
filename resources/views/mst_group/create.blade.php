@extends( 'layouts.base' )

@section( 'title', 'グループ作成')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1 h-adr">

  {{ Form::open(['route' => 'mst_group.store']) }}

  <!-- group Form -->
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('name', null, ['class' => 'validate' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'required']) }}
      {{ Form::label('name', 'グループ名') }}
      <span class="helper-text">※必須項目です</span>
      @error('name')
      <span class="error invalid-feedback">{{ $message }}</span>
      @enderror
    </div>
  </div>
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('company_id', '会社選択') }}
      <div class="input-field">
        @foreach ($list['companyList'] as $key => $val)
        <p>
          <label>
            {{ Form::checkbox('company_id[]', $key, null, ['class'=>'filled-in']) }}
            <span>{{ $val }}</span>
          </label>
        </p>
        @endforeach
      </div>
    </div>
  </div>
  <!-- /.group Form -->

  <div class="row">
    <div class="col s12 center-align">
      {{ Form::button('登録する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
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
