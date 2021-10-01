<div class="col-12">
  <div class="card sidebar-items h-100">
    <div class="card-body">
      <h5 class="card-title">{{auth()->user()->profile->fio}} <i class="bi bi-person-badge"></i></h5>
      <p class="card-text">Управление своим профилем и информацией о себе</p>
    </div>
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
      <p class="card-text mb-1"><strong>{{auth()->user()->roles()->first()->name}}</strong></p>
      <a href="{{ route('profile')}}" class="btn btn-primary text-white">
        <i class="bi bi-arrow-right-square-fill"></i>
      </a>
    </div>
  </div>
</div>
