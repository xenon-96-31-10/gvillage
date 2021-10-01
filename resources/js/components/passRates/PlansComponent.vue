<template>
    <div class="container my-2">
      <Loader v-if="loader"/>
      <div v-else>
        <div v-if="create">
          <div class="d-grid gap-2 my-2">
            <button @click="btnShow(null)" class="btn btn-primary text-white" type="button">Вернуться к списку</button>
          </div>
          <create-rate-plan-component :key="created" @refreshList="refreshList"/>
        </div>
        <div v-else-if="show">
          <div class="d-grid gap-2 my-2">
            <button @click="btnShow(null)" class="btn btn-primary text-white" type="button">Вернуться к списку</button>
          </div>
          <show-rate-plan-component :id="plan" :key="plan"/>
        </div>
        <div v-else-if="edit">
          <div class="d-grid gap-2 my-2">
            <button @click="btnShow(null)" class="btn btn-primary text-white" type="button">Вернуться к списку</button>
          </div>
          <edit-rate-plan-component :id="plan" :key="plan"/>
        </div>
        <div v-else>
          <div class="input-group my-1 sticky-top">
            <input  v-model="searchPlans"
                    type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
            <button @click="btnShow(null, 'create')"
                    class="btn btn-dark" type="button"><i class="bi bi-plus-square"></i></button>
            <button @click="searchPlans = ''" :class="{'disabled': searchPlans ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>

          <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Название</th>
                  <th scope="col">Дата создания</th>
                  <th scope="col">По умолчанию</th>
                  <th scope="col">Действие</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in Plans" class="bg-info" :key="item.id">
                  <th class="rounded-start" scope="row">{{item.id}}</th>
                  <td>{{item.name}}</td>
                  <td>{{item.create}}</td>
                  <td>
                    <button v-if="item.default ==='По умолчанию'" type="button" class="btn btn-success text-white">
                      <i class="bi bi-star-fill"></i>
                    </button>
                    <button v-else type="button" class="btn btn-secondary" @click="updateToDefault(item.id)">
                      <i class="bi bi-star-fill"></i>
                    </button>
                  </td>
                  <td class="rounded-end">
                    <div class="d-flex justify-content-between">
                      <button type="button" class="btn btn-light" @click="btnShow(item.id, 'edit')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button type="button" class="btn btn-light" @click="btnShow(item.id, 'show')">
                        <i class="bi bi-eye"></i>
                      </button>
                      <button v-if="item.default !='По умолчанию'" type="button" class="btn btn-danger" @click="deletePlan(item.id)">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="Plans.length < 1" class="bg-danger">
                  <td class="rounded-start text-white">К сожелению, по результату поиска ничего не найдено... Попробуйте еще.</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="rounded-end">
                    <button class="btn btn-light" type="button" @click="searchPlans = ''">
                      Очистить
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getPlans()
        },
        data(){
          return{
            loader: true,
            plans: [],
            searchPlans: '',
            edit: false,
            create: false,
            show: false,
            plan: null,
            created: 1,
          }
        },
        methods:{
          getPlans(){
            axios.post('/api/get-pass-plans').then((response) =>{
              this.plans = response.data
              this.loader = false
            })
          },
          refreshList(){
            this.loader = true
            this.created += 1
            this.create = false,
            this.getPlans()
          },
          updateToDefault(id){
            axios.post('/update/default-pass-rate-plan', {id}).then((response) =>{
              this.loader = true
              this.getPlans()
            })
          },
          deletePlan(id){
            axios.post('/delete/pass-rate-plan', {id}).then((response) =>{
              this.loader = true
              this.getPlans()
            })
          },
          btnShow(id, type){
            this.plan = id
            if(type === 'edit'){
              this.edit = true
              this.create = this.show = false
            }else if(type === 'show'){
              this.show = true
              this.create = this.edit = false
            }else if(type === 'create'){
              this.create = true
              this.show = this.edit = false
            }else{
              this.create = this.show = this.edit = false
              this.plan = null
            }
          },
        },
        computed: {
            Plans() {
              return this.plans.filter(item => item.name.toLowerCase().indexOf(this.searchPlans.toLowerCase()) !== -1 || item.default.toLowerCase().indexOf(this.searchPlans.toLowerCase()) !== -1)
            },
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
</style>
