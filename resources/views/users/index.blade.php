@extends('layouts.app')
@section('title')Все пользователи@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Все пользователи</li>
    </ol>
  </nav>
</div>
<div class="container">
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="all-profiles-tab" data-bs-toggle="pill" data-bs-target="#all-profiles" type="button" role="tab" aria-controls="all-profiles" aria-selected="true">Все пользователи</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="all-users-tab" data-bs-toggle="pill" data-bs-target="#all-users" type="button" role="tab" aria-controls="all-users" aria-selected="false">Зарегестрированные</button>
    </li>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="all-roles-tab" data-bs-toggle="pill" data-bs-target="#all-roles" type="button" role="tab" aria-controls="all-roles" aria-selected="false">Роли и их права</button>
      </li>
    @endif
  </ul>
  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="all-profiles" role="tabpanel" aria-labelledby="all-profiles-tab">
      <profiles-component/>
    </div>
    <div class="tab-pane fade" id="all-users" role="tabpanel" aria-labelledby="all-users-tab">
      <users-component/>
    </div>    
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
      <div class="tab-pane fade" id="all-roles" role="tabpanel" aria-labelledby="all-users-tab">
        <roles-component/>
      </div>
    @endif
  </div>
</div>


@endsection
