@extends( 'layouts.base' )

@section( 'title', 'ユーザ一覧' )

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- Main content -->
<section class="content z-depth-1">

  <!-- Company List -->
  <table class="table">
    <thead>
      <tr>
        <th>氏名</th>
        <th>会社名</th>
        <th>所属</th>
        <th>役職</th>
        <th class="center-align" colspan="2">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->company->name }}</td>
        <td>{{ $user->dept->name }}</td>
        <td>{{ $user->role }}</td>
        <td class="center-align">
          {{ link_to_route('mst_user.show', '詳細', $user->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
        <td class="center-align">
          {{ Form::open(['method' => 'delete', 'route' => ['mst_user.destroy', $user->id]]) }}
          {{ Form::button('削除', ['class' => 'wave-effect wave-light btn red lighten-1', 'onclick' => "return confirm('本当に削除しますか?')", 'type' => 'submit']) }}
          {{ Form::close() }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Company List -->

  <div>
  {{ link_to_route('mst_user.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
  </div>

</section>
<!-- /.content -->

@endsection
