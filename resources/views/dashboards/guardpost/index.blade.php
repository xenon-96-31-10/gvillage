@extends('layouts.app')
@section('title')Охранник поста@endsection
@section('content')
  @if(auth()->user()->get_guard_status()->status == 'Авторизован')
    @include('partials.nav.sidebar')
  @endif
@endsection
