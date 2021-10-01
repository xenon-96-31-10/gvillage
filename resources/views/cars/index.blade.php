@extends('layouts.app')
@section('title')Управление автомобилями@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Управление автомобилями</li>
    </ol>
  </nav>
</div>
<div class="container">
  <control-items-component type="cars"/>
</div>

@endsection
