<template>
    <div>
      <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <h4 class="alert-heading">Добро пожаловать! Это управление пользователями, которые Вам доступны!</h4>
        <p>
          Вы можете просмотривать и редактировать профили.
        </p>
        <hr>
        <p class="mb-0">В режиме редактирования: Вы также сможете подключать, пополнять ЛС, добавлять/удалять авто, технику,документы в выбранный профиль.</p>
      </div>
      <div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Все пользователи</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">Зарегестрированные пользователи</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="roles-tab" data-bs-toggle="pill" data-bs-target="#roles" type="button" role="tab" aria-controls="roles" aria-selected="true">Пользовательские роли и их права</button>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <profiles-component/>
          </div>
          <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users">
            <users-component/>
          </div>
          <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles">
            <roles-component/>
          </div>
        </div>
      </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
        },
        data() {
            return {
            }
        },
    }
</script>
<style scoped>
.slide-fade-enter-active {
  transition: all 0.5s ease;
}
.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}
.table{
  border-collapse: separate!important;
  border-spacing: 0 5px;
  cursor: pointer;
}
.offcanvas-bottom{
  height: 40vh!important;
}
</style>
