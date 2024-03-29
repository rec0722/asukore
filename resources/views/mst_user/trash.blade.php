@extends( 'layouts.base' )

@section( 'title', 'ゴミ箱' )

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- Main content -->
<section class="content z-depth-1">

  <!-- Company List -->
  <table class="table table-responsive">
    <thead>
      <tr>
        <th>氏名</th>
        <th>会社名</th>
        <th>所属</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

      @foreach ($users as $user)
      <tr>
        <td class="row-title">{{ $user->name }}</td>
        <td data-title="会社名 : ">{{ $user->company->name }}</td>
        <td data-title="所属 : ">{{ $user->dept->name }}</td>
        <td class="center-align">
          {{ Form::open(['route' => ['mst_user.restore', $user->id], 'method' => 'PATCH']) }}
          {{ Form::button('復元する', ['class' => 'wave-effect wave-light btn lighten-1', 'type' => 'submit']) }}
          {{ Form::close() }}
        </td>

      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Company List -->

  <div class="row">
    <div class="col s12 center-align">
      {{ Form::open(['route' => 'mst_user.force_delete', 'method' => 'DELETE']) }}
      {{ Form::button('ゴミ箱を空にする', ['class' => 'waves-effect waves-light btn grey', 'onclick' => "return confirm('完全に削除しますか?')", 'type' => 'submit']) }}
      {{ Form::close() }}
    </div>
  </div>

</section>
<!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_user.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
