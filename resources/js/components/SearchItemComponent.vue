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
              <button v-on:click="clearSearchItem" :class="{'disabled': searchItem==''}"
                      class="btn btn-outline-secondary" type="button">Очистить</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-borderless align-middle" :class="status">
                <tbody>
                  <tr v-for="item in paginatedItems" :key="item.id" :class="statusItem(item.id)" @click="selectItem(item)">
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
                  <tr v-if="noResult" class="bg-danger">
                    <td class="rounded-start text-white h5">К сожелению, по результату поиска ничего не найдено... Попробуйте еще.</td>
                    <td class="text-end rounded-end">
                      <button v-if="add" class="btn btn-light" type="button" @click="search = false">
                        Добавить?
                      </button>
                      <button v-else class="btn btn-light" type="button" @click="clearSearchItem">
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
            <fast-create-profile-component v-if="type==='owner'" :fio="searchItem" @refreshList="refreshList"/>
            <create-car-component v-if="type==='cars'" :project="project" :pass-type="passType" :number="searchItem" @refreshList="refreshList"></create-car-component>
            <fast-create-profile-component v-if="type==='profiles'" :project="project" :fio="searchItem" @refreshList="refreshList"/>
            <create-mechanizm-component v-if="type==='mechanizms'" :project="project" :number="searchItem" @refreshList="refreshList"></create-mechanizm-component>
            <div class="d-grid gap-2 my-3">
              <button @click="search = true" type="button" class="btn btn-dark text-white">Вернуться к поиску</button>
            </div>
          </div>
        </transition>
      </div>
    </div>
</template>

<script>
    import SearchPaginationMixin from '../mixins/SearchPagination.mixin.js'
    export default {
      props: ['type', 'status', 'project', 'passType'],
      data() {
          return {
            createMessage: false,
            loader: true,
            search: true,
            add: true,
            id: null,
          }
      },
      mounted() {
        console.log('Компонент успешно загружен. '+this.type)
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
            this.add = false
            this.loader = false
          })
        },
        getCars(){
          axios.post('/api/get-cars',{id : this.project, type: this.passType}).then((response) =>{
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

            this.loader = false
          })
        },
        getApplicants(){
          if(this.project){
            axios.post('/api/get-applicants',{id : this.project }).then((response) =>{
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
          }
        },
        statusItem(id){
          return {
            'bg-success': id === this.id,
            'bg-info': id != this.id
          }
        },
        selectItem(item){
          if(this.id == null || this.id != item.id){
            this.id = item.id
          }else{
            this.id = null
          }

          this.$emit('checkItem', this.id);
        },
        refreshList(data){
          this.getData()
          this.search = true
          this.noResult = false
          this.id = data.id
          this.searchItem = data.title
          setTimeout(() => {
            this.searchInTheList(data.title)
          }, 1000)
          this.createMessage = true;
          // убрать сообщение о регистрации
          setTimeout(() => {
            this.createMessage = false
          }, 5000)
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
