@extends('layouts.app')
@section('title')Все пользователи@endsection
@section('content')
<div class="container my-3 bg-white shadow rounded">
  <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      @role('guard-post')
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('guardpost')}}">Главная</a></li>
      @else
        <li class="breadcrumb-item"><a class="link-dark" href="{{ route('login')}}">Главная</a></li>
      @endrole
      <li class="breadcrumb-item active" aria-current="page">Регистрация пользователя</li>
    </ol>
  </nav>
</div>

<div class="container my-3">
  <div class="row">
    <div class="col">
      <div class="card rounded shadow-lg">
        <div class="card-header">
          <h2>Регистрация нового пользователя</h2>
        </div>
        <div class="card-body">
          <register-user-component  register-user-route="{{ route('users.register')}}"
                                    get-roles-route="{{ route('api.getRoles')}}"
                                    get-organizations-route="{{ route('api.getOrganizations')}}"
                                    get-project-groups-route="{{ route('api.getProjectGroups')}}"
                                    get-families-route="{{ route('api.getFamilies')}}"
                                    get-family-projects-route="{{ route('api.getFamilyProjects')}}"
                                    get-project-types-route="{{ route('api.getProjectTypes')}}"
                                    get-activities-route="{{ route('api.getActivities')}}"
                                    get-equipments-route="{{ route('api.getEquipments')}}"
                                    get-option-projects-route="{{ route('api.getOptionProjects')}}"
                                    get-guard-posts-route="{{ route('api.getGuardPosts')}}"
                                    ></register-user-component>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
