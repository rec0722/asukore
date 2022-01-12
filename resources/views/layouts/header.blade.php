<header class="page-header">
  <!-- Navigation -->
  <nav>
    <div class="nav-wrapper">
      <a href="{{ route('dashboard') }}" class="brand-logo"><img src="{{ asset('img/logo.svg') }}" alt=""></a>
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="{{ route('report.create') }}">日報作成</a></li>
        <li><a href="{{ route('report.index') }}">日報一覧</a></li>
        <!-- Dropdown Trigger -->
        @if (Auth::user()->role > 3)
        <li>
          <a class="dropdown-trigger" href="#" data-target="dropdown1">マスタ設定
            <i class="material-icons right">arrow_drop_down</i>
          </a>
          <!-- Dropdown Structure -->
          <ul id="dropdown1" class="dropdown-content">
            <li><a href="{{ route('mst_user.index') }}">ユーザ情報</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('mst_company.index') }}">会社情報</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="{{ route('mst_dept.index') }}">部署情報</a></li>
          </ul><!-- /.Dropdown Structure -->
        </li>
        @endif
        <li>
          <a class="dropdown-trigger" href="#" data-target="dropdown2">ユーザ情報
            <i class="material-icons right">arrow_drop_down</i>
          </a>
          <!-- Dropdown Structure -->
          <ul id="dropdown2" class="dropdown-content">
            <li><a href="{{ route('mst_user.show', Auth::user()->id) }}">プロフィール</a></li>
            <li class="divider" tabindex="-1"></li>
            <li style="min-height: 36px;">
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="sublit" class="btn" style="width: 100%;">
                  <i class="fas fa-power-off right"></i>
                  ログアウト
                </button>
              </form>
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
      <li><a href="{{ route('report.create') }}">日報作成</a></li>
      <li><a href="{{ route('report.index') }}">日報一覧</a></li>
      <!-- Dropdown Trigger -->
      @if (Auth::user()->role > 3)
      <li>
        <a class="dropdown-trigger" href="#" data-target="dropdown3">マスタ設定
          <i class="material-icons right">arrow_drop_down</i>
        </a>
        <ul id="dropdown3" class="dropdown-content">
          <li><a href="{{ route('mst_user.index') }}">ユーザ情報</a></li>
          <li class="divider" tabindex="-1"></li>
          <li><a href="{{ route('mst_company.index') }}">会社情報</a></li>
          <li class="divider" tabindex="-1"></li>
          <li><a href="{{ route('mst_dept.index') }}">部署情報</a></li>
        </ul><!-- /.Dropdown Structure -->
      </li>
      @endif
      <li>
        <a href="{{ route('mst_user.show', Auth::user()->id) }}">プロフィール</a>
      </li>
      <li class="center-align">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="sublit" class="btn">
            <i class="fas fa-power-off right"></i>
            ログアウト
          </button>
        </form>
      </li>
    </ul>
  </ul><!-- /.Mobile Navigation -->
</header>
