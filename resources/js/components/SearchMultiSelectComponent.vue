<template>
    <div>
      <Loader v-if="loader"/>

      <div v-else>
        <transition name="slide-fade">
          <div>
            <div class="input-group mb-2">
              <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                      type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск объекта" aria-describedby="clear">
              <button @click="clearSearchItem" :class="{'disabled': searchItem==''}"
                      class="btn btn-outline-secondary" type="button">Очистить</button>
            </div>
            <p class="small">{{paginatedItems.length}} элемента доступны к поиску</p>
            <div class="border rounded mb-2 p-2 m-tags">
              <span><strong>{{selectedItems.length}}</strong> элементов выбрано</span>
              <div class="m-tags-items">
                <button v-for="(item,index) in selectedItems" type="button" class="btn btn-dark m-1">
                  {{item.title}}
                  <button type="button" class="btn-close btn-close-white" aria-label="Close"  @click="removeSelectedItem(item, index)"></button>
                </button>
              </div>
            </div>
            <div class="d-grid gap-2 my-2">
              <button type="button" class="btn btn-success btn-lg text-white" @click="saveResult">
                Подтвердить выбор
              </button>
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
                      <span @click="selectItem(item)" class="btn btn-light" >
                        Выбрать
                      </span>
                    </td>
                  </tr>
                  <tr v-if="noResult" class="bg-danger">
                    <td class="rounded-start text-white">К сожелению, по результату поиска ничего не найдено... Попробуйте еще.</td>
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
      </div>
    </div>
</template>

<script>
    import SearchPaginationMixin from '../mixins/SearchPagination.mixin.js'
    export default {
      props: ['type', 'oldItems'],
      data() {
          return {
            loader: true,
            selectedItems: [],
          }
      },
      mounted() {
        console.log('Компонент успешно загружен. '+this.type)
        this.pagination.itemPerPage = 5
        this.getData()
      },
      mixins: [SearchPaginationMixin],
      methods: {
        selectOldItems(){
          var app = this
          _.each(this.oldItems, (value, key) => {
            app.selectItem(app.filteredItems.[app.filteredItems.findIndex(i => i.id == value.id || i.id == value)])
          })
        },
        getData(){
          if(this.type === 'projects'){
            this.getProjects()
            this.placeholder = 'Поиск по названию, адрессу и ФИО собтсвенника'
          }else if(this.type === 'project-groups'){
            this.getProjectGroups()
            this.placeholder = 'Поиск по названию группы'
          }else if(this.type === 'users'){
            this.getUserProfiles()
            this.placeholder = 'Поиск'
          }
        },
        getProjects(){
          axios.post('/api/get-projects-for-rate').then((response) =>{
            this.items = response.data

            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            if(this.oldItems){
              this.selectOldItems()
            }
            this.loader = false
          })
        },
        getUserProfiles(){
          axios.post('/api/get-user-profiles').then((response) =>{
            this.items = response.data

            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            if(this.oldItems){
              this.selectOldItems()
            }
            this.loader = false
          })
        },
        getProjectGroups(){
          axios.post('/api/get-project-groups-for-rate').then((response) =>{
            this.items = response.data

            this.filteredItems = this.items
            this.buildPagination()
            this.selectPage(1)

            if(this.oldItems){
              this.selectOldItems()
            }
            this.loader = false
          })
        },
        selectItem(item){
          item.selected = true
          this.selectedItems.push(item)
          this.searchInTheList(this.searchItem, this.pagination.currentPage)
        },
        removeSelectedItem(item, index){
          item.selected = false
          Vue.delete(this.selectedItems, index)
          this.searchInTheList(this.searchItem, this.pagination.currentPage)
        },
        saveResult(){
          this.$emit('selectItems', this.selectedItems);
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
.m-tags-items{
  margin-top: 5px;
  height: 100px;
  overflow-y: scroll;

  .tag{
    margin-bottom: 5px;
    margin-right: 3px;
  }
}
</style>
