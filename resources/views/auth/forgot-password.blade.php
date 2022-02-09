@extends( 'layouts.base2' )

@section( 'title', 'パスワードリセットメール')

@section( 'content' )

<div class="container2 flex">
  <section id="login" class="flex">
    <div class="login-inner z-depth-1">
      <h1 class="login-title">パスワードをリセットする</h1>
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
        {{ Form::open(['route' => 'password.email']) }}
        @csrf

        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'メールアドレス', 'required', 'autofocus']) }}
        {{ Form::button('パスワードリセットするリンクを送る', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}

        {{ Form::close() }}
      </div><!-- /.login form -->

    </div>
  </section>
</div>

@endsection
