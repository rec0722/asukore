@extends( 'layouts.base' )

@section( 'title', '会社一覧' )

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
        <th>会社名</th>
        <th>住所</th>
        <th class="center-align" colspan="2">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($companies as $com)
      <tr>
        <td>{{ $com->name }}</td>
        <td>{{ $com->prefecture->name }}{{ $com->city }}</td>
        <td class="center-align">
          {{ link_to_route('mst_company.show', '詳細', $com->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
        <td class="center-align">
          {{ Form::open(['method' => 'delete', 'route' => ['mst_company.destroy', $com->id]]) }}
          {{ Form::button('削除', ['class' => 'wave-effect wave-light btn red lighten-1', 'onclick' => "return confirm('本当に削除しますか?')", 'type' => 'submit']) }}
          {{ Form::close() }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Company List -->

  <div>
  {{ link_to_route('mst_company.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
  </div>

</section>
<!-- /.content -->

@endsection
