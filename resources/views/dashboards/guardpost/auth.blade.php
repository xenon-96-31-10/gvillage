@extends('layouts.app')
@section('title')Авторизация на посту@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center my-5">
        <div class="col-md-6">
          @if(auth()->user()->get_guard_status() != null && auth()->user()->get_guard_status()->status == 'Авторизован')
            <div class="alert alert-danger d-flex align-items-center h4" role="alert">
              <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Вы уже авторизованны в системе.
                Перейдите в <a href="{{route('guardpost')}}" class="link-danger">панель</a> или <a  class="link-danger" href="{{ route('guardpost.logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-post').submit();">смените пост</a>, чтобы продолжить!
              </div>
            </div>
          @elseif(auth()->user()->get_guard_status() == null)
          <div class="alert alert-primary d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
              <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <div>
              Это Ваш первый вход в систему. Добро пожаловать !
            </div>
          </div>
          @endif

          <div class="card">
              <div class="card-header">Авторизация на посту "{{$post->name}}"</div>
              <div class="card-body">
                <form method="post" action="{{ route('guardpost.auth')}}" class="needs-validation">
                  @csrf
                  <div class="form-floating mb-3">
                    <select class="form-select" name="enter" id="floatingEnter" aria-label="Выбор...">
                      @foreach($enters as $enter)
                        <option value="{{$enter->id}}" @if($enter->status == 'Закрыт' || $enter->status == 'Под наблюдением') disabled @endif>{{$enter->name}} @if($enter->status != 'Активный')| {{$enter->status }} @endif</option>
                      @endforeach
                    </select>
                    <label for="floatingEnter">Выберите блок пост</label>
                  </div>
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-dark" @if(auth()->user()->get_guard_status() != null && auth()->user()->get_guard_status()->status == 'Авторизован') disabled @endif>Авторизоваться</button>
                  </div>
                </form>
              </div>
              <div class="card-footer d-flex justify-content-end">
                <a class="btn btn-primary text-white" data-bs-toggle="offcanvas" href="#offcanvasEnters" role="button" aria-controls="offcanvasEnters">
                  <i class="bi bi-info-square-fill"></i>
                  Для справки
                </a>
              </div>
          </div>

        </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnters" aria-labelledby="offcanvasEntersLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasEntersLabel">Справка о КПП</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="h5 mb-2">
      Узнайте о статусе всех пропускных пунктов Вашего поста
    </div>
    @foreach($enters as $enter)
    <div class="alert alert-info" role="alert">
      <h4 class="alert-heading">{{$enter->name}}</h4>
      @if($enter->status == 'Под наблюдением')
        @if($enter->status()->guarder->id == auth()->user()->id)
          <p class="lead">Охранник: Это Вы</p>
        @else
          <p class="lead">Охранник: <strong>{{$enter->status()->guarder->profile->fio}}</strong></p>
        @endif
        <p class="lead">Сейчас: <strong>{{$enter->status()->guarder->status}}</strong></p>
        <p class="lead">Телефон: <strong>+7{{$enter->status()->guarder->phone}}</strong></p>
      @endif
      <hr>
      <p class="mb-0">{{$enter->status}}</p>
    </div>
    @endforeach
  </div>
</div>
@endsection
