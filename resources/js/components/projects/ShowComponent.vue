<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="card">
          <div class="card-header">
            №{{data.id}} "{{data.name}}"
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Адрес: <strong>{{data.address}}</strong></h5>

            <h5 class="card-title mb-2">Дополнительная информация</h5>
            <ul>
              <li>Описание: <strong>{{data.description}}</strong></li>
              <li>Группа: <strong>{{data.group.name}}</strong></li>
              <li>Тип: <strong>{{data.type.name}}</strong></li>
              <li>Общая площадь: <strong>{{data.totalarea}} м<sup>2</sup></strong></li>
              <li>Жилая площадь: <strong>{{data.livingarea}} м<sup>2</sup></strong></li>
            </ul>
            <h5 class="card-title mb-2">Пользователи</h5>
            <ol class="list-group list-group-numbered mb-2">
              <li v-for="profile in data.profiles" :key="profile.id" class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{profile.fio}}</div>
                </div>
                <div>
                  {{profile.role}}
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
    import {required, minLength, email} from 'vuelidate/lib/validators'
    import moment from 'moment';
    export default {
        props: ['projectId'],
        mounted() {
            console.log('Component mounted.')
            setTimeout(() => {
              this.getProject()
            }, 3000)
        },
        methods: {
          getProject(){
            this.loader = true
            axios.post('/api/get-project', { id : this.projectId}).then((response) =>{
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
