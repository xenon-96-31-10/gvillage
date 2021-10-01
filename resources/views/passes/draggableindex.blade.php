@extends('layouts.app')
@section('title')Draggeble Все пропуска@endsection
@section('content')
<div class="container-fluid my-2">
  <h1 class="text-center">Пропуски</h1>
  <draggablepasses-component getfullpasslist-route="{{route('api.getfullpasslist')}}" checkin-route="{{route('pass.checkin')}}" checkout-route="{{route('pass.checkout')}}" deletefromlist-route="{{route('pass.deletefromlist')}}" delete-route="{{route('pass.delete')}}" repeat-route="{{route('pass.repeattemporary')}}"></draggablepasses-component>
</div>
@endsection
