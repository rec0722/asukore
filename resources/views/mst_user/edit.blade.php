@extends( 'layouts.base' )

@section( 'title', 'ユーザ 編集画面')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1 h-adr">

  {{ Form::model($user, ['route' => ['mst_user.update', $id], 'method' => 'PATCH']) }}

  <!-- user Form -->
  <h2 class="headline1">ユーザ情報</h2>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('name', null, ['class' => 'validate' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'required']) }}
      {{ Form::label('name', '氏名') }}
      <span class="helper-text">※必須項目です</span>
      @error('name')
      <span class="helper-text red-text lighten-1">{{ $message }}</span>
      @enderror
    </div>
  </div>
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('company_id', '会社名') }}
      {{ Form::select('company_id', $user['companyList'], null, ['class' => 'browser-default', 'id' => 'select-company', 'required']) }}
      <div class="input-field">
        <span class="helper-text">※必須項目です</span>
        @error('company_id')
        <span class="helper-text red-text lighten-1">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('dept_id', '所属') }}
      {{ Form::select('dept_id', $user['deptSelect'], null, ['class' => 'browser-default', 'id' => 'change-dept']) }}
      <div class="input-field">
        <span class="helper-text">※必須項目です</span>
        @error('dept_id')
        <span class="helper-text red-text lighten-1">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <!-- 管理者のみ表示 -->
  @if ($user['role'] > 7)
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('role', '役職') }}
      <div class="input-field">
        @foreach ($user['roleList'] as $key => $val)
        <p>
          <label>
            {{ Form::radio('role', $key, ['class' => 'with-gap', 'id' => 'role' . $key]) }}
            <span>{{ $val }}</span>
          </label>
        </p>
        @endforeach
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('dept', '閲覧権限') }}
      <div class="input-field">
        @foreach ($user['deptCheck'] as $key => $val)
        <p>
          <label>
            @if (array_search($key, array_column($userDept, 'dept_id'), true) === false)
            {{ Form::checkbox('dept[]', $key, false, ['class'=>'filled-in']) }}
            @else
            {{ Form::checkbox('dept[]', $key, true, ['class'=>'filled-in']) }}
            @endif
            <span>{{ $val }}</span>
          </label>
        </p>
        @endforeach
      </div>
    </div>
  </div>
  @else
    {{ Form::hidden('role', null) }}
  @endif
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('input_type', '入力設定') }}
      <div class="input-field">
        <p>
          <label>
            {{ Form::checkbox('input_free', 1, $user['input_free'], ['class'=>'filled-in mstType']) }}
            <span>フリー入力</span>
          </label>
        </p>
        <p>
          <label>
            {{ Form::checkbox('input_time', 1, $user['input_time'], ['class'=>'filled-in mstType']) }}
            <span>時間制入力</span>
          </label>
        </p>
        <p>
          <label>
            {{ Form::checkbox('input_pic', 1, $user['input_pic'], ['class'=>'filled-in mstType']) }}
            <span>画像報告</span>
          </label>
        </p>
      </div>
    </div>
  </div>

  <div class="divider"></div>
  <h2 class="headline1">ログイン情報</h2>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::email('email', null, ['class' => 'validate', 'id' => 'email', 'required']) }}
      {{ Form::label('email', 'メールアドレス') }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::password('password', null, ['class' => 'validate', 'id' => 'password']) }}
      {{ Form::label('password', 'パスワード') }}
      <span class="helper-text">※変更する場合のみ入力（8文字以上の半角英数字で入力してください）</span>
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::password('password_confirmation', null, ['class' => 'validate', 'id' => 'password_confirmation']) }}
      {{ Form::label('password_confirmation', 'パスワード（確認）') }}
      <span class="helper-text">※変更する場合のみ入力（8文字以上の半角英数字で入力してください）</span>
      @error('password')
      <span class="helper-text red-text lighten-1">{{ $message }}</span>
      @enderror
    </div>
  </div>
  <!-- /.user Form -->

  <div class="row">
    <div class="col s12 center-align">
      {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
    </div>
  </div>

  {{ Form::close() }}
</section><!-- /.content -->
<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_user.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
