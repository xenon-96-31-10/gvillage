@extends('layouts.app')
@section('title')Управление пользователями@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Пользователи</li>
    </ol>
  </nav>
</div>
<div class="container">
  <control-profiles-component />
</div>


@endsection
