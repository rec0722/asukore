@extends( 'layouts.base' )

@section( 'title', '会社情報')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content Box -->
<section class="content-box flex">
  <!-- company Information -->
  <div class="card">
    <div class="card-content">
      <span class="card-title">{{ $company->name }}</span>
      <div class="section">
        <p>{{ $company->zipcode }}<br>
          {{ $company->prefecture->name }}{{ $company->city }}{{ $company->address }}
        </p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <p><span>tel: </span>{{ $company->tel }}</p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <p><span>fax: </span>{{ $company->fax }}</p>
      </div>
      <div class="divider"></div>
      <div class="section">
        <p><span>email: </span>{{ $company->email }}</p>
      </div>
      <div class="divider"></div>
    </div>

    <div class="card-action right-align">
      <a href="{{ route('mst_company.edit', $id) }}" class="waves-effect waves-teal btn btn-floating red lighten-1">
        <i class="material-icons">edit</i>
      </a>
    </div>
  </div><!-- /.company Information -->
</section><!-- /.content Box -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_company.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
