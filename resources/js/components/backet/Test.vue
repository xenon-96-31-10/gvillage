<template>
    <div>
      <form enctype="multipart/form-data" novalidate>
        <transition name="slide-fade">
          <div v-if="createMessage" class="alert alert-success" role="alert">
             Вы успешно создали пропуск!
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-show="step === 1">
            <h3>Шаг 1. Выбор объекта</h3>
            <hr>
            <div class="input-group mb-1">
              <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                      type="text" class="form-control" placeholder="Поиск" aria-label="Поиск объекта" aria-describedby="clear">
              <button v-on:click="clearSearchItem" :class="{'disabled': searchItem==''}"
                      class="btn btn-outline-secondary" type="button" id="clear">Очистить</button>
            </div>
            <div class="table-responsive">
              <table  :class="status($v.form.project)"
                      v-model="form.project"
                      class="table table-hover table-borderless align-middle">
                <tbody>
                  <tr v-for="item in paginatedItems" :key="item.id" :class="{ 'bg-success': item.id === form.project, 'bg-info': item.id != form.project }" @mouseover="id = item.id"  @click="selectItem(item)">
                    <th class="rounded-start" scope="row">{{item.id}}</th>
                    <td>{{item.name}}</td>
                    <td>{{item.address}}</td>
                    <td>{{item.owner}}</td>
                    <td class="text-end rounded-end">
                      <span v-if="item.id != form.project" class="btn btn-light" >
                        Выбрать
                      </span>
                      <span v-else class="btn btn-danger text-white">
                        Отменить
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center">
              <nav aria-label="...">
                <ul class="pagination">
                  <li class="page-item"
                      :class="{'disabled': pagination.currentPage == pagination.items[0] || pagination.items.length==0}">
                      <a @click="selectPage(pagination.currentPage-1)"
                          class="page-link">Назад</a>
                  </li>
                  <li v-for="item in pagination.filteredItems"
                      :class="{'active': item == pagination.currentPage}"
                      @click="selectPage(item)"
                      class="page-item"><a class="page-link">{{item}}</a></li>

                  <li class="page-item" :class="{'disabled': pagination.currentPage==pagination.items[pagination.items.length-1] || pagination.items.length==0}">
                    <a @click="selectPage(pagination.items[pagination.items.length-1])"
                        class="page-link">Вперед</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-show="step === 2">
            <h3>Шаг 1. Выбор объекта</h3>
            <hr>
            <div class="btn-group mb-3 d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
              <input @blur="$v.form.visitor.$touch()" type="radio" class="btn-check" v-model="form.visitor" value="car" id="visitor-car" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-car">Автомобиль <i class="bi bi-truck"></i></label>

              <input @blur="$v.form.visitor.$touch()"  type="radio" class="btn-check" v-model="form.visitor" value="human" id="visitor-human" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-human">Человек <i class="bi bi-person-square"></i></label>

              <input @blur="$v.form.visitor.$touch()"  type="radio" class="btn-check" v-model="form.visitor" value="mechanizm" id="visitor-mechanizm" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-mechanizm">Техника <i class="bi bi-truck-flatbed"></i></label>
            </div>
          </div>
        </transition>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
          <button :disabled="step === minstep"@click="backStep" class="btn btn-secondary btn-lg me-sm-2 text-white" type="button"><i class="bi bi-arrow-left-square"></i> Назад</button>

          <button v-if="step < maxstep" class="btn btn-info btn-lg text-white" @click="nextStep" type="button">Дальше <i class="bi bi-arrow-right-square"></i></button>
          <button v-show="step === maxstep" @click.prevent="createPass"  class="btn btn-primary btn-lg text-white"type="submit">Оформить пропуск</button>
        </div>

      </form>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'

    import moment from 'moment';
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        created(){
          this.getProjects()
        },
        data() {
            return {
              step: 1,
              minstep: 1,
              maxstep: 3,
              createMessage: false,
              projects: [],
              searchItem: '',
              items: [],
              id: 1,
              filteredItems: [],
              paginatedItems: [],
              pagination: {
                range: 5,
                currentPage: 1,
                itemPerPage: 5,
                items: [],
                filteredItems: [],
              },
              form: {
                project: null,
                visitor: '',
                visitor_id: null,
              }
            }
        },
        methods: {
          backStep(){
            if(this.step > this.minstep) this.step--
          },
          nextStep(){
            let next = true
            if(this.step === 1){
              if(this.$v.form.project.$invalid){
                this.$v.form.project.$touch()
                next = false
              }
            }
            if(next && this.step < this.maxstep){this.step++}
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'ring': validation.$error,
               'is-valid': validation.required,
             }
          },
          getProjects(){
            axios.post('/api/get-projects').then((response) =>{
              this.projects = response.data

              this.filteredItems = this.projects
              this.buildPagination()
              this.selectPage(1)
            })
          },
          clearSearchItem(){
            this.searchItem = undefined
            this.searchInTheList('')
          },
          searchInTheList(searchText, currentPage){
            if(_.isUndefined(searchText)){
              this.filteredItems = _.filter(this.projects, function(v, k){
                return !v.selected
              })
            }
            else{
              this.filteredItems = _.filter(this.projects, function(v, k){
                return v.address.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.name.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.owner.toLowerCase().indexOf(searchText.toLowerCase()) > -1
              })
            }

            this.buildPagination()

            if(_.isUndefined(currentPage)){
              this.selectPage(1)
            }
            else{
              this.selectPage(currentPage)
            }
          },
          buildPagination(){
            let numberOfPage = Math.ceil(this.filteredItems.length/this.pagination.itemPerPage)
            this.pagination.items = []
            for(var i=0; i<numberOfPage; i++){
              this.pagination.items.push(i+1)
            }
          },
          selectPage(item) {
            this.pagination.currentPage = item

            let start = 0
            let end = 0
            if(this.pagination.currentPage < this.pagination.range-2){
              start = 1
              end = start+this.pagination.range-1
            }
            else if(this.pagination.currentPage <= this.pagination.items.length && this.pagination.currentPage > this.pagination.items.length - this.pagination.range + 2){
              start = this.pagination.items.length-this.pagination.range+1
              end = this.pagination.items.length
            }
            else{
              start = this.pagination.currentPage-2
              end = this.pagination.currentPage+2
            }
            if(start<1){
              start = 1
            }
            if(end>this.pagination.items.length){
              end = this.pagination.items.length
            }

            this.pagination.filteredItems = []
            for(var i=start; i<=end; i++){
              this.pagination.filteredItems.push(i);
            }
            this.paginatedItems = this.filteredItems.filter((v, k) => {
              return Math.ceil((k+1) / this.pagination.itemPerPage) == this.pagination.currentPage
            })
          },
          selectItem(item){
            if(this.form.project == null || this.form.project != item.id){
              this.form.project = item.id
            }else{
              this.form.project = null
            }
          },
          removeSelectedItem(item){
            this.form.project = null
          }
        },
        validations: {
          form: {
            project: {
              required
            },
          },
        }
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
