<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <div class="justify-content-start">
        <a class="navbar-brand" href="{{ route('welcome') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
      </div>
      <div class="justify-content-center">

      </div>
      <div class="justify-content-end">
        <ul class="navbar-nav">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Войти <i class="bi bi-box-arrow-in-right"></i></a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Регистрация <i class="bi bi-person-plus"></i></a>
                    </li>
                @endif
            @else
              <li class="nav-item me-2">
                <div class="btn-group">
                  <a class="btn btn-info text-white" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"><i class="bi bi-bell-fill"></i> <span class="badge bg-danger">{{auth()->user()->unreadNotifications->count()}}</span></a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">{{ Auth::user()->login }}</a>
                <ul class="dropdown-menu">
                  @role('guard-post')
                    @include('dashboards.guardpost.nav')
                  @endrole
                  <li>
                    @role('guard-post')
                      <a class="dropdown-item" href="{{ route('guardpost')}}">
                    @else
                      <a class="dropdown-item" href="{{ route('login')}}">
                    @endrole
                      Панель управления <i class="bi bi-house-door-fill"></i></a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        Выход <i class="bi bi-box-arrow-left"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                  </li>
                </ul>
              </li>
            @endguest
        </ul>
      </div>
    </div>
</nav>
