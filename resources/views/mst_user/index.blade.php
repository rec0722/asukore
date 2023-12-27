@extends( 'layouts.base' )

@section( 'title', 'ユーザ一覧' )

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
        <th>役職</th>
        <th class="center-align" colspan="2">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($users as $user)
      <tr>
        <td class="row-title">{{ $user->name }}</td>
        <td data-title="会社名 : ">{{ $user->company->name }}</td>
        <td data-title="所属 : ">{{ $user->dept->name }}</td>
        <td data-title="役職 : ">{{ $user->role }}</td>
        <td class="half center-align">
          {{ link_to_route('mst_user.show', '詳細', $user->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
        <td class="half center-align">
          {{ Form::open(['method' => 'delete', 'route' => ['mst_user.destroy', $user->id]]) }}
          {{ Form::button('削除', ['class' => 'wave-effect wave-light btn red lighten-1', 'onclick' => "return confirm('本当に削除しますか?')", 'type' => 'submit']) }}
          {{ Form::close() }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Company List -->

  {{ $users->links('components/pagination') }}

  @if ($authUser['role'] > 7)
  <div class="row">
    <div class="col s6">
      {{ link_to_route('mst_user.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
    </div>
    <div class="col s6 right-align">
      {{ link_to_route('mst_user.trash', ' ゴミ箱 ', null, ['class' => 'wave-effect wave-light btn grey']) }}
    </div>
  </div>
  @endif

</section>
<!-- /.content -->

@endsection
