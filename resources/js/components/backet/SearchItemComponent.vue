<template>
    <div>
      <div v-if="preloader" class="my-5 py-5 d-flex justify-content-center align-items-center">
        <div class="spinner-border"  style="width: 5rem; height: 5rem;" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div v-else>
        <transition name="slide-fade">
          <div v-if="search">
            <div class="input-group mb-1">
              <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                      type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск объекта" aria-describedby="clear">
              <button v-on:click="clearSearchItem" :class="{'disabled': searchItem==''}"
                      class="btn btn-outline-secondary" type="button">Очистить</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-borderless align-middle" :class="status">
                <tbody>
                  <tr v-for="item in paginatedItems" :key="item.id" :class="{ 'bg-success': item.id === id, 'bg-info': item.id != id }" @click="selectItem(item)">
                    <th class="rounded-start" scope="row">{{item.id}}</th>
                    <td>{{item.title}}</td>
                    <td>{{item.description}}</td>
                    <td>{{item.data}}</td>
                    <td class="text-end rounded-end">
                      <span v-if="item.id != id" class="btn btn-light" >
                        Выбрать
                      </span>
                      <span v-else class="btn btn-danger text-white">
                        Отменить
                      </span>
                    </td>
                  </tr>
                  <tr v-if="addNewOffcanvas" class="bg-danger">
                    <td class="rounded-start text-white h5" data-bs-toggle="offcanvas" :data-bs-target="'#'+type" :aria-controls="type">К сожелению, по результату поиска ничего не найдено</td>
                    <td class="text-end rounded-end">
                      <button class="btn btn-light" type="button" data-bs-toggle="offcanvas" :data-bs-target="'#'+type" :aria-controls="type">
                        Добавить?
                      </button>
                    </td>
                  </tr>
                  <tr v-if="addNewCarousel" class="bg-danger">
                    <td class="rounded-start text-white h5" @click="search = false">К сожелению, по результату поиска ничего не найдено</td>
                    <td class="text-end rounded-end">
                      <button class="btn btn-light" type="button" @click="search = false">
                        Добавить?
                      </button>
                    </td>
                  </tr>
                  <tr v-if="noResult" class="bg-danger">
                    <td class="rounded-start text-white h5">К сожелению, по результату поиска ничего не найдено... Попробуйте еще.</td>
                    <td class="text-end rounded-end">
                      <button class="btn btn-light" type="button" @click="clearSearchItem">
                        Очистить
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="!search">
            <div class="d-grid gap-2">
              <button @click="search = true" type="button" class="btn btn-dark text-white">Отменить</button>
              <create-profile-component v-if="type==='owner'" @refreshList="refreshList"></create-profile-component>
            </div>
          </div>
        </transition>
      </div>

      <div class="offcanvas offcanvas-bottom rounded-3 border-0" tabindex="-1" :id="type" :aria-labelledby="type+'Label'">
        <div class="offcanvas-header pb-0">
          <h2 class="offcanvas-title" :id="type+'Label'">Добавление новой информации</h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
          <create-car-component v-if="type==='cars'" @refreshList="refreshList"></create-car-component>
          <create-profile-component v-if="type==='profiles'" @refreshList="refreshList"></create-profile-component>
          <create-mechanizm-component v-if="type==='mechanizms'" @refreshList="refreshList"></create-mechanizm-component>
        </div>
      </div>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'

    import moment from 'moment';
    export default {
        props: ['type', 'status', 'add', 'project'],
        mounted() {
            console.log('Component mounted.')
        },
        created(){
          console.log(this.type)
          setTimeout(() => {
            if(this.add === 'false'){
              this.addNewOff = false
            }
            this.getData()
            this.preloader = false
          }, 3000)

        },
        data() {
            return {
              placeholder: 'Поиск',
              searchItem: '',
              items: [],
              id: 1,
              filteredItems: [],
              paginatedItems: [],
              preloader: true,
              pagination: {
                range: 5,
                currentPage: 1,
                itemPerPage: 2,
                items: [],
                filteredItems: [],
              },
              search: true,
              addNewOff: true,
              id: null,
              addNewOffcanvas: false,
              addNewCarousel: false,
              noResult: false,
            }
        },
        methods: {
          getData(){
            if(this.type === 'projects'){
              this.getProjects()
              this.placeholder = 'Поиск по названию, адрессу и ФИО собтсвенника'
            }else if(this.type === 'cars'){
              this.getCars()
              this.placeholder = 'Поиск по номеру, владельцу и доп. информации'
            }else if(this.type === 'profiles' || this.type === 'owner'){
              this.getProfiles()
              this.placeholder = 'Поиск по ФИО и роли'
            }else if(this.type === 'mechanizms'){
              this.getMechanizms()
              this.placeholder = 'Поиск по номеру, владельцу и доп. информации'
            }else if(this.type === 'applicants'){
              this.getApplicants()
              this.placeholder = 'Поиск по ФИО и роли'
            }
          },
          getProjects(){
            axios.post('/api/get-projects').then((response) =>{
              this.items = response.data
              if(this.items.length < 2){
                this.id = this.items[0].id
                this.$emit('checkItem', this.id);
              }

              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)

            })
          },
          getCars(){
            axios.post('/api/get-cars',{id : this.project}).then((response) =>{
              this.items = response.data

              if(this.items.length < 2){
                this.id = this.items[0].id
                this.$emit('checkItem', this.id);
              }
              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)
            })
          },
          getProfiles(){
            axios.post('/api/get-profiles',{id : this.project}).then((response) =>{
              this.items = response.data

              if(this.items.length < 2){
                this.id = this.items[0].id
                this.$emit('checkItem', this.id);
              }
              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)
            })
          },
          getMechanizms(){
            axios.post('/api/get-mechanizms',{id : this.project}).then((response) =>{
              this.items = response.data

              if(this.items.length < 2){
                this.id = this.items[0].id
                this.$emit('checkItem', this.id);
              }
              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)
            })
          },
          getApplicants(){
            axios.post('/api/get-applicants',{id : this.project}).then((response) =>{
              this.items = response.data

              if(this.items.length < 2){
                this.id = this.items[0].id
                this.$emit('checkItem', this.id);
              }
              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)
            })
          },
          clearSearchItem(){
            this.searchItem = undefined
            this.searchInTheList('')
            this.addNewOffcanvas = false
            this.addNewCarousel = false
            this.noResult = false
          },
          searchInTheList(searchText, currentPage){
            this.addNewOffcanvas = false
            this.addNewCarousel = false
            this.noResult = false
            if(_.isUndefined(searchText)){
              this.filteredItems = _.filter(this.items, function(v, k){
                return !v.selected
              })
            }
            else{
              this.filteredItems = _.filter(this.items, function(v, k){
                return v.title.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.description.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.data.toLowerCase().indexOf(searchText.toLowerCase()) > -1
              })
            }

            if(this.filteredItems.length === 0 && this.addNewOff && this.type != 'projects' && this.type != 'applicants'){
              this.addNewOffcanvas = true
            }else if(this.filteredItems.length === 0 && !this.addNewOff && this.type != 'projects' && this.type != 'applicants'){
              this.addNewCarousel = true
            }else if(this.filteredItems.length === 0 && (this.type === 'projects' || this.type === 'applicants')){
              this.noResult = true
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
            if(this.id == null || this.id != item.id){
              this.id = item.id
            }else{
              this.id = null
            }

            this.$emit('checkItem', this.id);
          },
          refreshList(id){
            this.getData()
            this.search = true
            this.addNewOffcanvas = false
            this.addNewCarousel = false
            this.noResult = false
            this.searchItem = ''
            this.id = id
            this.$emit('checkItem', this.id);
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
