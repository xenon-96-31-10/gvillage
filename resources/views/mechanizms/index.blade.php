@extends('layouts.app')
@section('title')Управление техникой@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Управление техникой</li>
    </ol>
  </nav>
</div>
<div class="container">
  <control-items-component type="mechanizms"/>
</div>

@endsection
