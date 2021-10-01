@extends('layouts.auth')
@section('title')Отправка ссылки на востановление пароля@endsection
@section('styles')
<style media="screen">
  body{
   background-image: url('../storage/images/auth/fon-login.jpg');
   background-attachment: fixed;
   background-size: cover;
   background-position: center;
  }
</style>
@endsection
@section('content')
<reset-link-component password-email-route="{{ route('password.email') }}"></reset-link-component>
@endsection
