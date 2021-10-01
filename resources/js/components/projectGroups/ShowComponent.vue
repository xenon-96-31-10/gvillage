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
              <li>Пост охраны: <strong>{{data.post.name}}</strong></li>
              <li>Тарифный план: <strong>{{data.plan.name}}</strong></li>
              <li>Колличество проектов: <strong>{{data.projects.length}}</strong></li>
            </ul>
            <h5 class="card-title mb-2">Объекты в группе:</h5>
            <ol class="list-group list-group-numbered mb-2">
              <li v-for="project in data.projects" :key="project.id" class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                  <div class="fw-bold">{{project.address}}</div>
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
            axios.post('/api/get-project-group', { id : this.projectId}).then((response) =>{
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
