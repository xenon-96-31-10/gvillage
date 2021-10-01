@extends('layouts.auth')
@section('title')Востановление пароля @endsection
@section('styles')
<style media="screen">
body{
 background-image: url('../../storage/images/auth/fon-login.jpg');
 background-attachment: fixed;
 background-size: cover;
 background-position: center;
}
</style>
@endsection
@section('content')

<reset-component login-route="{{ route('login') }}" password-update-route="{{ route('password.update') }}" token={{$token}} email="{{$email}}"></reset-component>
@endsection
