<header class="page-header">
  <!-- Navigation -->
  <nav>
    <div class="nav-wrapper">
      <a href="{{ route('dashboard') }}" class="brand-logo"><img src="{{ asset('img/logo.svg') }}" alt=""></a>
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="{{ route('dashboard') }}">HOME</a></li>
        <li>
          <a class="dropdown-trigger" href="#" data-target="desktop-report">報告管理
            <i class="material-icons right">arrow_drop_down</i>
          </a>
           <!-- Dropdown Structure -->
           <ul id="desktop-report" class="dropdown-content">
            <li><a href="{{ route('report.create') }}">日報作成</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('report.index') }}">日報一覧</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('weekly-report.create') }}">週報作成</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('weekly-report.index') }}">週報一覧</a></li>
          </ul><!-- /.Dropdown Structure -->
        </li>
        <!-- Dropdown Trigger -->
        @if (Auth::user()->role > 3)
        <li>
          <a class="dropdown-trigger" href="#" data-target="desktop-master">マスタ設定
            <i class="material-icons right">arrow_drop_down</i>
          </a>
          <!-- Dropdown Structure -->
          <ul id="desktop-master" class="dropdown-content">
            <li><a href="{{ route('mst_user.index') }}">ユーザ情報</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('mst_company.index') }}">会社情報</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('mst_dept.index') }}">部署情報</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('mst_group.index') }}">グループ情報</a></li>
          </ul><!-- /.Dropdown Structure -->
        </li>
        @endif
        <li>
          <a class="dropdown-trigger" href="#" data-target="desktop-user">ユーザ情報
            <i class="material-icons right">arrow_drop_down</i>
          </a>
          <!-- Dropdown Structure -->
          <ul id="desktop-user" class="dropdown-content">
            <li><a href="{{ route('mst_user.show', Auth::user()->id) }}">プロフィール</a></li>
            <li class="divider" tabindex="-1"></li>
            <li>
              {{ Form::open(['route' => 'logout']) }}
              {{ Form::button('<i class="material-icons right">exit_to_app</i>ログアウト', ['class' => 'waves-effect waves-light btn logout', 'type' => 'submit']) }}
              {{ Form::close() }}
            </li>
          </ul><!-- /.Dropdown Structure -->
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.Navigation -->

  <!-- Mobile Navigation -->
  <ul id="slide-out" class="sidenav">
    <ul id="nav-mobile">
      <li><a href="{{ route('dashboard') }}">HOME</a></li>
      <li>
          <a class="dropdown-trigger" href="#" data-target="mobile-report">報告管理
            <i class="material-icons right">arrow_drop_down</i>
          </a>
           <!-- Dropdown Structure -->
           <ul id="mobile-report" class="dropdown-content">
            <li><a href="{{ route('report.create') }}">日報作成</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('report.index') }}">日報一覧</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('weekly-report.create') }}">週報作成</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('weekly-report.index') }}">週報一覧</a></li>
          </ul><!-- /.Dropdown Structure -->
        </li>
      <!-- Dropdown Trigger -->
      @if (Auth::user()->role > 3)
      <li>
        <a class="dropdown-trigger" href="#" data-target="mobile-master">マスタ設定
          <i class="material-icons right">arrow_drop_down</i>
        </a>
        <ul id="mobile-master" class="dropdown-content">
          <li><a href="{{ route('mst_user.index') }}">ユーザ情報</a></li>
          <li class="divider" tabindex="-1"></li>
          <li><a href="{{ route('mst_company.index') }}">会社情報</a></li>
          <li class="divider" tabindex="-1"></li>
          <li><a href="{{ route('mst_dept.index') }}">部署情報</a></li>
          <li class="divider" tabindex="-1"></li>
          <li><a href="{{ route('mst_group.index') }}">グループ情報</a></li>
        </ul><!-- /.Dropdown Structure -->
      </li>
      @endif
      <li>
        <a href="{{ route('mst_user.show', Auth::user()->id) }}">プロフィール</a>
      </li>
      <li class="center-align">
        {{ Form::open(['route' => 'logout']) }}
        {{ Form::button('<i class="material-icons right">exit_to_app</i>ログアウト', ['class' => 'waves-effect waves-light btn', 'type' => 'submit']) }}
        {{ Form::close() }}
      </li>
    </ul>
  </ul><!-- /.Mobile Navigation -->
</header>
