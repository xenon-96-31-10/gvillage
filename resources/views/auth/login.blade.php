@extends('layouts.auth')
@section('title')Вход в Личный кабинет@endsection
@section('styles')
<style media="screen">
body{
 background-image: url('storage/images/auth/fon-login.jpg');
 background-attachment: fixed;
 background-size: cover;
 background-position: center;
}
</style>
@endsection
@section('content')
  <login-component password-request-route="{{ route('password.request') }}"></login-component>
@endsection
