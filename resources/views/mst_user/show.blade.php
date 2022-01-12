@extends( 'layouts.base' )

@section( 'title', 'ユーザ情報')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content Box -->
<section class="content-box flex">
  <!-- user Information -->
  <div class="card">
    <div class="card-content">
      <span class="card-title">{{ $user->name }}</span>
      <div class="section">
        <p>{{ $user->company->name }}</p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <p>{{ $user->dept->name }} {{ $user->role }}</p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <p><span>email: </span>{{ $user->email }}</p>
      </div>
      <div class="divider"></div>
    </div>

    <div class="card-action right-align">
      <a href="{{ route('mst_user.edit', $id) }}" class="waves-effect waves-teal btn btn-floating red lighten-1">
        <i class="material-icons">edit</i>
      </a>
    </div>
  </div><!-- /.user Information -->
</section><!-- /.content Box -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_user.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

<!-- /.content -->

@endsection
