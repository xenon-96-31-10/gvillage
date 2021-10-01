<template>
  <div class="container-fluid mt-5">
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
    <div class="row flex-nowrap" style="overflow-x: auto; height: 400px">
        <div class="col-sm-4 col-md-2">
          <div class="sticky-top border-5 border-bottom border-danger bg-light">
            <p class="h5 text-center">Заказаны</p>
            
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in expectedpasses" v-bind:key="pass.id" class="card my-2">
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
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="checkIn(pass.id)">Отметить приезд <i class="bi bi-calendar2-check-fill"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteEnd(pass.id)" href="#">Отменить <i class="bi bi-trash-fill"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>
                <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
                <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
                <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>

              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-success bg-light">
            <p class="h5 text-center">На территории</p>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in arrivedpasses" v-bind:key="pass.id" class="card my-2">
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
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="checkOut(pass.id)">Отметить отъезд <i class="bi bi-calendar2-check"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>

                <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
                <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
                <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>
              </div>

            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-primary bg-light">
            <p class="h5 text-center">Отбыли</p>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in departedpasses" v-bind:key="pass.id" class="card my-2">
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
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id)">Повторить <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteFromList(pass.id)">Закрыть пропуск <i class="bi bi-x-circle"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>

                <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
                <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
                <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>
              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-dark bg-light">
            <p class="h5 text-center">Пропущенные</p>
          </div>

          <transition-group name="slide-fade">
            <div v-for="pass in unusedpasses" v-bind:key="pass.id" class="card my-2">
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
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="repeatTemporary(pass.id)">Повторить <i class="bi bi-arrow-counterclockwise"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteFromList(pass.id)">Закрыть пропуск <i class="bi bi-x-circle"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>

                <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
                <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
                <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>
              </div>
            </div>
          </transition-group>
        </div>
        <div class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-info bg-light">
            <p class="h5 text-center">Постоянные</p>
          </div>
          <transition-group name="slide-fade">
            <div v-for="pass in permanentpasses" v-bind:key="pass.id" class="card my-2">
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
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" @click="checkIn(pass.id)">Отметить приезд <i class="bi bi-calendar2-check-fill"></i></a></li>
                      <li><a class="dropdown-item" @click="deleteEnd(pass.id)" href="#">Отменить <i class="bi bi-trash-fill"></i></a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                      <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                    </ul>
                  </div>
                </div>

                <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
                <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
                <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>
              </div>
            </div>
          </transition-group>
        </div>
        <div v-if="stayed"class="col-sm-4 col-md-2">

          <div class="sticky-top border-5 border-bottom border-warning bg-light">
            <p class="h5 text-center">Остались</p>
          </div>

          <div v-for="pass in stayedpasses" v-bind:key="pass.id" class="card my-2">
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
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" @click="checkOut(pass.id)">Отметить отъезд <i class="bi bi-calendar2-check"></i></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'info')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Подробнее <i class="bi bi-window-sidebar"></i></a></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'comment')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >Комментарии <i class="bi bi-chat-left-text"></i></a></li>
                    <li><a class="dropdown-item" @click="getPass(pass.id, 'log')" data-bs-toggle="offcanvas" href="#offcanvasPass" role="button" aria-controls="offcanvasPass" >История <i class="bi bi-clock-history"></i></a></li>
                  </ul>
                </div>
              </div>

              <span v-if="pass.type == 'cars'">{{pass.visitor.number}}</span>
              <span v-if="pass.type == 'profiles'">{{pass.visitor.fio}}</span>
              <span v-if="pass.type == 'mechanizms'">{{pass.visitor.number}}</span>
            </div>
          </div>
        </div>
    </div>
    <div>
      <span class="small text-muted">Дата заказа</span>
      <VueDatePicker @onClose="sortByCalendar"
                      v-model="date" class="mb-2" :min-date="minDate" value="DD.MM.YYYY" format="DD.MM.YYYY" fullscreen-mobile no-header/>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasPass" aria-labelledby="offcanvasPassLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasPassLabel">Пропуск № {{pass.id}}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <h5 class="offcanvas-title d-flex justify-content-between" id="offcanvasPassLabel"><span class="btn btn-info text-white">{{pass.type}}</span><span class="btn btn-success text-white">{{pass.status}}</span></h5>

        <transition name="slide-fade">
          <div v-if="passDataType === 'info'" class="mt-2">
            <transition name="slide-fade">
              <div v-if="pass.visitorType === 'cars'" class="mt-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Модель: <strong>{{ pass.visitor.model}}</strong></li>
                  <li class="list-group-item">Номер: <strong>{{ pass.visitor.number}}</strong></li>
                  <li class="list-group-item">Тип: <strong>{{ pass.visitor.body}}</strong></li>
                  <li class="list-group-item">Цвет: <strong>{{ pass.visitor.color}}</strong></li>
                  <li v-if="pass.owner != null" class="list-group-item">Владелец: <strong>{{ pass.owner.fio}}</strong></li>
                </ul>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-if="pass.visitorType === 'profiles'" class="mt-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">ФИО: <strong>{{ pass.visitor.fio}}</strong></li>
                  <li class="list-group-item">Пол: <strong>{{ pass.visitor.sex}}</strong></li>
                  <li class="list-group-item">Дата рождения: <strong>{{ pass.visitor.dateofbirth}}</strong></li>
                </ul>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-if="pass.visitorType === 'mechanizms'" class="mt-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Модель: <strong>{{ pass.visitor.model}}</strong></li>
                  <li class="list-group-item">Номер: <strong>{{ pass.visitor.number}}</strong></li>
                  <li class="list-group-item">Наименование: <strong>{{ pass.visitor.name}}</strong></li>
                  <li class="list-group-item">Цвет: <strong>{{ pass.visitor.color}}</strong></li>
                </ul>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-if="pass.avatar != null || pass.passport != null" class="row mt-2">
                <div class="col">
                  <img v-if="pass.avatar != 'Фото отсутствует'" :src="pass.avatar" class="img-fluid" alt="Аватар">
                </div>
                <div class="col">
                  <img v-if="pass.passport != 'Паспорт отсутствует'" :src="pass.passport" class="img-fluid" alt="Скан паспорта">
                </div>
              </div>
            </transition>
            <hr>
            <transition name="slide-fade">
              <div class="mt-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Для объекта: <strong>"{{pass.project.name}}"</strong>  ({{pass.project.address}})</li>
                  <li class="list-group-item">Автор: <strong>{{ pass.author}}</strong></li>
                  <li v-if="pass.type == 'Временный пропуск'" class="list-group-item">Заказчик: <strong>{{ pass.applicant.fio}}</strong></li>
                  <li class="list-group-item">Дата создания: <strong>{{ pass.created_at }}</strong></li>
                </ul>
              </div>
            </transition>
            <hr>
            <div v-if="pass.type === 'Временный пропуск'">
              <p class="lead">Дата приезда: {{pass.dates.entry}}</p>
              <p class="lead">Дата отъезда: {{pass.dates.exit}}</p>
            </div>
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-if="passDataType === 'comment'" class="mt-2">
            <transition name="slide-fade">
              <div v-if="createCommentMessage" class="alert alert-success" role="alert">
                 Вы успешно добавили комментарий!
              </div>
            </transition>
            <form class="my-2">
              <div class="form-floating">
                <textarea @blur="$v.commentForm.text.$touch()"
                           :class="status($v.commentForm.text)"
                           v-model="commentForm.text"
                           class="form-control" placeholder="Leave a comment here" id="floatingComment" style="height: 100px"></textarea>
                <label for="floatingComment">Ваш комментарий <i class="bi bi-chat-left-text"></i></label>
                <div class="invalid-feedback" v-if="!$v.commentForm.text.required">Заполните это поле!</div>
              </div>
              <div class="d-grid gap-2  mt-2">
                <button @click.prevent="addComment(pass.id)" class="btn btn-primary text-white" type="submit">Оставить свой комментарий</button>
              </div>
            </form>
            <transition-group name="slide-fade">
              <div v-for="comment in pass.comments" v-bind:key="comment.text" class="alert alert-info" role="alert">
                <p>{{comment.text}}</p>
                <hr>
                <p class="mb-0">Автор: <strong>{{comment.author.profile.fio}}</strong></p>
              </div>
            </transition-group>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="passDataType === 'log'" class="mt-2">

            <p class="h3 my-1"><strong>"{{pass.project.name}}"</strong></p>
            <p v-if="pass.type == 'Временный пропуск'" class="text-muted">Заказчик пропуска: <strong>{{ pass.applicant.fio}}</strong></p>
            <transition-group name="slide-fade">
              <div v-for="log in pass.logs" v-bind:key="log.id" class="alert alert-success" role="alert">
                <h4 class="alert-heading">{{log.status}}</h4>
                <p class="mb-0">{{log.description}}</p>
                <hr>
                <p class="mb-0">Автор: <strong>{{log.author}}</strong> ({{log.role}})</p>
                <p class="mb-0">Создан: <strong>{{log.created_at}}</strong></p>
              </div>
            </transition-group>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import moment from 'moment';
    export default {
        props: ['getfullpasslistRoute', 'getPassRoute', 'checkinRoute','checkoutRoute', 'deletefromlistRoute','deleteRoute', 'repeatRoute','createCommentRoute'],
        mounted() {
            console.log('Компонент загружен')
            this.getLists()
        },
        methods: {
          getLists(){
            var d = moment(this.date, 'YYYY-MM-DD').format('DD.MM.YYYY')
            axios.post(this.getfullpasslistRoute, {date: d}).then((response) =>{
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
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          checkIn(v){
            axios.post(this.checkinRoute, {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
            })
          },
          checkOut(v){
            axios.post(this.checkoutRoute, {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
            })
          },
          deleteFromList(v){
            axios.post(this.deletefromlistRoute, {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
            })
          },
          deleteEnd(v){
            axios.post(this.deleteRoute, {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
            })
          },
          repeatTemporary(v){
            axios.post(this.repeatRoute, {id : v}).then((response) =>{
              console.log(response.data)
              this.getLists()
            })
          },
          getPass(v, type){
            this.passDataType = type
            axios.post(this.getPassRoute, {id : v}).then((response) =>{
              this.pass = response.data
            })
          },
          addComment(v){
            if(this.$v.commentForm.$invalid){
              this.$v.commentForm.$touch()
            }else{
              this.commentForm.id = v
              axios.post(this.createCommentRoute, this.commentForm).then((response) =>{
                this.getPass(v, 'comment')
                this.createCommentMessage = true;
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.createCommentMessage = false
                }, 3000)
                this.commentForm.id = null
                this.commentForm.text=''
                this.$v.commentForm.$reset()
              })
            }
          }
        },
        computed: {

        },
        data() {
          return {
            expectedpasses: [],
            arrivedpasses: [],
            departedpasses: [],
            stayedpasses: [],
            unusedpasses: [],
            permanentpasses: [],
            passDataType: '',
            pass: [],
            stayed: false,
            createCommentMessage: false,
            date: moment(new Date()).format('YYYY-MM-DD'),
            minDate: moment(new Date()).format('YYYY-MM-DD'),
            commentForm:{
              text: '',
              id: null,
            }
          }
        },
        validations: {
          commentForm: {
            text: {
              required
            },
          }
        },
        watch: {

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
