@extends('layouts.app')
@section('title')Добро пожаловать@endsection
@section('content')
<div class="container">
  <div class="px-4 pt-5 text-center border-bottom">
    <h1 class="display-4 fw-bold"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Добро пожаловать на наш сайт</font></font></h1>
    <div class="row g-2 py-5 row-cols-1 row-cols-sm-3">
      <div class="feature col">
        <div class="feature-icon bg-primary bg-gradient">
          <i class="bi bi-ui-checks"></i>
        </div>
        <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Регистрация разовых пропусков</font></font></h2>
      </div>
      <div class="feature col">
        <div class="feature-icon bg-primary bg-gradient">
          <i class="bi bi-ui-checks-grid"></i>
        </div>
        <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Учёт постоянных пропусков</font></font></h2>
      </div>
      <div class="feature col">
        <div class="feature-icon bg-primary bg-gradient">
          <i class="bi bi-people-fill"></i>
        </div>
        <h2><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Контроль количества гостей на территории</font></font></h2>
      </div>


    </div>
    <div class="col-lg-6 mx-auto">
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
        <a href="https://moscow.rt.ru/videocontrol" class="btn btn-primary btn-lg text-white px-4 me-sm-3"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Зарегестрироваться</font></font></a>
        <button type="button" class="btn btn-outline-secondary btn-lg px-4"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">О проекте</font></font></button>
      </div>
    </div>
    <div class="overflow-hidden" style="max-height: 30vh;">
      <div class="container px-5">
        <img src="storage/images/header.png" class="img-fluid border rounded-3 shadow-lg mb-4" alt="Пример изображения" width="700" height="500" loading="lazy">
      </div>
    </div>
  </div>
</div>
@endsection
