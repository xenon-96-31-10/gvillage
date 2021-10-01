@extends('layouts.app')
@section('title')Предавторизация пользователя@endsection
@section('content')
<div class="container my-2">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Предавторизация пользователя</li>
    </ol>
  </nav>
</div>
<div class="container my-2">
  <profile-create-component/>
</div>
@endsection
