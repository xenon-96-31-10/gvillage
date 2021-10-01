<template>
  <div class="container-fluid">
    <div class="row flex-nowrap" style="overflow-x: auto; height: 400px">

        <div class="col-md-4">

          <div class="sticky-top border-5 border-bottom border-success bg-light">
            <p class="h3 text-center">Временные пропуски</p>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in temporarypasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i> {{pass.visitor.number}}</span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i> {{pass.visitor.number}}</span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>

            </div>
          </transition-group>
        </div>

        <div class="col-md-4">
          <div class="sticky-top border-5 border-bottom border-danger bg-light">
            <p class="h3 text-center">Физ лица</p>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in humanpasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i> {{pass.visitor.fio}}</span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </transition-group>
        </div>

        <div class="col-md-4">

          <div class="sticky-top border-5 border-bottom border-info bg-light">
            <p class="h3 text-center">Постоянные</p>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in permanentpasses" v-bind:key="pass.id" class="card my-2">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">
                    <span v-if="pass.type == 'cars'"><i class="bi bi-truck"></i> {{pass.visitor.number}}</span>
                    <span v-if="pass.type == 'profiles'"><i class="bi bi-person-badge"></i> {{pass.visitor.fio}}</span>
                    <span v-if="pass.type == 'mechanizms'"><i class="bi bi-truck-flatbed"></i> {{pass.visitor.number}}</span>
                  </h5>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary rounded" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </transition-group>
        </div>

    </div>

    <div class="position-absolute bottom-0 start-0 p-2">
      <div v-if="role == 'owner'"class="form-check">
        <input v-model="own" @change="getLists()" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          Показать только мои пропуска
        </label>
      </div>
      <div>
        <VueDatePicker @onClose="sortByCalendar" v-model="date" value="DD.MM.YYYY" format="DD.MM.YYYY" fullscreen-mobile no-header/>
      </div>
    </div>
    <div class="position-absolute bottom-0 end-0">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportModal">
        Отчет в цифрах
      </button>
      <button type="button" @click="getLists()" class="btn btn-info text-white m-3">
        <i class="bi bi-arrow-counterclockwise"></i>
      </button>
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

    <div class="modal fade d-print" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportModalLabel">Отчет в цифрах за {{Data}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              <Loader v-if="loader"/>
              <div v-else class="table-responsive">
                <table class="table table-hover">
                  <caption class="mb-0">Всего на сегодня пропусков: {{passData.temporary.counter}}</caption>
                  <caption class="mb-0">Оплачено: {{passData.temporary.pay.count}} (бесплатных: {{passData.temporary.pay.free}}) на {{passData.temporary.pay.price}} &#8381;</caption>
                  <caption class="mb-0">Не оплачено: {{passData.temporary.unpay.count}} на {{passData.temporary.unpay.price}} &#8381;</caption>
                  <thead>
                    <tr>
                      <th scope="col">Наименование</th>
                      <th scope="col">Автомобили</th>
                      <th scope="col">Физ. Лица</th>
                      <th scope="col">Техника</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="data in passData">
                      <th scope="row">{{data.title}}</th>
                      <td>{{data.cars}}</td>
                      <td>{{data.profiles}}</td>
                      <td>{{data.mechanizms}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer d-print-none">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary" @click="print">На печать</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import moment from 'moment';
    export default {
        mounted() {
            console.log('Компонент загружен')
            this.getLists()
        },
        created() {
          this.getRole()
        },
        methods: {
          getRole(){
            axios.post('/api/get-current-role').then((response) =>{
              this.role = response.data
            })
          },
          getLists(){
            var d = moment(this.date, 'YYYY-MM-DD').format('DD.MM.YYYY')
            axios.post('/api/get-report-pass-list', {date: d, own: this.own}).then((response) =>{
              this.temporarypasses = response.data.temporarypasses
              this.humanpasses = response.data.humanpasses
              this.permanentpasses = response.data.permanentpasses
              this.passData = response.data.passData
              this.loader = false
            })
          },
          sortByCalendar(){
            this.getLists()
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          getPass(id,type){
            this.id = id
            this.type = type
          },
          generateKey(item, index) {
            const uniqueKey = `${item}-${index}`;
            return uniqueKey;
          },
          print(){
            print()
          }
        },
        computed: {
          Data(){
            return moment(this.date, 'YYYY-MM-DD').format('DD.MM.YYYY')
          }
        },
        data() {
          return {
            loader: true,
            role: '',
            temporarypasses: [],
            humanpasses: [],
            permanentpasses: [],
            passData: [],
            id: null,
            type: '',
            own: false,
            date: moment(new Date()).format('YYYY-MM-DD'),
            minDate: moment(new Date()).format('YYYY-MM-DD'),
          }
        },
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
</style>
