<template>
    <div class="my-3">
      <div v-if="search">
        <div class="form-group">
          <div class="input-group mb-1">
            <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                    type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="clearSearchItem" :class="{'disabled': searchItem==''}"
                    class="btn btn-outline-secondary" type="button" id="clear">Очистить</button>
                    <button @click="reloadList" class="btn btn-info text-white" type="button"><i class="bi bi-arrow-counterclockwise"></i></button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">ФИО</th>
                <th scope="col">ЛС</th>
                <th scope="col">Организация</th>
                <th scope="col">Роль</th>
                <th scope="col">Статус</th>
                <th scope="col">Действия</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in paginatedItems" class="bg-info">
                <th class="rounded-start" scope="row">{{item.id}}</th>
                <td>{{item.fio}}</td>
                <td>{{item.account}}</td>
                <td>{{item.organization}}</td>
                <td>{{item.role}}</td>
                <td>{{item.status}}</td>
                <td class="d-flex justify-content-between align-items-center rounded-end">
                  <button class="btn btn-light" type="button" @click="showProfile(item.id)">
                    <i class="bi bi-eye-fill"></i>
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

      <div v-else>
        <div class="d-grid gap-2 mb-2" @click="search = true">
          <button class="btn btn-dark" type="button">Назад</button>
        </div>
        <profile-show-component :profile-id="id" :key="id"/>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        created(){
          this.getProfiles()
        },
        methods: {
          getProfiles(){
            axios.post('/api/get-available-profiles').then((response) =>{
              this.items = response.data

              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)
            })
          },
          showProfile(id){
            this.id = id
            this.search = false
          },
          reloadList(){
            this.clearSearchItem()
            this.getProfiles()
          },
          clearSearchItem(){
            this.searchItem = undefined
            this.searchInTheList('')
          },
          searchInTheList(searchText, currentPage){
            if(_.isUndefined(searchText)){
              this.filteredItems = _.filter(this.items, function(v, k){
                return !v.selected
              })
            }
            else{
              this.filteredItems = _.filter(this.items, function(v, k){
                return v.fio.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.account.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.organization.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.role.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.status.toLowerCase().indexOf(searchText.toLowerCase()) > -1
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
            console.log(this.paginatedItems)
            this.paginatedItems = this.filteredItems.filter((v, k) => {
              return Math.ceil((k+1) / this.pagination.itemPerPage) == this.pagination.currentPage
            })
          }
        },
        data() {
          return {
            searchItem: '',
            items: [],
            id: 1,
            filteredItems: [],
            paginatedItems: [],
            search: true,
            pagination: {
              range: 5,
              currentPage: 1,
              itemPerPage: 10,
              items: [],
              filteredItems: [],
            }
          }
        },
    }
</script>
<style scope>
.offcanvas-bottom {
    right: 0;
    left: 0;
    height: 100vh;
    max-height: 100%;
    border-top: 1px solid rgba(0, 0, 0, 0.2);
    transform: translateY(100%);
}
.table{
  border-collapse: separate!important;
  border-spacing: 0 5px;
  cursor: pointer;
}
</style>
