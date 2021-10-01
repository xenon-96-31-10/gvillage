<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="card">
          <div class="card-header">
            №{{data.id}} "{{data.name}}"
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Организация: <strong>{{data.organization.name}}</strong></h5>

            <h5 class="card-title mb-2">Дополнительная информация</h5>
            <ul class="mb-2">
              <li>Телефон связи с постом: <strong>+7{{data.phone}}</strong></li>
              <li>Начальник поста: <strong>{{data.chief.fio}} (+7{{data.chief.phone}})</strong></li>
              <li>Привязан к группе: <strong>{{data.projectGroup.name}}</strong></li>
            </ul>
            <h5 class="card-title mb-2">Охранники:</h5>
            <ol class="list-group list-group-numbered mb-2">
              <li v-for="guard in data.guards" :key="guard.id" class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{guard.fio}}</div>
                </div>
                <div class="ms-2 me-auto">
                  <div class="fw-bold">+7{{guard.phone}}</div>
                </div>
              </li>
            </ol>
            <h5 class="card-title mb-2">Входы/выходы:</h5>
            <ol class="list-group list-group-numbered mb-2">
              <li v-for="enter in data.enters" :key="enter.id" class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{enter.name}}</div>
                </div>
                <div class="ms-2 me-auto">
                  <div class="fw-bold">+7{{enter.phone}}</div>
                </div>
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{enter.status}}</div>
                </div>
              </li>
            </ol>
          </div>
          <div class="card-footer text-muted">
            Дата создания: {{data.date}}
          </div>
        </div>

      </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import moment from 'moment';
    export default {
        props: ['guardpostId'],
        mounted() {
            console.log('Component mounted.')
            setTimeout(() => {
              this.getGuardPost()
            }, 3000)
        },
        methods: {
          getGuardPost(){
            this.loader = true
            axios.post('/api/get-guard-post', { id : this.guardpostId}).then((response) =>{
              this.data = response.data
              this.loader = false
            })
          }
        },
        data() {
            return {
              data: [],
              loader: true,
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
