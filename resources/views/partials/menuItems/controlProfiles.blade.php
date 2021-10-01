<div class="col">
  <div class="card sidebar-items h-100">
    <div class="card-body">
      <h5 class="card-title">Пользователи <i class="bi bi-people-fill"></i></h5>
      <p class="card-text text-mutted">Управление пользователями сайта</p>
    </div>
    <div class="card-footer bg-transparent d-flex justify-content-end">
      <a href="{{ route('profile.showCreateForm') }}" class="btn btn-info text-white me-2">
        <i class="bi bi-person-plus"></i>
      </a>
      <a href="{{ route('profiles.control') }}" class="btn btn-primary text-white">
        <i class="bi bi-person-lines-fill"></i>
      </a>
    </div>
  </div>
</div>
