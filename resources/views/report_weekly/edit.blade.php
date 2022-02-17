@extends( 'layouts.base2' )

@section( 'title', '週報 編集画面')

@section( 'content' )

<div class="container3">
  <div class="page-title">
    <h1 class="headline1">@yield('title')</h1>
  </div>

  <!-- content -->
  <section class="content z-depth-1 weekly">

    {{ Form::model($report, ['route' => ['weekly-report.update', $id], 'method' => 'PATCH']) }}

    <!-- report Form -->
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

          @for($i = 0; $i < count($report['action']); $i++) <tr>
            {{ Form::hidden('action_list[' . $i . '][id]', $report['action'][$i]['id']) }}
            <td class="w10">
              {{ Form::text('action_list[' . $i . '][date]', $report['action'][$i]['date'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10">
              {{ Form::text('action_list[' . $i . '][weekday]', $report['action'][$i]['weekday'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10 input-field">
              {{ Form::select('action_list[' . $i . '][work_flg]',  $list['situationList'], $report['action'][$i]['work_flg']) }}
            </td>
            <td class="w70">
              {{ Form::textarea('action_list[' . $i . '][action]', $report['action'][$i]['action'], ['class' => 'materialize-textarea', 'rows' => '1', 'aria-multiline' => 'true', 'placeholder' => '']) }}
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

          @for($i = 0; $i < count($report['plan']); $i++) <tr>
            {{ Form::hidden('action_plan[' . $i . '][id]', $report['plan'][$i]['id']) }}
            <td class="w10">
              {{ Form::text('action_plan[' . $i . '][date]', $report['plan'][$i]['date'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10">
              {{ Form::text('action_plan[' . $i . '][weekday]', $report['plan'][$i]['weekday'], ['class' => 'validate', 'readonly']) }}
            </td>
            <td class="w10 input-field">
              {{ Form::select('action_plan[' . $i . '][work_flg]', $list['situationList'], $report['plan'][$i]['work_flg']) }}
            </td>
            <td class="w70">
              {{ Form::textarea('action_plan[' . $i . '][action]', $report['plan'][$i]['action'], ['class' => 'materialize-textarea', 'rows' => '1', 'aria-multiline' => 'true', 'placeholder' => '']) }}
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
        {{ Form::button('更新する', ['class' => 'waves-effect waves-light btn', 'onClick' => 'submit();']) }}
      </div>
    </div><!-- /.report Form -->

    {{ Form::close() }}
  </section><!-- /.content -->

  <div class="row">
    <div class="col s12 center-align">
      <a href="{{ route('weekly-report.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
    </div>
  </div>
</div><!-- /.container -->
  @endsection
