@extends( 'layouts.base2' )

@section( 'title', 'パスワード再設定')

@section( 'content' )

<div class="container2 flex">
  <section id="login" class="flex">
    <div class="login-inner z-depth-1">
      <h1 class="login-title">パスワード再設定</h1>
      <div class="login-error">
        @if ($errors->any())
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
        @endif
      </div>

      <!-- login form -->
      <div class="login-form">
        {{ Form::open(['route' => 'password.update']) }}
        @csrf
        {{ Form::hidden('token', request()->token) }}

        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'メールアドレス', 'required']) }}
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => '新しいパスワード', 'required']) }}
        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '新しいパスワード（確認）', 'required']) }}
        {{ Form::button('パスワードを再設定する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}

        {{ Form::close() }}
      </div><!-- /.login form -->

    </div>
  </section>
</div>

@endsection
