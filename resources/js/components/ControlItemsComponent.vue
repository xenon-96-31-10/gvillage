<template>
    <div>
      <Loader v-if="loader"/>

      <div v-else>
        <transition name="slide-fade">
          <div v-if="createMessage" class="alert alert-success" role="alert">
             Вы успешно добавили!
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-if="search">
            <div class="input-group mb-1">
              <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                      type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск объекта" aria-describedby="clear">
              <button @click="btnShow('create')"
                      class="btn btn-primary text-white" type="button"><i class="bi bi-plus-circle"></i></button>
              <button v-on:click="clearSearchItem" :class="{'disabled': searchItem==''}"
                      class="btn btn-outline-secondary" type="button">Очистить</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-borderless align-middle">
                <tbody>
                  <tr v-for="item in paginatedItems" :key="item.id" class="bg-info">
                    <th class="rounded-start" scope="row">{{item.id}}</th>
                    <td>{{item.title}}</td>
                    <td>{{item.description}}</td>
                    <td>{{item.data}}</td>
                    <td class="text-end rounded-end">
                      <button class="btn btn-light" type="button" @click="btnShow('edit', item.id)">
                        Редактировать <i class="bi bi-pencil-square"></i>
                      </button>
                    </td>
                  </tr>
                  <tr v-if="noResult" class="bg-danger">
                    <td class="rounded-start text-white h5">К сожелению, по результату поиска ничего не найдено... Попробуйте еще.</td>
                    <td class="text-end rounded-end">
                      <button class="btn btn-light" type="button" @click="btnShow('create')">
                        Добавить?
                      </button>
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
          <div v-if="create">
            <fast-create-profile-component v-if="type==='owner'" @refreshList="refreshList"/>
            <create-car-component v-if="type==='cars'" :project="project" :pass-type="passType" :number="searchItem" @refreshList="refreshList"></create-car-component>
            <fast-create-profile-component v-if="type==='profiles'" :project="project" :fio="searchItem" @refreshList="refreshList"/>
            <create-mechanizm-component v-if="type==='mechanizms'" :project="project" :number="searchItem" @refreshList="refreshList"></create-mechanizm-component>
            <div class="d-grid gap-2 my-3">
              <button @click="btnShow('search')" type="button" class="btn btn-dark text-white">Вернуться к поиску</button>
            </div>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="edit">
            <edit-car-component v-if="type==='cars'" :id="this.id" :key="this.id" @refreshList="refreshList"/>
            <edit-mechanizm-component v-if="type==='mechanizms'" :id="this.id" :key="this.id" @refreshList="refreshList"/>
            <div class="d-grid gap-2">
              <button @click="btnShow('search')" type="button" class="btn btn-dark text-white">Вернуться к поиску</button>
            </div>
          </div>
        </transition>
      </div>
    </div>
</template>

<script>
    import SearchPaginationMixin from '../mixins/SearchPagination.mixin.js'
    export default {
      props: ['type'],
      data() {
          return {
            createMessage: false,
            loader: true,
            search: true,
            create: false,
            edit: false,
            id: null,
          }
      },
      mounted() {
        console.log('Компонент успешно загружен. '+this.type)
        this.pagination.itemPerPage = 6
        this.getData()
      },
      mixins: [SearchPaginationMixin],
      methods: {
        getData(){
          if(this.type === 'projects'){
            this.getProjects()
            this.placeholder = 'Поиск по названию, адрессу и ФИО собственника'
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
            this.loader = false
          })
        },
        getCars(){
          axios.post('/api/get-cars').then((response) =>{
            this.items = response.data

            if(this.items.length < 2){
              this.id = this.items[0].id
              this.$emit('checkItem', this.id);
            }
            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            this.loader = false
          })
        },
        getProfiles(){
          axios.post('/api/get-profiles',{id : this.project}).then((response) =>{
            this.items = response.data
            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            this.loader = false
          })
        },
        getMechanizms(){
          axios.post('/api/get-mechanizms',{id : this.project}).then((response) =>{
            this.items = response.data
            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            this.loader = false
          })
        },
        getApplicants(){
          if(this.project){
            axios.post('/api/get-applicants',{id : this.project }).then((response) =>{
              this.items = response.data
              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)

              this.loader = false
            })
          }
        },
        btnShow(type, id){
          if(type == 'search'){
            this.search = true
            this.create = this.edit = false
          }else if(type === 'create'){
            this.create = true
            this.search = this.edit = false
          }else{
            this.edit = true
            this.search = this.create = false
            this.id = id
          }
        },
        refreshList(data){
          if(this.create === true){
            this.btnShow('search')
            this.id = null
            this.createMessage = true;
            // убрать сообщение о регистрации
            setTimeout(() => {
              this.createMessage = false
            }, 5000)
          }
          this.getData()
          this.noResult = false
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
