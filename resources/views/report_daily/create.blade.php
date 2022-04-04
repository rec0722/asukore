@extends( 'layouts.base2' )

@section( 'title', '日報作成')

@section( 'content' )

<div class="container3">
  <div class="page-title">
    <h1 class="report-h1">@yield('title')</h1>
  </div>

  <!-- content -->
  <section class="content z-depth-1 report">

    {{ Form::open(['route' => 'report.store', 'files' => true]) }}

    <!-- report Form -->
    <div class="row">
      <div class="col s12 l6">
        {{ Form::label('report_date', '報告日') }}
        {{ Form::select('report_date', $item['dateList'], null, ['class' => 'browser-default']) }}
        @error('report_date')
        <span class="helper-text red-text lighten-1">{{ $message }}</span>
        @enderror
      </div>
      <div class="input-field col s12 l6">
        {{ Form::text('user_id', $user->name, ['class' => 'validate', 'id' => 'user_id', 'disabled']) }}
        {{ Form::label('user_id', '報告者') }}
      </div>
    </div>
    <h2 class="report-h1"><i class="material-icons red-text accent-2">lens</i> 今日の作業内容</h2>
    <div class="report-type-box">
      <div class="row input-field">
        <div class="col s12 l3">
          <label>
            {{ Form::checkbox('input_free', 1, $item['input_free'], ['class'=>'filled-in inputType']) }}
            <span>フリー入力</span>
          </label>
        </div>
        <div class="col s12 l3">
          <label>
            {{ Form::checkbox('input_time', 1, $item['input_time'], ['class'=>'filled-in inputType']) }}
            <span>時間制入力</span>
          </label>
        </div>
        <div class="col s12 l3">
          <label>
            {{ Form::checkbox('input_pic', 1, $item['input_pic'], ['class'=>'filled-in inputType']) }}
            <span>画像で報告</span>
          </label>
        </div>
      </div>
      <p class="small grey-text">※表示のデフォルト設定は<a href="{{ route('mst_user.edit', $user->id) }}" class="underline">プロフィール</a>から変更できます</p>
    </div>
    <div class="row {{ $item['free'] }}" id="free-box">
      <div class="input-field col s12">
        {{ Form::textarea('todays_plan', null, ['class' => 'materialize-textarea', 'id' => 'todays_plan', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '作業内容を入力してください']) }}
        <label for="todays_plan" class="red-text text-lighten-3"><i class="material-icons">create</i> フリー入力</label>
      </div>
    </div>
    <div class="row {{ $item['time'] }}" id="time-box">
      <label for="todays_plan" class="red-text text-lighten-3"><i class="material-icons">create</i> 時間制入力</label>
      <table id="tableAction" class="report-table">
        <thead>
          <tr class="flex">
            <th class="col-12 col-md-2">{{ $item['text1'] }}</th>
            <th class="col-12 col-md-2">{{ $item['text2'] }}</th>
            <th class="col-12 col-md-4">{{ $item['text3'] }}</th>
            <th class="col-12 col-md-3">{{ $item['text4'] }}</th>
            <th class="col-12 col-md-1"></th>
          </tr>
        </thead>
        <tbody>

          @for ($i = 0; $i < $item['rows']; $i++) <tr class="flex">
            <td class="col-12 col-md-2 row id">
              {{ Form::hidden('action_list[' . $i . '][id]', null) }}
              <span class="col s5 input1">
                @if ($item['agent']->isMobile())
                {{ Form::text('action_list[' . $i . '][time1]', null, ['class' => 'js-time-picker', 'id' => 'time1_' . $i]) }}
                @else
                {{ Form::time('action_list[' . $i . '][time1]', null, ['class' => '', 'id' => 'time1_' . $i]) }}
                @endif
              </span>
              <label class="col s2">〜</label>
              <span class="col s5 input2">
                @if ($item['agent']->isMobile())
                {{ Form::text('action_list[' . $i . '][time2]', null, ['class' => 'js-time-picker', 'id' => 'time2_' . $i]) }}
                @else
                {{ Form::time('action_list[' . $i . '][time2]', null, ['class' => '', 'id' => 'time2_' . $i]) }}
                @endif
              </span>
            </td>
            <td class="col-12 col-md-2 input3">
              {{ Form::text('action_list[' . $i . '][customer]', null, ['class' => 'validate', 'placeholder' => $item['text2']]) }}
            </td>
            <td class="col-12 col-md-4 input4">
              {{ Form::textarea('action_list[' . $i . '][action]', null, ['class' => 'materialize-textarea', 'aria-multiline' => 'true', 'placeholder' => $item['text3']]) }}
            </td>
            <td class="col-12 col-md-3 input5">
              {{ Form::textarea('action_list[' . $i . '][approach]', null, ['class' => 'materialize-textarea', 'aria-multiline' => 'true', 'placeholder' => $item['text4']]) }}
            </td>
            <td class="col-12 col-md-1 button right-align">
              {{ Form::button('<i class="material-icons">clear</i>', ['class' => 'deleteItem', 'id' => $i, 'type' => 'button']) }}
              {{ Form::hidden('action_list[' . $i . '][delete_flg]', 0, ['id' => 'flg0']) }}
            </td>
            </tr>
            @endfor

        </tbody>
        <tfoot>
          <tr>
            <td class="center-align" colspan="5">
              {{ Form::button('<i class="material-icons">add</i>', ['class' => 'waves-effect waves-light btn btn-floating addItem', 'type' => 'button']) }}
              {{ Form::hidden('itemNum', $i, ['id' => 'getItemNum']) }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="row {{ $item['pic'] }}" id="pic-box">
      <label for="todays_plan" class="red-text text-lighten-3"><i class="material-icons">create</i> 画像報告</label>
      <div class="file-field input-field">
        <div class="btn">
          <span>ファイル</span>
          <input type="file" name="todays_image">
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text" placeholder="画像をアップロード">
        </div>
      </div>
    </div>
    <h2 class="report-h1"><i class="material-icons grey-text lighten-1">lens</i> 明日の予定</h2>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('tomorrow_plan', null, ['class' => 'materialize-textarea', 'id' => 'tomorrow_plan', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '']) }}
        <label for="tomorrow_plan" class="red-text text-lighten-3"><i class="material-icons">create</i> 明日の予定や今日未完了の仕事を書いてください</label>
      </div>
    </div>
    <h2 class="report-h1"><i class="material-icons amber-text lighten-1">lens</i> 特記事項</h2>
    <div class="row">
      <div class="input-field col s12">
        {{ Form::textarea('notices', null, ['class' => 'materialize-textarea', 'id' => 'notices', 'rows' => '4', 'aria-multiline' => 'true', 'placeholder' => '']) }}
        <label for="notices" class="red-text text-lighten-3"><i class="material-icons">create</i> 気になる点や覚えた内容があれば書いてください</label>
      </div>
    </div><!-- /.report Form -->

    <div class="row">
      <div class="col s12 center-align">
        {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
      </div>
    </div>

    {{ Form::close() }}

  </section><!-- /.content -->
</div>

@endsection