@extends('layouts.app')
@section('title')Заказ постоянного пропуска@endsection
@section('content')
<div class="container my-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      @role('guard-post')
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('guardpost')}}">Главная</a></li>
      @else
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      @endrole
      <li class="breadcrumb-item active" aria-current="page">Заказ постоянного пропуска</li>
    </ol>
  </nav>
</div>
<div class="container my-3">
  <div class="row">
  <div class="col">
    <div class="card rounded shadow-lg">
      <div class="card-header">
        <h2>Оформление нового постоянного пропуска</h2>
      </div>
      <div class="card-body">
        @include('passes.permanent.createform')
      </div>
    </div>
  </div>
</div>

</div>
@endsection
