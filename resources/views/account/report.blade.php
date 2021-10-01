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
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('profile', ['id' => auth()->user()->profile->id])}}">Профиль</a></li>
      <li class="breadcrumb-item active" aria-current="page">Отчет по ЛС</li>
    </ol>
  </nav>
</div>
<report-account-component :id="{{auth()->user()->profile->account != null ? auth()->user()->profile->account->id : 'null'}}"/>
@endsection
