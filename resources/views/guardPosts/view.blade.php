@extends('layouts.app')
@section('title')Доступные к просмотру посты охраны@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      <li class="breadcrumb-item active" aria-current="page">Посты охраны</li>
    </ol>
  </nav>
</div>
<div class="container">
  <view-guardposts-component/>
</div>
@endsection
