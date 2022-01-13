@extends( 'layouts.base' )

@section( 'title', '部署一覧' )

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- Main content -->
<section class="content z-depth-1">

  <!-- dept List -->
  <table class="table table-responsive">
    <thead>
      <tr>
        <th>部署名</th>
        <th>会社名</th>
        <th class="center-align" colspan="2">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($depts as $dept)
      <tr>
        <td class="row-title">{{ $dept->name }}</td>
        <td data-title="会社名 : ">{{ $dept->company->name }}</td>
        <td class="half center-align">
          {{ link_to_route('mst_dept.edit', '編集', $dept->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
        <td class="half center-align">
          {{ Form::open(['method' => 'delete', 'route' => ['mst_dept.destroy', $dept->id]]) }}
          {{ Form::button('削除', ['class' => 'wave-effect wave-light btn red lighten-1', 'onclick' => "return confirm('本当に削除しますか?')", 'type' => 'submit']) }}
          {{ Form::close() }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.dept List -->

  <div>
  {{ link_to_route('mst_dept.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
  </div>

</section>
<!-- /.content -->

@endsection
