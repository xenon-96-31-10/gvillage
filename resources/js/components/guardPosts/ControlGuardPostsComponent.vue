<template>
    <div>
      <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <h4 class="alert-heading">Добро пожаловать! Это управление охранными постами, которые Вам доступны!</h4>
        <p>
          Вы можете просматривать и редактировать информацию об постах.
        </p>
        <hr>
        <p class="mb-0">В режиме редактирования объекта Вам будут доступны такие функции.</p>
      </div>

      <transition name="slide-fade">
        <div v-if="success" class="alert alert-success" role="alert">
           Вы успешно создали охранный пост!
        </div>
      </transition>
      <Loader v-if="loader"/>
      <div v-else>
        <div v-if="search">
          <div class="input-group mb-2">
            <input  v-model="searchItem" v-on:keyup="searchInTheList(searchItem)"
                    type="text" class="form-control" placeholder="Поиск" aria-label="Поиск объекта" aria-describedby="clear">
                    <button @click="btnPost(null, 'create')"
                            class="btn btn-dark" type="button"><i class="bi bi-plus-square"></i></button>
            <button @click="clearSearchItem" :class="{'disabled': searchItem==''}"
                    class="btn btn-outline-secondary" type="button">Очистить</button>
          </div>
          <p class="small">{{paginatedItems.length}} элемента доступны к поиску</p>
          <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
              <tbody>
                <tr v-for="item in paginatedItems" :key="item.id" class="bg-info">
                  <th class="rounded-start" scope="row">{{item.id}}</th>
                  <td>{{item.title}}</td>
                  <td>+7{{item.description}}</td>
                  <td>{{item.data.fio}}</td>
                  <td class="d-flex justify-content-between align-items-center rounded-end">
                    <button class="btn btn-light" type="button" @click="btnPost(item.id, 'edit')">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-light" type="button" @click="btnPost(item.id, 'show')">
                      <i class="bi bi-eye-fill"></i>
                    </button>
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
        <div v-else-if="create">
          <div class="d-grid gap-2 mb-2">
            <button class="btn btn-dark"  @click="btnPost(null)" type="button">Назад</button>
            <guardpost-create-component @refreshList="refreshList"/>
          </div>
        </div>
        <div v-else-if="show">
          <div class="d-grid gap-2 mb-2">
            <button class="btn btn-dark"  @click="btnPost(null)" type="button">Назад</button>
          </div>
          <guardpost-show-component :guardpost-id="id" :key="id"/>
        </div>
        <div v-else-if="edit">
          <div class="d-grid gap-2 mb-2" >
            <button class="btn btn-dark" @click="btnPost(null)" type="button">Назад</button>
          </div>
          <guardpost-edit-component :guardpost-id="id" :key="id" @refreshList="getGuardPosts"/>
        </div>
      </div>

    </div>
</template>

<script>
    import SearchPaginationMixin from '../../mixins/SearchPagination.mixin.js'
    export default {
        mounted() {
          console.log('Компонент успешно загружен.')
          this.pagination.itemPerPage = 10
          this.getGuardPosts()
        },
        mixins: [SearchPaginationMixin],
        data() {
            return {
              loader: true,
              search: true,
              edit: false,
              show: false,
              create: false,
              success: false,
              id: null,
            }
        },
        methods: {
          getGuardPosts(){
            this.loader = true
            axios.post('/api/get-guard-posts-for-list').then((response) =>{
              this.items = response.data

              this.filteredItems = this.items
              this.buildPagination()
              this.selectPage(1)

              this.loader = false
            })
          },
          btnPost(id, type){
            if(type === 'edit'){
              this.edit = true
              this.create = this.show = this.search = false
            }else if(type === 'show'){
              this.show = true
              this.create =this.edit = this.search = false
            }else if(type === 'create'){
              this.create = true
              this.show = this.edit = this.search = false
            }else{
              this.search = true
              this.create =this.edit = this.show = false
            }
            this.id = id
          },
          refreshList(){
            this.btnPost(null)
            this.getGuardPosts()
            this.success = true
            setTimeout(() => {
              this.success = false
            }, 5000)
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
