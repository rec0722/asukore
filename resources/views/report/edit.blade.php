@extends( 'layouts.base2' )

@section( 'title', '報告 編集画面')

@section( 'content' )

<div class="container3">
  <div class="page-title">
    <h1 class="headline1">@yield('title')</h1>
  </div>

  <!-- content -->
  <section class="content z-depth-1 report">

    {{ Form::model($report, ['route' => ['report.update', $id], 'method' => 'PATCH']) }}

    <!-- report Form -->
    <div class="row">
      <div class="input-field col s12 l6">
        <h2 class="headline1 m0">{{ $report->user->name }}</h2>
        <p>{{ optional($report['report_date'])->format('Y年m月d日') }}</p>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('todays_plan', null, ['class' => 'materialize-textarea', 'id' => 'todays_plan', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '本日の作業内容を入力してください']) }}
        {{ Form::label('todays_plan', '本日の作業内容') }}
      </div>
    </div>
    <div class="row">
      <table id="tableAction" class="report-table">
        <thead>
          <tr class="flex">
            <th class="col-12 col-md-2">時間</th>
            <th class="col-12 col-md-2">お客様名</th>
            <th class="col-12 col-md-4">作業内容</th>
            <th class="col-12 col-md-3">契約・販売・作業・打ち合わせ結果</th>
            <th class="col-12 col-md-1"></th>
          </tr>
        </thead>
        <tbody>

          @for ($i = 0; $i < count($actions); $i++)
          <tr class="flex">
            <td class="col-12 col-md-2 row id">
              {{ Form::hidden('action_list[' . $i . '][id]', $actions[$i]['id']) }}
              <div class="col s5 input1">
                {{ Form::text('action_list[' . $i . '][time1]', $actions[$i]['time1'], ['class' => 'timepicker']) }}
              </div>
              <label class="col s2">〜</label>
              <div class="col s5 input2">
                {{ Form::text('action_list[' . $i . '][time2]', $actions[$i]['time2'], ['class' => 'timepicker']) }}
              </div>
            </td>
            <td class="col-12 col-md-2 input3">
              {{ Form::text('action_list[' . $i . '][customer]', $actions[$i]['customer'], ['class' => 'validate', 'placeholder' => 'お客様']) }}
            </td>
            <td class="col-12 col-md-4 input4">
              {{ Form::textarea('action_list[' . $i . '][action]', $actions[$i]['action'], ['class' => 'materialize-textarea', 'aria-multiline' => 'true', 'placeholder' => '作業内容']) }}
            </td>
            <td class="col-12 col-md-3 input5">
              {{ Form::textarea('action_list[' . $i . '][approach]', $actions[$i]['approach'], ['class' => 'materialize-textarea', 'aria-multiline' => 'true', 'placeholder' => '契約・販売・作業・打ち合わせ結果']) }}
            </td>
            <td class="col-12 col-md-1 button center-align">
              {{ Form::button('<i class="material-icons">remove</i>', ['class' => 'waves-effect waves-light btn btn-floating deleteItem', 'id' => $i, 'type' => 'button']) }}
              {{ Form::hidden('action_list[' . $i . '][delete_flg]', 0, ['id' => 'flg' . $i]) }}
            </td>
            </tr>
            @endfor

        </tbody>
        <tfoot>
          <tr>
            <td class="right-align" colspan="5">
              {{ Form::button('<i class="material-icons">add</i>', ['class' => 'waves-effect waves-light btn btn-floating addItem', 'type' => 'button']) }}
              {{ Form::hidden('itemNum', $i, ['id' => 'getItemNum']) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('tomorrow_plan', null, ['class' => 'materialize-textarea', 'id' => 'tomorrow_plan', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '明日の予定や今日完了できなかった仕事を書いてください']) }}
        {{ Form::label('tomorrow_plan', '明日の予定') }}
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('notices', null, ['class' => 'materialize-textarea', 'id' => 'notices', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '上記の報告以外で気になる点や覚えた内容があれば書いてください']) }}
        {{ Form::label('notices', '特記事項') }}
      </div>
    </div><!-- /.report Form -->

    <div class="row">
      <div class="col s12 center-align">
        {{ Form::button('更新する', ['class' => 'waves-effect waves-light btn', 'onClick' => 'submit();']) }}
      </div>
    </div><!-- /.report Form -->

    {{ Form::close() }}
  </section><!-- /.content -->

  <div class="row">
    <div class="col s12 center-align">
      <a href="{{ route('report.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
    </div>
  </div>
</div>

@endsection
