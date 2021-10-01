<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <h5 class="offcanvas-title d-flex justify-content-between" id="offcanvasPassLabel">
          <span class="btn btn-info text-white">{{pass.type}}</span>
          <span class="btn btn-primary text-white">{{pass.status}}</span>
        </h5>
        <h5 class="offcanvas-title d-flex justify-content-between align-items-center mt-1" id="offcanvasPassLabel">
          <span class="btn text-white" :class="{'btn-success': pass.status_pay != 'Не оплачен', 'btn-danger': pass.status_pay === 'Не оплачен'}" >{{pass.status_pay}}</span>
          <span class="btn btn-light">{{pass.rate.price}} &#8381; {{pass.rate.type}}</span>
        </h5>
        <p class="text-muted text-end m-1">{{pass.plan}}</p>
        <p v-if="pass.status_pay != 'Оплачен'" class="small text-end">Данная суммая внесена и заморожена в ЛС</p>
        <transition name="slide-fade">
          <div v-if="type === 'info'" class="mt-2 d-print">
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
            </div>
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-if="type === 'comment'" class="mt-2">
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
                <button @click.prevent="addComment()" class="btn btn-primary text-white" type="submit">Оставить свой комментарий</button>
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
          <div v-if="type === 'log'" class="mt-2">

            <p class="h3 my-1"><strong>"{{pass.project.name}}"</strong></p>
            <p v-if="pass.type == 'Временный пропуск'" class="text-muted">Заказчик пропуска: <strong>{{ pass.applicant.fio}}</strong></p>

            <div class="input-group my-1 sticky-top">
              <input  v-model="searchLogs"
                      type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
              <button v-on:click="searchLogs = ''" :class="{'disabled': searchLogs ==''}"
                      class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
            </div>
            <transition-group name="slide-fade">
              <div v-for="log in passLogs" v-bind:key="log.id" class="alert alert-success" role="alert">
                <h4 class="alert-heading">{{log.status}}</h4>
                <p class="mb-0">{{log.description}}</p>
                <hr>
                <p class="mb-0">Автор: <strong>{{log.author}}</strong> ({{log.role}})</p>
                <p class="mb-0">Создан: <strong>{{log.created_at}}</strong></p>
              </div>
            </transition-group>
          </div>
        </transition>

        <div class="d-grid gap-2 my-2 d-print-none">
          <button class="btn btn-primary" type="button" @click="getPass">Обновнить</button>
          <button v-if="type === 'info'" type="button" class="btn btn-dark" @click="print">На печать</button>
        </div>
      </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import moment from 'moment';

    export default {
        props: ['id', 'type'],
        mounted() {
            console.log('Component mounted.')
            this.getPass()
        },
        data() {
          return{
            loader: true,
            pass: [],
            searchLogs: '',
            createCommentMessage: false,
            commentForm:{
              text: '',
              id: this.id,
            }
          }
        },
        methods: {
          getPass(){
            axios.post('/api/get-pass', {id : this.id}).then((response) =>{
              this.pass = response.data
              this.loader = false
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          addComment(){
            if(this.$v.commentForm.$invalid){
              this.$v.commentForm.$touch()
            }else{
              axios.post('/pass/create-comment', this.commentForm).then((response) =>{
                this.getPass()
                this.createCommentMessage = true;
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.createCommentMessage = false
                }, 5000)
                this.commentForm.text=''
                this.$v.commentForm.$reset()
              })
            }
          },
          print(){
            print()
          }
        },
        computed: {
            passLogs() {
              return this.pass.logs.filter(item => item.status.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1 || item.description.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1 || item.author.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1 || item.created_at.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1)
            },
        },
        validations: {
          commentForm: {
            text: {
              required
            },
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
