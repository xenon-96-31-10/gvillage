<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="row">
            <div class="col-sm-4 mb-2 mb-sm-0">
              <div class="card my-2">
                <img v-if="data.avatar != 'Фото отсутствует'" :src="data.avatar" class="card-img-top" alt="Аватар">
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-person-circle card-img-top my-1 mx-auto" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>
              </div>              
            </div>
            <div class="col-sm-8">
                <div class="card my-2">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Профиль</h5>
                    </div>
                  </div>

                  <div class="card-body">
                    <p class="card-text">ФИО: <strong> {{data.profile.fio}}</strong></p>
                    <p class="card-text">Роль: <strong> {{data.profile.role}}</strong></p>
                    <p class="card-text">Пол: <strong> {{data.profile.sex}}</strong></p>
                    <p class="card-text">Дата рождения: <strong>{{data.dateofbirth}} г.</strong></p>
                  </div>
                </div>

                <div class="card my-2">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Контактная информация</h5>
                    </div>
                  </div>

                  <div class="card-body">
                    <p class="card-text"><i class="bi bi-house-fill"></i> : <strong>{{data.profile.address}}</strong></p>
                    <p class="card-text"><i class="bi bi-telephone-inbound-fill"></i> : <strong>+7{{data.profile.phone}}</strong></p>
                    <p class="card-text"><i class="bi bi-envelope-fill"></i> : <strong>{{data.profile.email}}</strong></p>
                  </div>
                </div>

            </div>
        </div>
      </div>
    </div>
</template>

<script>
    import {required, minLength, email} from 'vuelidate/lib/validators'
    import moment from 'moment';
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
            axios.post('/api/get-profile', { id : this.profileId, show : true}).then((response) =>{
              this.data = response.data
              this.loader = false
            })
          }
        },
        data() {
            return {
              data: [],
              loader: true,
              docs: [],
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
