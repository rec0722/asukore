@extends( 'layouts.base' )

@section( 'title', '日報一覧' )

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- Main content -->
<section class="content z-depth-1">

  <!-- search Box -->
  {{ Form::open(['route' => 'report.search']) }}
  <div class="search-box flex">
    <div class="col-3">
      {{ Form::text('search[date]', $item['date'], ['class' => 'datepicker']) }}
    </div>
    <div class="col-3">
      @if ($user['role'] === 8)
      {{ Form::select('search[dept]', $search['deptSelect'], $item['dept'], ['class' => 'browser-default']) }}
      @else
      {{ Form::hidden('search[dept]', null) }}
      @endif
    </div>
    <div class="col-3">
      @if ($user['role'] === 8)
      {{ Form::select('search[employ]', $search['userSelect'], $item['employ'], ['class' => 'browser-default']) }}
      @else
      {{ Form::hidden('search[employ]', null) }}
      @endif
    </div>
    <div class="col-1">
      {{ Form::button('検 索', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
    </div>
  </div>
  {{ Form::close() }}
  <!-- /.search Box -->

  <!-- Report List -->
  <table class="table">
    <thead>
      <tr class="flex">
        <th class="col-3">氏名</th>
        <th class="col-3">所属</th>
        <th class="col-4">報告日</th>
        <th class="col-2 center-align">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($reports as $report)
      <tr class="flex">
        <td class="col-3">{{ $report->user->name }}</td>
        <td class="col-3">{{ $report->dept->name }}</td>
        <td class="col-4">{{ $report['report_date']->format('Y年m月d日') }}</td>
        <td class="col-2 center-align">
          {{ link_to_route('report.show', '詳細', $report->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Report List -->

  <div>
    {{ link_to_route('report.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
  </div>

</section>
<!-- /.content -->

@endsection
