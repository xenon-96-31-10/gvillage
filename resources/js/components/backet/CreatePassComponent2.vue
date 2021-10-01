<template>
    <div>
      <transition name="slide-fade">
        <div v-if="createMessage" class="alert alert-success" role="alert">
           <h4 class="alert-heading">Вы успешно создали пропуск!</h4>
           <p>Теперь Вы можете создать еще пропуск или вернуться в главное меню</p>
            <hr>
            <div class="d-grid gap-2 d-md-block">
              <button class="btn btn-primary text-white" type="button" @click="succesMessage('back')">Вернуться в панель управления</button>
              <button class="btn btn-secondary" type="button" @click="succesMessage('repeat')">Создать еще пропуск</button>
            </div>
        </div>
      </transition>
      <transition name="slide-fade">
        <div v-if="createRequest" class="alert alert-success" role="alert">
           <h4 class="alert-heading">Ваша заявка на пропуск успешно оформлена!</h4>
           <p>В скором времени наш специалист проверит ее и подвердит. А теперь Вы можете заказать еще пропуск или вернуться в главное меню</p>
            <hr>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
              <button class="btn btn-primary text-white" type="button" @click="succesMessage('back')">Вернуться в панель управления</button>
              <button class="btn btn-secondary" type="button" @click="succesMessage('repeat')">Создать еще пропуск</button>
            </div>
        </div>
      </transition>
      <form v-if="createMessage != true && createRequest != true" enctype="multipart/form-data" novalidate>
        <transition name="slide-fade">
          <div v-if="errors">
            <div v-for="err in errors" class="alert alert-danger" role="alert">
              {{err.text}}
              <br>
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#updateAccount" aria-controls="updateAccount">Пополнить</button>
              <div class="offcanvas offcanvas-bottom" tabindex="-1" id="updateAccount" aria-labelledby="updateAccountLabel">
                <div class="offcanvas-header">
                  <h5 class="offcanvas-title" id="updateAccountLabel">Пополнение счета для {{err.fio}}</h5>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body small">
                  <refill-account-component :idAccount="err.id" :key="err.id"></refill-account-component>
                </div>
              </div>
            </div>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-show="step === 1">
            <h3><i v-if="$v.form.project.$invalid && $v.form.project.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.project.$invalid" class="bi bi-check-circle-fill text-success"></i> Шаг 1. Выберите объект, на который оформляется пропуск</h3>
            <hr>
            <search-item-component type="projects" :key="create" :status="status($v.form.project)" @checkItem="selectProject"></search-item-component>
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-show="step === 2">
            <h3><i v-if="$v.form.visitor.type.$invalid && $v.form.visitor.type.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.visitor.type.$invalid" class="bi bi-check-circle-fill text-success"></i> Шаг 2. Выберите тип посетителя</h3>
            <hr>
            <div class="btn-group mb-3 d-flex justify-content-center" :class="status($v.form.visitor)" role="group" aria-label="Basic radio toggle button group">
              <input @blur="$v.form.visitor.type.$touch()" type="radio" class="btn-check" v-model="form.visitor.type" value="cars" id="visitor-car" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-car">Автомобиль <i class="bi bi-truck"></i></label>

              <input @blur="$v.form.visitor.type.$touch()"  type="radio" class="btn-check" v-model="form.visitor.type" value="profiles" id="visitor-human" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-human">Человек <i class="bi bi-person-square"></i></label>

              <input v-if="type != 'permanent'" @blur="$v.form.visitor.type.$touch()"  type="radio" class="btn-check" v-model="form.visitor.type" value="mechanizms" id="visitor-mechanizm" autocomplete="off">
              <label v-if="type != 'permanent'" class="btn btn-sm-lg btn-outline-dark" for="visitor-mechanizm">Техника <i class="bi bi-truck-flatbed"></i></label>
            </div>
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-show="step === 3">
            <h3><i v-if="$v.form.visitor.id.$invalid && $v.form.visitor.id.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.visitor.id.$invalid" class="bi bi-check-circle-fill text-success"></i> Шаг 3. Выберите посетителя</h3>
            <hr>
            <search-item-component :type="form.visitor.type" :key="form.visitor.type" :status="status($v.form.visitor.id)" @checkItem="selectVisitorId"></search-item-component>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="step === 4">
            <h3><i v-if="$v.form.applicant.$invalid && $v.form.applicant.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.applicant.$invalid" class="bi bi-check-circle-fill text-success"></i> Шаг 4. Укажите закачика пропуска</h3>
            <hr>
            <div class="mb-3" v-if="role != 'owner'">
              <div class="d-flex justify-content-between mb-3">
                <h4>Список пользователей выбранного проекта:</h4>
                <div>
                  <input type="checkbox" class="btn-check" id="btn-check" @click="applicantMe" autocomplete="off">
                  <label class="btn btn-info text-white" for="btn-check"><span v-if="applicantNotMe">Я заказчик</span><span v-if="!applicantNotMe">Отменить</span></label>
                </div>
              </div>
              <div v-if="!applicantNotMe" class="alert alert-success" role="alert">
                <strong>Вы заказчик пропуска! Можете просто продолжить</strong>
              </div>
              <search-item-component v-if="applicantNotMe" type="applicants" :project="form.project" :key="form.project" :status="status($v.form.applicant)" @checkItem="selectApplicantId"></search-item-component>
            </div>
            <div v-else>
              <div class="alert alert-success" role="alert">
                <strong>Вы заказчик пропуска! Можете просто продолжить</strong>
              </div>
            </div>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="step === 4">
            <h3><i v-if="$v.form.applicant.$invalid && $v.form.applicant.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.applicant.$invalid" class="bi bi-check-circle-fill text-success"></i> Укажите закачика пропуска</h3>
            <hr>
            <div class="mb-3" v-if="request">
              <div class="alert alert-success" role="alert">
                <strong>Вы заказчик пропуска! Можете просто продолжить</strong>
              </div>
            </div>
            <div v-else>
              <div class="d-flex justify-content-between mb-3">
                <h4>Список пользователей выбранного проекта:</h4>
                <div>
                  <input type="checkbox" class="btn-check" id="btn-check" @click="applicantMe" autocomplete="off">
                  <label class="btn btn-info text-white" for="btn-check"><span v-if="applicantNotMe">Я заказчик</span><span v-if="!applicantNotMe">Отменить</span></label>
                </div>
              </div>
              <div v-show="!applicantNotMe" class="alert alert-success" role="alert">
                <strong>Вы заказчик пропуска! Можете просто продолжить</strong>
              </div>
              <div v-show="applicantNotMe">
                <search-item-component type="applicants" :key="form.project" :project="form.project" :status="status($v.form.applicant)" @checkItem="selectApplicantId"></search-item-component>
              </div>
            </div>
          </div>
        </transition>


        <transition name="slide-fade">
          <div v-show="step === 4">
            <h3><i v-if="$v.form.applicant.$invalid && $v.form.applicant.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.applicant.$invalid" class="bi bi-check-circle-fill text-success"></i> Укажите закачика пропуска</h3>
            <hr>
            <search-item-component type="applicants" :key="form.project" :project="form.project" :status="status($v.form.applicant)" @checkItem="selectApplicantId"></search-item-component>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="step === 5">
            <h3><i v-if="$v.form.rate.$invalid && $v.form.rate.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.rate.$invalid" class="bi bi-check-circle-fill text-success"></i> Шаг 5. Дополнительная информация</h3>
            <hr>
            <div class="mb-3" v-if="type === 'temporary'">
              <div class="form-floating mb-2">
                <VueDatePicker v-model="form.temporary.entry" class="form-control mb-2" :min-date="new Date()" value="DD.MM.YYYY" format="DD.MM.YYYY" id="date" no-header/>
                <label for="date" class="h5">Дата приезда</label>
              </div>
            </div>
            <div class="mb-3" v-if="type === 'permanent'">
              <div class="mb-3">
                <Multiselect
                    v-model="weeksday.value"
                    v-bind="weeksday"
                  ></Multiselect>
              </div>
              <div class="row g-2">
                <h5>Укажите период действия пропуска (не указав дату "до" - пропуск считается безсрочным)</h5>
                <div class="col-sm">
                  <div class="form-floating mb-2">
                    <VueDatePicker v-model="form.permanent.entry" class="form-control mb-2" :min-date="new Date()" value="DD.MM.YYYY" format="DD.MM.YYYY" id="entry" no-header/>
                    <label for="entry" class="h5">От</label>
                  </div>
                </div>
                <div class="col-sm">
                  <div class="form-floating mb-2">
                    <VueDatePicker v-model="form.permanent.exit" class="form-control mb-2" :min-date="form.permanent.entry" value="DD.MM.YYYY" format="DD.MM.YYYY" id="exit" no-header/>
                    <label for="exit" class="h5">До</label>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="role != 'owner'">
              <h5>Выберите тариф для пропуска:</h5>
              <div class="row g-2 mb-3 justify-content-center" style="overflow-x: auto; height: 150px">
                <div v-for="rate in rates" class="col-auto" >
                  <div class="d-grid gap-2">
                    <input  @blur="$v.form.rate.$touch()"
                            v-model="form.rate"
                            :value="rate.id"
                            type="radio" class="btn-check" name="btnradio" :id="rate.id" autocomplete="off">
                    <label class="btn" :for="rate.id" :class="[{'btn-dark': form.rate != rate.id, 'btn-success': form.rate === rate.id},  status($v.form.rate)]" :title="rate.description">{{rate.name}} ({{rate.price}} &#8381; {{rate.type}})</label>
                  </div>
                </div>
              </div>
            </div>

            <div v-show="comment"class="form-floating mb-3">
              <textarea class="form-control" v-model="form.comment" placeholder="Leave a comment here" id="floatingComment" style="height: 100px"></textarea>
              <label for="floatingComment" class="h5">Ваш комментарий</label>
            </div>
          </div>
        </transition>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
          <button :disabled="step === minstep"@click="backStep" class="btn btn-secondary btn-lg me-sm-2 text-white" type="button"><i class="bi bi-arrow-left-square"></i> Назад</button>


          <div v-if="step === maxstep" class="btn-group" role="group">
            <input type="checkbox" class="btn-check" v-model="comment" id="comment" autocomplete="off">
            <label class="btn btn-outline-primary btn-lg" for="comment"><span v-if="comment">Не указывать</span><span v-else>Указать</span> комментарий</label>
          </div>
          <button v-if="step < maxstep" class="btn btn-info btn-lg text-white" @click="nextStep" type="button">Дальше <i class="bi bi-arrow-right-square"></i></button>
          <button v-show="step === maxstep && role != 'owner'" @click.prevent="createPass"  class="btn btn-primary btn-lg text-white"type="submit">Оформить пропуск</button>
          <button v-show="step === maxstep && role === 'owner'" @click.prevent="createRequestForPass"  class="btn btn-primary btn-lg text-white"type="submit">Отправить запрос на пропуск</button>
        </div>

      </form>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'

    import moment from 'moment';
    export default {
        props: ['role','type', 'applicant'],
        mounted() {
            console.log('Component mounted.')

        },
        created(){
          this.form.type = this.type
          if(this.role === 'owner'){
            this.form.applicant = this.applicant
          }
        },
        data() {
            return {
              errors: [],
              step: 1,
              minstep: 1,
              maxstep: 5,
              createMessage: false,
              createRequest: false,
              applicantNotMe: true,
              comment: false,
              create: 1,
              weeksday: {
    	          mode: 'tags',
                placeholder: 'Выберите график приезда (не обязательно)',
                options: [
                  { value: 'Ежедневно', label: 'Ежедневно' },
                  { value: 'Будни', label: 'Будни' },
                  { value: 'Выходные', label: 'Выходные' },
                  { value: 'Понедельник', label: 'Понедельник' },
                  { value: 'Вторник', label: 'Вторник' },
                  { value: 'Среда', label: 'Среда' },
                  { value: 'Четверг', label: 'Четверг' },
                  { value: 'Пятница', label: 'Пятница' },
                  { value: 'Суббота', label: 'Суббота' },
                  { value: 'Воскресенье', label: 'Воскресенье' },
                ],
              },
              rates: [],
              form: {
                type: '',
                project: null,
                visitor: {
                  type: '',
                  id: null,
                },
                temporary:{
                  entry: moment(new Date()).format('YYYY-MM-DD'),
                },
                permanent:{
                  timetable: '',
                  entry: moment(new Date()).format('YYYY-MM-DD'),
                  exit: null,
                },
                applicant: null,
                comment: '',
                rate: null,
              }
            }
        },
        watch: {
          'form.visitor.type': function (newVal, oldVal){
             if(newVal != oldVal){
               this.form.visitor.id = null,
               this.$v.form.visitor.id.$reset()
             }
           },
           'form.permanent.entry': function (newVal, oldVal){
              if(newVal != oldVal){
                this.form.permanent.exit = null
              }
            },
            'weeksday.value': function (val){
              this.form.permanent.timetable = val
             },
        },
        methods: {
          getRates(){
            axios.post('/api/get-pass-rates', {id : this.form.project}).then((response) =>{
              this.rates = response.data
              console.log(response.data)
            })
          },
          backStep(){
            if(this.step > this.minstep) this.step--
          },
          nextStep(){
            let next = true
            if(this.step === 1){
              if(this.$v.form.project.$invalid){
                this.$v.form.project.$touch()
                next = false
              }
            }else if(this.step === 2){
              if(this.$v.form.visitor.type.$invalid){
                this.$v.form.visitor.type.$touch()
                next = false
              }
            }else if(this.step === 3){
              if(this.$v.form.visitor.id.$invalid){
                this.$v.form.visitor.id.$touch()
                next = false
              }
            }else if(this.step === 4){
              if(this.$v.form.applicant.$invalid){
                this.$v.form.applicant.$touch()
                next = false
              }
            }
            if(next && this.step < this.maxstep){
              this.step++
              if(this.step === 4){
                this.getRates()
              }
            }
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'ring': validation.$error,
               'is-valid': validation.required,
             }
          },
          selectProject(id){
            this.form.project = id
          },
          selectVisitorId(id){
            this.form.visitor.id = id
          },
          selectApplicantId(id){
            this.form.applicant = id
          },
          applicantMe(){
            if(this.applicantNotMe){
              this.applicantNotMe = false
              this.form.applicant = this.applicant
            }else{
              this.applicantNotMe = true
              this.form.applicant = null
            }
          },
          createPass(){
            if(this.$v.form.rate.$invalid){
              this.$v.form.rate.$touch()
            }else{
              axios.post('/create/pass/'+this.type, this.form).then((response) =>{
                console.log(response.data)
                this.createMessage = true;
                this.form.type = this.type
                if(this.role === 'owner'){
                  this.form.applicant = this.applicant
                }else{
                  this.form.applicant = null
                }
                this.form.project = null
                this.form.visitor.type = ''
                this.form.visitor.id = null
                this.form.temporary.entry = moment(new Date()).format('YYYY-MM-DD')
                this.form.permanent.timetable = ''
                this.form.permanent.entry = moment(new Date()).format('YYYY-MM-DD')
                this.form.permanent.exit = null
                this.form.comment = ''
                this.form.rate = null
                this.$v.form.$reset()
                this.$emit('refreshList');
                this.step = 1
                this.errors = []
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
          },
          createRequestForPass(){
            axios.post('/create/pass/'+this.type, this.form).then((response) =>{
              console.log(response.data)
              this.createRequest = true
              this.form.type = this.type
              if(this.role === 'owner'){
                this.form.applicant = this.applicant
              }else{
                this.form.applicant = null
              }
              this.form.project = null
              this.form.visitor.type = ''
              this.form.visitor.id = null
              this.form.temporary.entry = moment(new Date()).format('YYYY-MM-DD')
              this.form.permanent.timetable = ''
              this.form.permanent.entry = moment(new Date()).format('YYYY-MM-DD')
              this.form.permanent.exit = null
              this.form.comment = ''
              this.form.rate = null
              this.$v.form.$reset()
              this.$emit('refreshList');
              this.step = 1
              this.errors = []
            }).catch((error) =>{
                this.errors = error.response.data.errors
            })
          },
          succesMessage(type){
            if(type == 'back'){
              document.location.href = "/login";
            }else{
              this.createRequest = false;
              this.createMessage = false;
              this.create += 1
            }
          }
        },
        validations: {
          form: {
            project: {
              required
            },
            visitor: {
              type: {
                required
              },
              id: {
                required
              },
            },
            applicant: {
              required
            },
            rate: {
              required
            },
          },
        }
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
