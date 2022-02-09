@extends( 'layouts.base2' )

@section( 'title', 'ダッシュボード')

@section( 'content' )

<div class="container2">
  <section id="dashboard">

    {{ Form::open(['route' => 'report.search', 'name' => 'dashboard']) }}
      <div id="calendar"></div>
      {{ Form::hidden('search[date1]', null, ['id' => 'report_date']) }}
      {{ Form::hidden('search[date2]', null) }}
      {{ Form::hidden('search[dept]', null) }}
      {{ Form::hidden('search[employ]', null) }}
    {{ Form::close() }}

    <div class="right-align">
      <a href="{{ route('report.create') }}" class="btn-floating btn-large waves-effect waves-light red">
        作成
      </a>
    </div>
  </section>
</div>

@endsection
