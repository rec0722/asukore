@extends( 'layouts.base' )

@section( 'title', '会社情報 編集')

@section( 'content' )

<div class="page-title">
  <h1 class="headline1">@yield('title')</h1>
</div>

<!-- content -->
<section class="content z-depth-1 h-adr">

  {{ Form::model($company, ['route' => ['mst_company.update', $id], 'method' => 'PATCH']) }}

  <!-- company Form -->
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('name', null, ['class' => 'validate' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'required']) }}
      {{ Form::label('name', '会社名') }}
      <span class="helper-text">※必須項目です</span>
      @error('name')
      <span class="error invalid-feedback">{{ $message }}</span>
      @enderror
    </div>
  </div>
  <div class="row">
    <div class="input-field col s8 l4">
      {{ Form::text('zipcode', null, ['class' => 'validate p-postal-code', 'id' => 'zipcode', 'maxlength' => '7']) }}
      {{ Form::label('zipcode', '郵便番号') }}
      <span class="helper-text">※ハイフン（-）を入れずに入力してください</span>
    </div>
  </div>
  <div class="row">
    <div class="col s12 l6">
      {{ Form::label('prefecture_id', '都道府県') }}
      {{ Form::select('prefecture_id', $list['prefList'], null, ['class' => 'browser-default p-region-id']) }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::text('city', null, ['class' => 'validate p-locality', 'id' => 'city']) }}
      {{ Form::label('city', '市区町村') }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12">
      {{ Form::text('address', null, ['class' => 'validate p-street-address p-extended-address', 'id' => 'address']) }}
      {{ Form::label('address', '住所') }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12 l6">
      {{ Form::email('email', null, ['class' => 'validate', 'id' => 'email']) }}
      {{ Form::label('email', 'メールアドレス') }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s8 l4">
      {{ Form::text('tel', null, ['class' => 'validate', 'id' => 'tel']) }}
      {{ Form::label('tel', '電話番号') }}
    </div>
  </div>
  <div class="row">
    <div class="input-field col s8 l4">
      {{ Form::text('fax', null, ['class' => 'validate', 'id' => 'fax']) }}
      {{ Form::label('fax', 'FAX番号') }}
    </div>
  </div>
  <!-- /.company Form -->

  <div class="row">
    <div class="col s12 center-align">
      {{ Form::button('保存する', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
    </div>
  </div>

  {{ Form::close() }}

</section><!-- /.content -->

<div class="row">
  <div class="col s12 center-align">
    <a href="{{ route('mst_company.index') }}" class="waves-effect waves-teal btn-flat">一覧に戻る</a>
  </div>
</div>

@endsection
