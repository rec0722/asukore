@extends( 'layouts.base' )

@section( 'title', '本日の報告')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="report">
  <!-- report -->
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <span class="card-title">{{ $report->user->name }}</span>
          <div class="section">
            <p>{{ $report['report_date']->format('Y年m月d日') }}</p>
          </div>
          <div class="divider"></div>
          <div class="section">
            <h2 class="headline2">今日の作業内容</h2>
            <p>{{ $report->todays_plan }}</p>
          </div>
          <div class="divider"></div>
          <table class="report-table">
            <tbody>

              @foreach($actions as $act)
              <tr class="flex">
                <td class="col-6 col-md-2">{{ $act->time }}</td>
                <td class="col-6 col-md-2">{{ $act->customer }}</td>
                <td class="col-12 col-md-4">{{ $act->action }}</td>
                <td class="col-12 col-md-4">{{ $act->approach }}</td>
              </tr>
              @endforeach

            </tbody>
          </table>
          <div class="section">
            <h2 class="headline2">明日の予定</h2>
            <p>{{ $report->tomorrow_plan }}</p>
          </div>
          <div class="divider"></div>
          <div class="section">
            <h2 class="headline2">特記事項</h2>
            <p>{{ $report->notices }}</p>
          </div>
        </div>

        <div class="card-action right-align">
          @if ($report['user_id'] === Auth::user()->id && $report['report_date']->format('Y-m-d') === date('Y-m-d'))
          <a href="{{ route('report.edit', $id) }}" class="waves-effect waves-teal btn btn-floating red lighten-1">
            <i class="material-icons">edit</i>
          </a>
          @endif
        </div>
      </div>
      <div>
        <div class="col s6">
          @if (!is_null($report['prev']))
          <a href="{{ route('report.show', $report->prev) }}" class="waves-effect waves-teal btn-flat">
            <i class="material-icons">chevron_left</i>前の日
          </a>
          @endif
        </div>
        <div class="col s6 right-align">
          @if (!is_null($report['next']))
          <a href="{{ route('report.show', $report->next) }}" class="waves-effect waves-teal btn-flat">
            次の日<i class="material-icons">chevron_right</i>
          </a>
          @endif
        </div>
      </div>
    </div>
  </div><!-- /.report -->
</section><!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('report.index') }}" class="waves-effect waves-teal btn-flat">日報一覧に戻る</a>
  </div>
</div>

<!-- /.content -->

@endsection
