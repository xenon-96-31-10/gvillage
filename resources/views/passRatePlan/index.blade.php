@extends('layouts.app')
@section('title')Отчет по ЛС @endsection
@section('content')
<div class="container my-2">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      @role('guard-post')
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('guardpost')}}">Главная</a></li>
      @else
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      @endrole
      <li class="breadcrumb-item active" aria-current="page">Тарифные планы</li>
    </ol>
  </nav>
</div>
<pass-rate-plans-component/>
@endsection
