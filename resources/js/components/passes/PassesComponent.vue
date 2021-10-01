<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <div class="form-check">
        <input v-model="stayed" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          Показать "Оставшиеся на территории"
        </label>
      </div>
      <button type="button" @click="getLists()" class="btn btn-secondary text-white m-1">
        <i class="bi bi-arrow-counterclockwise"></i>
      </button>
    </div>
    <transition name="slide-fade">
      <div v-if="errors">
        <div class="container">
          <div v-for="err in errors">
            <div class="d-grid gap-2 mb-2 col-12 col-sm-4 mx-auto alert alert-danger mt-2 mb-1" role="alert">
              {{err.text}}
            </div>
            <div class="d-grid gap-2 mb-2 col-12 col-sm-4 mx-auto" v-if="err.id">
              <button class="btn btn-dark" type="button" @click="errors = []">Отменить</button>
            </div>
            <div class="d-grid gap-2 mb-2 col-12 col-sm-4 mx-auto" v-if="err.id">
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#updateAccount" aria-controls="updateAccount">Пополнить</button>
            </div>
            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="updateAccount" aria-labelledby="updateAccountLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="updateAccountLabel">Пополнение счета для {{err.fio}}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body small">
                <refill-account-component :id-account="err.id" :id-project="err.project_id" :amount="err.amount" :key="err.id" @refreshList="gotMoney"></refill-account-component>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div class="row flex-nowrap" style="overflow-x: auto; height: 400px">
        <div class="col-sm-4 col-md-2">
          <div class="sticky-top border-5 border-bottom border-danger bg-light">
            <p class="h5 text-center">Заказаны</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchExpected"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchExpected = ''" :class="{'disabled': searchExpected ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in expectedPasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" style="z-index: 1090;">
                      <li><a class="dropdown-item" @click="checkIn(pass.id)">Отметить приезд <i class="bi bi-calendar2-check-fill"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteEnd(pass.id)" href="#">Отменить <i class="bi bi-trash-fill"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span>{{pass.visitor}}</span>

              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-success bg-light">
            <p class="h5 text-center">На территории</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchArrived"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchArrived = ''" :class="{'disabled': searchArrived ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in arrivedPasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" style="z-index: 1090;">
                      <li><a class="dropdown-item" @click="checkOut(pass.id)">Отметить отъезд <i class="bi bi-calendar2-check"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span>{{pass.visitor}}</span>

              </div>

            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-primary bg-light">
            <p class="h5 text-center">Отбыли</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchDeparted"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchDeparted = ''" :class="{'disabled': searchDeparted ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in departedPasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" style="z-index: 1090;">
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id, 'today')">Повторить на сегодня <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id, 'tommorow')">Повторить на завтра <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteFromList(pass.id)">Закрыть пропуск <i class="bi bi-x-circle"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span>{{pass.visitor}}</span>

              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-dark bg-light">
            <p class="h5 text-center">Пропущенные</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchUnused"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchUnused = ''" :class="{'disabled': searchUnused ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in unusedPasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" style="z-index: 1090;">
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id, 'today')">Повторить на сегодня <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id, 'tommorow')">Повторить на завтра <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteFromList(pass.id)">Закрыть пропуск <i class="bi bi-x-circle"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span>{{pass.visitor}}</span>

              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-info bg-light">
            <p class="h5 text-center">Постоянные</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchPermanent"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchPermanent = ''" :class="{'disabled': searchPermanent ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in permanentPasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" style="z-index: 1090;">
                      <li v-if="pass.status === 'Ожидает приезда'"><a class="dropdown-item" @click="checkIn(pass.id)">Отметить приезд <i class="bi bi-calendar2-check-fill"></i></a></li>
                      <li v-else><a class="dropdown-item" @click="checkOut(pass.id)">Отметить отъезд <i class="bi bi-calendar2-check"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteEnd(pass.id)" href="#">Отменить <i class="bi bi-trash-fill"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span>{{pass.visitor}}</span>

              </div>
            </div>
          </transition-group>
        </div>
        <div v-if="stayed" class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-warning bg-light">
            <p class="h5 text-center">Остались</p>
          </div>
          <div class="input-group my-1">
            <input  v-model="searchStayed"
                    type="text" class="form-control" :placeholder="placeholder" aria-label="Поиск" aria-describedby="clear">
            <button v-on:click="searchStayed = ''" :class="{'disabled': searchStayed ==''}"
                    class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
          </div>

          <div v-for="pass in stayedPasses" v-bind:key="pass.id" class="card my-2">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                  <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i></span>
                  <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i></span>
                  <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i></span>
                </h5>
                <div class="btn-group">
                  <button type="button" class="btn btn-secondary rounded btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                  </button>
                  <ul class="dropdown-menu" style="z-index: 1090;">
                    <li><a class="dropdown-item" @click="checkOut(pass.id)">Отметить отъезд <i class="bi bi-calendar2-check"></i></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                  </ul>
                </div>
              </div>
              <span>{{pass.visitor}}</span>

            </div>
          </div>
        </div>
    </div>


    <transition name="slide-fade">
      <div class="toast-container position-absolute p-3 bottom-0 end-0 m-3 translate-middle-y" id="toastPlacement" data-original-class="toast-container position-absolute p-3">
        <div class="toast align-items-center text-white bg-success border-0"  id="createPassMessage" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              Успешно!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
        <div class="toast align-items-center text-white bg-success border-0"  id="checkinPassMessage" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              Приезд зафиксирован и тариф по пропуску учтен!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
        <div class="toast align-items-center text-white bg-danger border-0"  id="errorPassMessage" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              Ошибка!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </transition>

    <div class="position-absolute bottom-0 start-0 p-2">
      <span class="small text-muted">Дата заказа</span>
      <VueDatePicker @onClose="sortByCalendar"
                      v-model="date" class="mb-2" :min-date="minDate" value="DD.MM.YYYY" format="DD.MM.YYYY" fullscreen-mobile no-header/>
    </div>
    <div class="position-absolute bottom-0 end-0">
      <button type="button" class="btn btn-info btn-lg text-white m-3" data-bs-toggle="modal" data-bs-target="#createTemporaryPass">
        <i class="bi bi-plus-circle-dotted"></i>
      </button>
    </div>
    <div class="modal fade" id="createTemporaryPass" tabindex="-1" aria-labelledby="createTemporaryPassLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createTemporaryPassLabel">Создание временного пропуска</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <create-pass-component type="temporary" @refreshList="refreshList"></create-pass-component>
          </div>
        </div>
      </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasPass" aria-labelledby="offcanvasPassLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasPassLabel">Пропуск № {{id}}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <pass-component v-if="id != null" :id="id" :type="type" :key="generateKey(id, type)"/>
      </div>
    </div>
  </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import moment from 'moment';
    var Modal, myModal, Toast, cToast, eToast, successToast, checkinToast, errorToast
    function initModal(){
      Modal = document.getElementById('createTemporaryPass')
      myModal = new bootstrap.Modal(Modal)
    }

    function initToast(){
      Toast = document.getElementById('createPassMessage')
      successToast = new bootstrap.Toast(Toast)
      cToast = document.getElementById('checkinPassMessage')
      checkinToast = new bootstrap.Toast(cToast)
      eToast = document.getElementById('errorPassMessage')
      errorToast = new bootstrap.Toast(eToast)
    }

    export default {
        mounted() {
            console.log('Компонент загружен')
            this.getLists()
            initModal()
            initToast()
        },
        methods: {
          getLists(){
            var d = moment(this.date, 'YYYY-MM-DD').format('DD.MM.YYYY')
            axios.post('/api/get-full-pass-list', {date: d}).then((response) =>{
              this.expectedpasses = response.data.expectedpasses
              this.arrivedpasses = response.data.arrivedpasses
              this.departedpasses = response.data.departedpasses
              this.stayedpasses = response.data.stayedpasses
              this.unusedpasses = response.data.unusedpasses
              this.permanentpasses = response.data.permanentpasses
            })
          },
          sortByCalendar(){
            this.getLists()
          },
          checkIn(v){
            axios.post('/pass/checkin', {id : v}).then((response) =>{
              this.getLists()
              checkinToast.show()
            }).catch((error) =>{
                this.errors = error.response.data.errors
            })
          },
          checkOut(v){
            axios.post('/pass/checkout', {id : v}).then((response) =>{
              this.getLists()
              successToast.show()
            })
          },
          deleteFromList(v){
            axios.post('/pass/delete-from-list', {id : v}).then((response) =>{
              this.getLists()
              successToast.show()
            })
          },
          deleteEnd(v){
            axios.post('/pass/delete', {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
              successToast.show()
            })
          },
          repeatTemporary(id, type){
            this.repeatPass = id
            this.repeatPassType = type
            axios.post('/pass/repeat-temporary', {id, type}).then((response) =>{
              console.log(response.data)
              this.getLists()
              successToast.show()
            }).catch((error) =>{
                this.errors = error.response.data.errors
                this.repeat = true
            })
          },
          refreshList(){
            this.getLists()
            myModal.toggle()
            successToast.show()

            setTimeout(() => {
              this.createPassMessage = false
            }, 5000)
          },
          generateKey(item, index) {
            const uniqueKey = `${item}-${index}`;
            return uniqueKey;
          },
          getPass(id,type){
            this.id = id
            this.type = type
          },
          gotMoney(){
            var myOffcanvas = document.getElementById('updateAccount')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            bsOffcanvas.show()
            bsOffcanvas.hide()
            this.errors = []
            successToast.show()
            if(this.repeat){
              this.repeatTemporary(this.repeatPass, this.repeatPassType)
              this.repeat = false
            }
          }
        },
        computed: {
          expectedPasses() {
            return this.expectedpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchExpected.toLowerCase()) !== -1)
          },
          arrivedPasses() {
            return this.arrivedpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchArrived.toLowerCase()) !== -1)
          },
          departedPasses() {
            return this.departedpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchDeparted.toLowerCase()) !== -1)
          },
          stayedPasses() {
            return this.stayedpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchStayed.toLowerCase()) !== -1)
          },
          unusedPasses() {
            return this.unusedpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchUnused.toLowerCase()) !== -1)
          },
          permanentPasses() {
            return this.permanentpasses.filter(item => item.visitor.toLowerCase().indexOf(this.searchPermanent.toLowerCase()) !== -1)
          },
        },
        data() {
          return {
            placeholder: 'Поиск',
            searchExpected: '',
            searchArrived: '',
            searchDeparted: '',
            searchStayed: '',
            searchUnused: '',
            searchPermanent: '',
            expectedpasses: [],
            arrivedpasses: [],
            departedpasses: [],
            stayedpasses: [],
            unusedpasses: [],
            permanentpasses: [],
            errors: [],
            repeatPass: null,
            repeatPassType: '',
            repeat: false,
            stayed: false,
            id: null,
            type: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            minDate: moment(new Date()).format('YYYY-MM-DD'),
          }
        }
    }
</script>
<style scoped>
.slide-fade-enter-active {
  transition-group: all 0.5s ease;
}

.slide-fade-leave-active {
  transition-group: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}
.offcanvas-bottom{
  height: 40vh!important;
}
</style>
