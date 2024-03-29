@extends( 'layouts.base' )

@section( 'title', '日報一覧' )

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- Main content -->
<section class="content z-depth-1">

  <!-- search Box -->
  {{ Form::open(['route' => 'report.search', 'id' => 'reportForm']) }}
  <div class="search-box flex">
    <div class="col-12 col-md-4">
      {{ Form::label('search', '日付検索') }}
      <div class="row">
        <div class="col s5">
          {{ Form::text('search[date1]', $item['date1'], ['class' => 'datepicker']) }}
        </div>
        <span class="col s1 center-align">〜</span>
        <div class="col s5">
          {{ Form::text('search[date2]', $item['date2'], ['class' => 'datepicker']) }}
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      @if ($user['role'] >= 8)
      {{ Form::label('search', '部署検索') }}
      {{ Form::select('search[dept]', $search['deptSelect'], $item['dept'], ['class' => 'browser-default']) }}
      @else
      {{ Form::hidden('search[dept]', null) }}
      @endif
    </div>
    <div class="col-12 col-md-3">
      @if ($user['role'] >= 4)
      {{ Form::label('search', '社員検索') }}
      {{ Form::select('search[employ]', $search['userSelect'], $item['employ'], ['class' => 'browser-default']) }}
      @else
      {{ Form::hidden('search[employ]', null) }}
      @endif
    </div>
    <div class="col-12 col-md-2 search-btn">
      {{ Form::button('検 索', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
      {{ Form::button('クリア', ['class' => 'waves-effect waves-light btn grey resetBtn', 'type' => 'clear']) }}
    </div>
  </div>
  {{ Form::close() }}
  <!-- /.search Box -->

  <!-- Report List -->
  <table class="table table-responsive">
    <thead>
      <tr>
        <th>氏名</th>
        <th>所属</th>
        <th>報告日</th>
        <th class="center-align">操作</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($reports as $report)
      <tr>
        <td class="row-title">{{ $report->user->name }}</td>
        <td data-title="所属 : ">{{ $report->dept->name }}</td>
        <td data-title="報告日 : ">{{ $report['report_date']->format('Y年m月d日') }}</td>
        <td class="row-btn">
          {{ link_to_route('report.show', '詳細', $report->id, ['class' => 'wave-effect wave-light btn']) }}
        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <!-- /.Report List -->

  {{ $reports->links('components/pagination') }}

  <div>
    {{ link_to_route('report.create', ' 新規追加 ', null, ['class' => 'wave-effect wave-light btn']) }}
  </div>

</section>
<!-- /.content -->

@endsection
