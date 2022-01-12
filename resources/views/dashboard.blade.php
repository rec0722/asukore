@extends( 'layouts.base2' )

@section( 'title', 'ダッシュボード')

@section( 'content' )

<section id="dashboard">
  <div id="calendar"></div>
  <div class="right-align">
    <a href="{{ route('report.create') }}" class="btn-floating btn-large waves-effect waves-light red">
      <i class="material-icons">add</i>
    </a>
  </div>
</section>

@endsection
