<template>
    <div>
      <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <h4 class="alert-heading">Добро пожаловать в Ваш профиль!</h4>
        <p>
          Здесь Вы сможете проверить информацию о себе, управлять лицевым счетом, добавить фото в профиль, автомобили, технику и документы.
          Также всю информацию Вы сможете править, удалять и обновлять.
        </p>
        <hr>
        <p class="mb-0">Если у Вас появились Вопросы обратитесь к администраторам/менеджерам.</p>
      </div>
      <Loader v-if="loader"/>
      <div v-else>
        <div v-if="editProfile">
          <profile-edit-component :profile-id="profileId" :key="profileId"/>
        </div>
        <div v-else>
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Просмотр</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="personal-account-tab" data-bs-toggle="pill" data-bs-target="#personal-account" type="button" role="tab" aria-controls="personal-account" aria-selected="true">Лицевой счет</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="cars-tab" data-bs-toggle="pill" data-bs-target="#cars" type="button" role="tab" aria-controls="cars" aria-selected="true">Автомобили</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="mechanizms-tab" data-bs-toggle="pill" data-bs-target="#mechanizms" type="button" role="tab" aria-controls="mechanizms" aria-selected="true">Техника</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="documents-tab" data-bs-toggle="pill" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="true">Документы</button>
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <profile-show-component :profile-id="profileId" :key="profileId"/>
            </div>
            <div class="tab-pane fade" id="personal-account" role="tabpanel" aria-labelledby="personal-account">
              <personal-account-component :profile-id="profileId" :key="profileId"/>
            </div>
            <div class="tab-pane fade" id="cars" role="tabpanel" aria-labelledby="cars">
              <profile-cars-component :profile-id="profileId" :key="profileId"/>
            </div>
            <div class="tab-pane fade" id="mechanizms" role="tabpanel" aria-labelledby="mechanizms">
              <profile-mechanizms-component :profile-id="profileId" :key="profileId"/>
            </div>
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents">
              <profile-documents-component :profile-id="profileId" :key="profileId"/>
            </div>
          </div>
        </div>

      </div>

      <div class="position-fixed bottom-0 end-0">
        <button  v-if="editProfile" type="button" class="btn btn-lg btn-danger text-white  m-3" @click="editProfile = false">
          <i class="bi bi-x-circle"></i>
        </button>
        <button v-else type="button" class="btn btn-lg btn-info text-white  m-3" @click="editProfile = true">
          <i class="bi bi-pen-fill"></i>
        </button>
      </div>
    </div>
</template>

<script>
    export default {
        props: ['profileId'],
        mounted() {
            console.log('Component mounted.')
            setTimeout(() => {
              this.getProfile()
            }, 3000)
        },
        methods: {
          getProfile(){
            this.loader = true
            axios.post('/api/get-profile', { id : this.profileId}).then((response) =>{
              this.data = response.data
              this.loader = false
            })
          },
        },
        data() {
            return {
              data: [],
              loader: true,
              editProfile: false,
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
