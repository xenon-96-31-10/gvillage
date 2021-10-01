@extends('layouts.app')
@section('title')Профиль@endsection
@section('content')
<div class="container my-2">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Профиль</li>
    </ol>
  </nav>
</div>
<div class="container my-2">
  <profile-edit-component profile-id="{{$id}}"></profile-edit-component>
</div>
@endsection
