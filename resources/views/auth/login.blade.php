@extends( 'layouts.base2' )

@section( 'title', 'ログイン画面')

@section( 'content' )

<div class="container2 flex">
  <section id="login" class="flex">
    <div class="login-inner z-depth-1">
      <h1 class="login-title">ログイン</h1>
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
        {{ Form::open(['route' => 'login']) }}
        @csrf

        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'メールアドレス', 'required', 'autofocus']) }}
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'パスワード', 'required']) }}
        {{ Form::button('ログイン', ['class' => 'waves-effect waves-light btn', 'id' => 'login-button', 'type' => 'submit']) }}

        {{ Form::close() }}
      </div><!-- /.login form -->

    </div>
  </section>
</div>

@endsection
