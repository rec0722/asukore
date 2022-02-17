@extends( 'layouts.base2' )

@section( 'title', '週報作成')

@section( 'content' )

<div class="container3">
  <div class="page-title">
    <h1 class="report-h1">@yield('title')</h1>
  </div>

  @if (is_null($list['date1']) && is_null($list['date2']))
  <!-- content -->
  <section class="content z-depth-1 weekly select-date">
    {{ Form::open(['route' => 'weekly-report.create', 'method' => 'GET']) }}
    <h2 class="report-h1 center-align">期間を選択してください</h2>
    <div class="report-type-box">
      <div class="row">
        <div class="input-field col l6 s12">
          {{ Form::text('date1', null, ['class' => 'validate datepicker', 'id' => 'date1']) }}
        {{ Form::label('date1', '開始日') }}
        </div>
        <div class="input-field col l6 s12">
          {{ Form::text('date2', null, ['class' => 'validate datepicker', 'id' => 'date2']) }}
        {{ Form::label('date2', '終了日') }}
        </div>
      </div>
      <div class="row">
        <div class="col s12 center-align search-btn">
          {{ Form::button('データ読込', ['class' => 'waves-effect waves-light btn weeklyBtn', 'type' => 'submit']) }}
        </div>
      </div>
    </div>
    {{ Form::close() }}
  </section>
  @endif

  @if (!is_null($list['date1']) && !is_null($list['date2']))
  <section class="content z-depth-1 weekly">
    {{ Form::open(['route' => 'weekly-report.store']) }}
    <!-- report Form -->
    <div class="row">
      <div class="input-field col s12 l6">
        {{ Form::text('title', null, ['class' => 'validate', 'id' => 'title']) }}
        {{ Form::label('title', 'タイトル') }}
      </div>
      <div class="input-field col s12 l6">
        {{ Form::text('user_id', $user->name, ['class' => 'validate', 'disabled']) }}
        {{ Form::label('user_id', '報告者') }}
      </div>
      {{ Form::hidden('report_date1', $list['date1']) }}
      {{ Form::hidden('report_date2', $list['date2']) }}
    </div>
    <div class="row">
      <h2 class="report-h1"><i class="material-icons red-text accent-2">lens</i> 今日の業務</h2>
      <table id="thisWeek" class="weekly-table">
        <thead>
          <tr>
            <th class="w10">日付</th>
            <th class="w10">曜日</th>
            <th class="w10">出勤状況</th>
            <th class="w70">今週の業務</th>
          </tr>
        </thead>
        <tbody>

          @for($i = 0; $i < count($data); $i++) <tr>
            <td class="w10">
              {{ Form::text('action_list[' . $i . '][date]', $data[$i]['date'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10">
              {{ Form::text('action_list[' . $i . '][weekday]', $data[$i]['weekday'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10 input-field">
              {{ Form::select('action_list[' . $i . '][work_flg]',  $list['situationList'], null) }}
            </td>
            <td class="w70">
              {{ Form::textarea('action_list[' . $i . '][action]', $data[$i]['action'], ['class' => 'materialize-textarea', 'rows' => '1', 'aria-multiline' => 'true', 'placeholder' => '']) }}
            </td>
            </tr>
            @endfor

        </tbody>
      </table>
    </div>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('this_week', null, ['class' => 'materialize-textarea', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '']) }}
        <label for="this_week" class="red-text text-lighten-3"><i class="material-icons">create</i> 報告・共有事項</label>
      </div>
    </div>
    <div class="divider"></div>
    <div class="row">
      <h2 class="report-h1"><i class="material-icons grey-text lighten-1">lens</i> 来週の予定</h2>
      <table id="nextWeek" class="weekly-table">
        <thead>
          <tr>
            <th class="w10">日付</th>
            <th class="w10">曜日</th>
            <th class="w10">出勤状況</th>
            <th class="w70">来週の予定</th>
          </tr>
        </thead>
        <tbody>

          @for($i = 0; $i < count($plan); $i++) <tr>
            <td class="w10">
              {{ Form::text('action_plan[' . $i . '][date]', $plan[$i]['date'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10">
              {{ Form::text('action_plan[' . $i . '][weekday]', $plan[$i]['weekday'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10 input-field">
              {{ Form::select('action_plan[' . $i . '][work_flg]', $list['situationList'], null) }}
            </td>
            <td class="w70">
              {{ Form::textarea('action_plan[' . $i . '][action]', null, ['class' => 'materialize-textarea', 'rows' => '1', 'aria-multiline' => 'true', 'placeholder' => '']) }}
            </td>
            </tr>
            @endfor

        </tbody>
      </table>
    </div>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('next_week', null, ['class' => 'materialize-textarea', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '']) }}
        <label for="next_week" class="red-text text-lighten-3"><i class="material-icons">create</i> 来週のメモ</label>
      </div>
    </div>
    <div class="row">
      <div class="col s12 center-align">
        {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
      </div>
    </div>
    {{ Form::close() }}
  </section><!-- /.content -->
  @endif

  <div class="row">
    <div class="col s12 center-align">
      <a href="{{ route('weekly-report.create') }}" class="waves-effect waves-teal btn-flat">日付選択に戻る</a>
    </div>
  </div>
</div><!-- /.container -->

@endsection
