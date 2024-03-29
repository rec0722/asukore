@extends( 'layouts.base' )

@section( 'title', 'グループ 編集')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1">

  {{ Form::model($group, ['route' => ['mst_group.update', $id], 'method' => 'PATCH']) }}

  <!-- group Form -->
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('name', null, ['class' => 'validate' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'required']) }}
      {{ Form::label('name', 'グループ名') }}
      <span class="helper-text">※必須項目です</span>
      @error('name')
      <span class="helper-text">{{ $message }}</span>
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
            @if (array_search($key, array_column($group['companyList'], 'company_id'), true) === false)
            {{ Form::checkbox('company_id[]', $key, false, ['class'=>'filled-in']) }}
            @else
            {{ Form::checkbox('company_id[]', $key, true, ['class'=>'filled-in']) }}
            @endif
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
      {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
    </div>
  </div>

  {{ Form::close() }}

</section><!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_group.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
