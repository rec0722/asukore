@extends( 'layouts.base' )

@section( 'title', '週報')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="weekly">
  <!-- report -->
  <div class="card">
    <div class="card-content">
      <span class="card-title">{{ $report['title'] }}</span>
      <div class="section">
        <p>報告者: {{ $report->user->name }}</p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <h2 class="report-h1"><i class="material-icons red-text accent-2">lens</i> 今週の業務</h2>
      </div>
      <table class="weekly-table">
        <thead>
          <tr>
            <th class="w10">日付</th>
            <th class="w10">曜日</th>
            <th class="w40">今週の業務</th>
            <th class="w40">報告・共有事項</th>
          </tr>
        </thead>
        <tbody>
          @for($i = 0; $i < count($actions); $i++)
          <tr>
            <td class="w10">
              {{ $actions[$i]['date'] }}
            </td>
            <td class="w10">
              {{ $actions[$i]['weekday'] }}
            </td>
            <td class="w40">
              {{ $actions[$i]['action'] }}
            </td>
            @if ($i === 0)
            <td class="w40 memo" rowspan="{{ count($actions) }}">
              {{ $report['this_week'] }}
            </td>
            @endif
            </tr>
            @endfor

        </tbody>
      </table>
      <div class="divider"></div>
      <div class="section">
        <h2 class="report-h1"><i class="material-icons grey-text lighten-1">lens</i> 来週の予定</h2>
      </div>
      <table class="weekly-table">
        <thead>
          <tr>
            <th class="w10">日付</th>
            <th class="w10">曜日</th>
            <th class="w40">来週の予定</th>
            <th class="w40">メモ事項</th>
          </tr>
        </thead>
        <tbody>
          @for($i = 0; $i < count($plans); $i++) <tr>
            <td class="w10">
              {{ $plans[$i]['date'] }}
            </td>
            <td class="w10">
              {{ $plans[$i]['weekday'] }}
            </td>
            <td class="w40">
              {{ $plans[$i]['action'] }}
            </td>
            @if ($i === 0)
            <td class="w40 memo" rowspan="{{ count($plans) }}">
              {{ $report['next_week'] }}
            </td>
            @endif
            </tr>
            @endfor

        </tbody>
      </table>
    </div>

    <div class="card-action right-align">
      @if ($report['user_id'] === Auth::user()->id)
      <a href="{{ route('weekly-report.edit', $id) }}" class="waves-effect waves-teal btn btn-floating red lighten-1">
        <i class="material-icons">edit</i>
      </a>
      @endif
    </div>
  </div>
  <div class="row">
    <div class="col s6">
      @if (!is_null($report['prev']))
      <a href="{{ route('weekly-report.show', $report->prev) }}" class="waves-effect waves-teal btn grey">
        <i class="material-icons left">chevron_left</i>前の週報
      </a>
      @endif
    </div>
    <div class="col s6 right-align">
      @if (!is_null($report['next']))
      <a href="{{ route('weekly-report.show', $report->next) }}" class="waves-effect waves-teal btn grey">
        次の週報<i class="material-icons right">chevron_right</i>
      </a>
      @endif
    </div>
  </div><!-- /.report -->
</section><!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('weekly-report.index') }}" class="waves-effect waves-teal btn-flat">週報一覧に戻る</a>
  </div>
</div>

<!-- /.content -->

@endsection
