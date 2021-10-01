<template>
    <div>
      <form enctype="multipart/form-data" novalidate>

        <transition name="slide-fade">
          <div v-if="createMessage" class="alert alert-success" role="alert">
             Вы успешно создали пропуск!
          </div>
        </transition>

        <transition name="slide-fade">
          <div v-show="step === 1">
            <div class="form-floating mb-3">
              <select @blur="$v.form.project.$touch()"
                      :class="status($v.form.project)"
                      v-model="form.project" class="form-select" id="floatingSelectProject" aria-label="Выбор проекта">
                <option disabled value="">Выберите объект...</option>
                <option v-for="project in projects" :value="project.id">{{project.name}}</option>
              </select>
              <label for="floatingSelect" class="h5">Список объектов <i class="bi bi-building"></i></label>
              <div class="invalid-feedback" v-if="!$v.form.project.required">{{ requiredText }}</div>
            </div>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-show="step === 2">
            <div class="btn-group mb-3 d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
              <input @blur="$v.form.visitor.$touch()" type="radio" class="btn-check" v-model="form.visitor" value="car" id="visitor-car" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-car">Автомобиль <i class="bi bi-truck"></i></label>

              <input @blur="$v.form.visitor.$touch()"  type="radio" class="btn-check" v-model="form.visitor" value="human" id="visitor-human" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-human">Человек <i class="bi bi-person-square"></i></label>

              <input @blur="$v.form.visitor.$touch()"  type="radio" class="btn-check" v-model="form.visitor" value="mechanizm" id="visitor-mechanizm" autocomplete="off">
              <label class="btn btn-sm-lg btn-outline-dark" for="visitor-mechanizm">Техника <i class="bi bi-truck-flatbed"></i></label>
            </div>

            <transition name="slide-fade">
              <div v-show="form.visitor === 'car'">
                <div class="row mb-3">
                  <div class="col-12 col-sm-9 mb-2 mb-sm-0">
                    <div class="form-floating">
                      <the-mask
                      @blur="$v.form.carnumber.$touch()"
                      :mask="['R ### RR ##', 'R ### RR ###']"
                      :tokens="carnumberTokens"
                      :class="status($v.form.carnumber)"
                      v-model="form.carnumber"
                      class="form-control"
                      id="floatingCarNumber" placeholder="Номер в формате А100АА77"/>
                      <label for="floatingCarNumber" class="h5">Номер в формате: А100АА77</label>
                    </div>
                  </div>

                  <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center">
                    <div v-if="form.carnumber.length > 7 && carcheck == true">
                      <button @click="checkCar" class="btn btn-primary btn-lg text-white" type="button">Подтвердить ввод</button>
                    </div>
                  </div>

                  <div class="col-12">
                    <div v-if="carData.status === '404'" class="alert alert-danger my-2" role="alert">
                      <strong>{{carData.text}}</strong>
                    </div>

                    <div v-else-if="carData.status === '200'" class="alert alert-success my-2" role="alert">
                      <h4 class="alert-heading">Автомобиль с номером: <strong>{{form.carnumber}}</strong></h4>
                      <p>Марка: <strong>{{carData.model}}</strong></p>
                      <p>Тип: <strong>{{carData.body}}</strong></p>
                      <p>Цвет: <strong>{{carData.color}}</strong></p>
                      <hr>
                      <p class="mb-0">{{carData.fio}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-show="form.visitor === 'human'">
                <div class="row mb-3">
                  <div class="col-12 col-sm-9 mb-2 mb-sm-0 order-1">
                    <div class="form-floating">
                      <input
                      @blur="$v.human.$touch()"
                      :class="status($v.human)"
                      v-model="human"
                      class="form-control"
                      id="floatingHuman" placeholder="ФИО"/>
                      <label for="floatingHuman" class="h5">ФИО человека</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center order-3 order-sm-2">
                    <div v-if="human.split(' ').length > 2 && humancheck == true">
                      <button @click="checkHuman" class="btn btn-primary btn-lg text-white" type="button">Подтвердить ввод</button>
                    </div>
                  </div>

                  <div v-if="humans.length > 0" class="col-12 order-2 order-sm-3 mb-2">
                    <span class="small-text">Возможно это: <a v-for="human in humans" class="link-info me-2" @click="autocompleteHuman(human.fio)">{{human.fio}}</a></span>
                  </div>

                  <transition name="slide-fade">
                    <div class="col-12 order-4">
                      <div v-if="humanData.status === '404'" class="alert alert-danger my-2" role="alert">
                        <strong>{{humanData.text}}</strong>
                        <hr>
                        <p class="mb-1">{{humanData.avatar}} <label
                                                              v-if="humanData.avatar == 'Фото отсутствует'"
                                                              class="btn btn-primary text-white p-1" for="avatar">
                                                              <input type="file" @change="selectAvatar" id="avatar" class="d-none"/>
                                                              <i class="bi bi-paperclip"></i> <span v-if="form.avatar == null">Добавить</span><span v-else>Добавлено</span>
                                                            </label>
                        </p>
                        <p class="mb-0">{{humanData.passport}} <label
                                                                v-if="humanData.passport == 'Паспорт отсутствует'"
                                                                class="btn btn-primary text-white p-1" for="passport">
                                                                <input type="file" @change="selectPassport" id="passport" class="d-none"/>
                                                                <i class="bi bi-paperclip"></i> <span v-if="form.passport == null">Добавить</span><span v-else>Добавлено</span>
                                                              </label>
                        </p>
                      </div>
                      <div v-else-if="humanData.status === '200'" class="alert alert-success my-2" role="alert">
                        <h4 class="alert-heading"><strong>{{humanData.profile.fio}}</strong></h4>
                        <hr>
                        <p>Пол: <strong>{{humanData.profile.sex}}</strong></p>
                        <p>Дата рождения: <strong>{{humanData.profile.dateofbirth}}</strong></p>
                        <hr>
                        <p class="mb-1">{{humanData.avatar}} <label
                                                              v-if="humanData.avatar == 'Фото отсутствует'"
                                                              class="btn btn-primary text-white p-1" for="avatar">
                                                              <input type="file" @change="selectAvatar" id="avatar" class="d-none"/>
                                                              <i class="bi bi-paperclip"></i> <span v-if="form.avatar == null">Добавить</span><span v-else>Добавлено</span>
                                                            </label>
                        </p>
                        <p class="mb-0">{{humanData.passport}} <label
                                                                v-if="humanData.passport == 'Паспорт отсутствует'"
                                                                class="btn btn-primary text-white p-1" for="passport">
                                                                <input type="file" @change="selectPassport" id="passport" class="d-none"/>
                                                                <i class="bi bi-paperclip"></i> <span v-if="form.passport == null">Добавить</span><span v-else>Добавлено</span>
                                                              </label>
                        </p>
                      </div>
                    </div>
                </transition>
                </div>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-show="form.visitor === 'mechanizm'">
                <div class="row mb-3">
                  <div class="col-12 col-sm-9 mb-2 mb-sm-0">
                    <div class="form-floating">
                      <the-mask
                      @blur="$v.form.mechanizmnumber.$touch()"
                      :mask="['R ### RR ##', 'R ### RR ###']"
                      :tokens="carnumberTokens"
                      :class="status($v.form.mechanizmnumber)"
                      v-model="form.mechanizmnumber"
                      class="form-control"
                      id="floatingMechanizmNumber" placeholder="Номер в формате А100АА77"/>
                      <label for="floatingMechanizmNumber" class="h5">Номер в формате: А100АА77</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center">
                    <div v-if="form.mechanizmnumber.length > 7 && mechanizmcheck == true">
                      <button @click="checkMechanizm" class="btn btn-primary btn-lg text-white" type="button">Подтвердить ввод</button>
                    </div>
                  </div>
                  <div class="col-12">
                    <div v-if="mechanizmData.status === '404'" class="my-2">
                      <div v-if="mechanizmData.status === '404'" class="alert alert-danger mb-2" role="alert">
                        <strong>{{mechanizmData.text}}</strong>
                      </div>
                      <div>
                        <div class="form-floating mb-2">
                          <input
                                  @blur="$v.form.mechanizmmodel.$touch()"
                                  :class="status($v.form.mechanizmmodel)"
                                  v-model="form.mechanizmmodel"
                                  class="form-control"
                                  id="floatingMechanizmModel" placeholder="Модель новой техники"/>
                                  <label for="floatingMechanizmModel" class="h5">Укажите модель новой техники</label>
                                  <div class="invalid-feedback" v-if="!$v.form.mechanizmmodel.required">{{ requiredText }}</div>
                        </div>
                        <div class="form-floating mb-2">
                          <select @blur="$v.form.mechanizmtype.$touch()"
                                  :class="status($v.form.mechanizmtype)"
                                  v-model="form.mechanizmtype"
                                  class="form-select" id="floatingSelectProject" aria-label="Выбор типа">
                            <option disabled value="">Выберите тип...</option>
                            <option v-for="tax in taxes" :value="tax.id">{{tax.type}} <span v-if="tax.type === 'Спецтехника'">{{tax.weight}}</span></option>
                          </select>
                          <label for="floatingSelect" class="h5">Список техники (типы)</label>
                          <div class="invalid-feedback" v-if="!$v.form.mechanizmtype.required">{{ requiredText }}</div>
                        </div>
                        <div v-if="form.mechanizmtype < 5 && form.mechanizmtype != ''" class="form-floating mb-2">
                          <input
                                  @blur="$v.form.mechanizmname.$touch()"
                                  :class="status($v.form.mechanizmname)"
                                  v-model="form.mechanizmname"
                                  class="form-control"
                                  id="floatingMechanizmModel" placeholder="Наименование новой техники"/>
                                  <label for="floatingMechanizmModel" class="h5">Наименование новой техники</label>
                                  <div class="invalid-feedback" v-if="!$v.form.mechanizmname.required">{{ requiredText }}</div>
                        </div>
                        <div class="form-floating mb-2">
                          <input  v-model="form.mechanizmcolor"
                                  class="form-control"
                                  id="floatingMechanizmModel" placeholder="Цвет новой техники"/>
                                  <label for="floatingMechanizmModel" class="h5">Укажите цвет новой техники</label>
                        </div>
                      </div>
                    </div>
                    <div v-else-if="mechanizmData.status === '200'" class="alert alert-success my-2" role="alert">
                      <h4 class="alert-heading">Техника с номером: <strong>{{form.mechanizmnumber}}</strong></h4>
                      <p>Марка: <strong>{{mechanizmData.model}}</strong></p>
                      <p>Название: <strong>{{mechanizmData.name}}</strong></p>
                      <p>Цвет: <strong>{{mechanizmData.color}}</strong></p>
                      <p>Плата за въезд: <strong>{{mechanizmData.price}} &#x20bd;</strong></p>
                      <hr>
                      <p class="mb-0">{{mechanizmData.fio}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </transition>

            <transition name="slide-fade">
              <div v-show="form.owner && ((mechanizmData.fio === 'Без владельца' && form.visitor === 'mechanizm') || (carData.fio === 'Без владельца' && form.visitor === 'car'))">
                <div class="row mb-3">
                  <div class="col-12 col-sm-9 mb-2 mb-sm-0">
                    <div class="form-floating">
                      <input
                      @blur="$v.human.$touch()"
                      :class="status($v.human)"
                      v-model="human"
                      class="form-control"
                      id="floatingOwner" placeholder="ФИО"/>
                      <label for="floatingOwner" class="h5">ФИО владельца</label>
                    </div>
                  </div>
                  <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center">
                    <div v-if="human.split(' ').length > 2 && humancheck == true">
                      <button @click="checkOwner" class="btn btn-primary btn-lg text-white" type="button">Подтвердить ввод</button>
                    </div>
                  </div>

                  <div v-if="humans.length > 0" class="col-12">
                    <span class="small-text">Возможно это: <a v-for="human in humans" class="link-info me-2" @click="autocompleteHuman(human.fio)">{{human.fio}}</a></span>
                  </div>
                </div>
              </div>
            </transition>

          </div>
        </transition>



        <transition name="slide-fade">
          <div v-show="step === 3">
            <div v-if="role != 'owner'" class="row mb-3">
              <div class="col-12 col-sm-9 mb-2 mb-sm-0">
                <div class="form-floating">
                  <input
                  @blur="$v.applicant.$touch()"
                  :class="status($v.applicant)"
                  v-model="applicant"
                  class="form-control"
                  id="floatingApplicant" placeholder="ФИО заказчика пропуска"/>
                  <label for="floatingApplicant" class="h5">ФИО заказчика пропуска</label>
                  <div class="invalid-feedback">Заказчик должен быть из числа пользователей системы!</div>
                </div>
              </div>
              <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center">
                <div v-if="applicant.split(' ').length > 2 && applicantcheck == true">
                  <button @click="checkApplicant" class="btn btn-primary btn-lg text-white" type="button">Подтвердить ввод</button>
                </div>
              </div>

              <div v-if="humans.length > 0" class="col-12">
                <span class="small-text">Возможно это: <a v-for="human in humans" class="link-info me-2" @click="autocompleteApplicant(human.fio)">{{human.fio}}</a></span>
              </div>
            </div>
            <div class="form-floating mb-2">
              <VueDatePicker v-model="form.date" class="form-control mb-2" :min-date="new Date()" value="DD.MM.YYYY" format="DD.MM.YYYY" id="date" no-header/>
              <label for="date" class="h5">Дата приезда</label>
            </div>
            <div v-show="form.comment"class="form-floating mb-3">
              <textarea class="form-control" v-model="form.commenttext" placeholder="Leave a comment here" id="floatingComment" style="height: 100px"></textarea>
              <label for="floatingComment" class="h5">Ваш комментарий</label>
            </div>
          </div>
        </transition>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
          <button v-if="step > minstep" @click="backStep" class="btn btn-secondary btn-lg me-sm-2 text-white" type="button"><i class="bi bi-arrow-left-square"></i> Назад</button>
          <div v-if="((mechanizmData.fio === 'Без владельца' && form.visitor === 'mechanizm') || (carData.fio === 'Без владельца' && form.visitor === 'car')) && step === 2" class="btn-group" role="group">
            <input type="checkbox" class="btn-check" v-model="form.owner" id="owner" autocomplete="off">
            <label class="btn btn-outline-primary btn-lg" for="owner"><span v-if="form.owner">Не указывать</span><span v-else>Указать</span> владельца</label>
          </div>
          <div v-if="step === 3" class="btn-group" role="group">
            <input type="checkbox" class="btn-check" v-model="form.comment" id="comment" autocomplete="off">
            <label class="btn btn-outline-primary btn-lg" for="comment"><span v-if="form.comment">Не указывать</span><span v-else>Указать</span> комментарий</label>
          </div>
          <button v-if="step < maxstep" :disabled="disabledBtn" class="btn btn-info btn-lg text-white" @click="nextStep" type="button">Дальше <i class="bi bi-arrow-right-square"></i></button>
          <button v-show="step === maxstep" :disabled="disabledBtn" @click.prevent="createPass"  class="btn btn-primary btn-lg text-white"type="submit">Оформить пропуск</button>
        </div>
      </form>
      <!-- <pre>{{$v.form}}</pre> -->
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'

    import moment from 'moment';
    export default {
        props: ['role','getProjectsRoute', 'getTaxesRoute', 'createRoute', 'getcarRoute', 'findhumanRoute', 'gethumanRoute', 'getmechanizmRoute'],
        created() {
            this.getProjects()
            this.getTaxes()
        },
        mounted() {
          console.log('Компонент загружен.')
          if(this.role == 'owner'){
            this.form.applicantfio = this.form.applicantid = this.role
          }
        },
        methods: {
          getProjects(){
            axios.post('/api/get-projects').then((response) =>{
              this.projects = response.data
              this.step = 1
              this.minstep = 1
              // if(this.projects.length < 2){
              //   this.form.project = this.projects[0].id
              //   this.step = 2
              //   this.minstep = 2
              // }else{
              //   this.step = 1
              //   this.minstep = 1
              // }
            })
          },
          getTaxes(){
            axios.post(this.getTaxesRoute).then((response) =>{
              this.taxes = response.data
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          nextStep(){
            if(this.step < this.maxstep) this.step++
          },
          backStep(){
            if(this.step > this.minstep) this.step--
          },
          checkCar(){
            axios.post(this.getcarRoute, this.form).then((response) =>{
              this.carData = response.data
              this.form.carid = response.data.id
              this.carcheck = false
            })
          },
          findHuman(v){
            axios.post(this.findhumanRoute, {human : v}).then((response) =>{
              this.humans = response.data
            })
          },
          checkHuman(){
            axios.post(this.gethumanRoute, {human : this.human}).then((response) =>{
              this.humanData = response.data
              this.form.humanid = response.data.profile.id
              this.form.humanfio = this.human
              this.humans = []
              this.humancheck = false
            })
          },
          autocompleteHuman(fio){
            this.human = fio
          },
          autocompleteApplicant(fio){
            this.applicant = fio
          },
          selectAvatar(event) {
            this.form.avatar = event.target.files[0]
          },
          selectPassport(event) {
            this.form.passport = event.target.files[0];
          },
          checkMechanizm(){
            axios.post(this.getmechanizmRoute, this.form).then((response) =>{
              this.mechanizmData = response.data
              this.form.mechanizmid = response.data.id
              this.mechanizmcheck = false
            })
          },
          checkOwner(){
            axios.post(this.gethumanRoute, {human : this.human}).then((response) =>{
              this.form.ownerid = response.data.profile.id
              this.form.ownerfio = response.data.profile.fio
              this.humans = []
              this.humancheck = false
            })
          },
          checkApplicant(){
            axios.post(this.gethumanRoute, {human : this.applicant}).then((response) =>{
              if(response.data.profile.id == 'new'){
                this.applicant = ''
                this.$v.applicant.$touch()
              }else{
                this.form.applicantid = response.data.profile.id
                this.form.applicantfio = this.applicant
                this.humans = []
                this.applicantcheck = false
              }
            })
          },
          createPass() {
            let formData = new FormData()
            formData.append('avatar', this.form.avatar)
            formData.append('passport', this.form.passport)

            _.each(this.form, (value, key) => {
              formData.append(key, value)
            })

            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
            }

            axios.post(this.createRoute, formData, config).then((response) =>{

              if(this.projects.length < 2){
                this.step = 2
                this.minstep = 2
              }else{
                this.step = 1;
              }
              this.createMessage = true;
              // убрать сообщение о регистрации
              setTimeout(() => {
                this.createMessage = false
              }, 3000)
              // сбросить все поля
              for (let input in this.form) {
                  this.form[input] = ''
              }

              if(this.projects.length < 2){
                this.form.project = this.projects[0].id
              }
              this.carData = [],
              this.humanData = [],
              this.humans = [],
              this.mechanizmData = [],
              this.applicant = ''
              this.form.date = moment(new Date()).format('YYYY-MM-DD')
              this.form.type = 'temporary'
              this.form.owner = false
              this.form.comment = false
              this.form.avatar = null
              this.form.passport = null

              this.humancheck = true
              this.applicantcheck = true
              this.mechanizmcheck = true
              this.carcheck = true

              this.$v.$reset()
            })

          },
        },
        computed: {
          disabledBtn() {
            if(this.step === 1){
              return this.$v.form.project.$invalid
            }

            if(this.step === 2){
              if(this.form.visitor === 'car'){
                if(this.form.owner){
                  return this.$v.form.carnumber.$invalid || this.$v.form.carid.$invalid || this.$v.form.ownerid.$invalid
                }else{
                  return this.$v.form.carnumber.$invalid || this.$v.form.carid.$invalid
                }
              }else if(this.form.visitor === 'human'){
                return this.$v.form.humanfio.$invalid || this.$v.form.humanid.$invalid
              }else if(this.form.visitor === 'mechanizm'){
                if(this.form.owner){
                  if(this.form.mechanizmid == 'new'){
                    if(this.form.mechanizmtype < 5){
                      return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid || this.$v.form.mechanizmmodel.$invalid || this.$v.form.mechanizmtype.$invalid || this.$v.form.mechanizmname.$invalid || this.$v.form.ownerid.$invalid
                    }else{
                      return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid || this.$v.form.mechanizmmodel.$invalid || this.$v.form.mechanizmtype.$invalid || this.$v.form.ownerid.$invalid
                    }
                  }else{
                    return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid || this.$v.form.ownerid.$invalid
                  }
                }else{
                  if(this.form.mechanizmid == 'new'){
                    if(this.form.mechanizmtype < 5){
                      return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid || this.$v.form.mechanizmmodel.$invalid || this.$v.form.mechanizmtype.$invalid || this.$v.form.mechanizmname.$invalid
                    }else{
                      return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid || this.$v.form.mechanizmmodel.$invalid || this.$v.form.mechanizmtype.$invalid
                    }
                  }else{
                    return this.$v.form.mechanizmnumber.$invalid || this.$v.form.mechanizmid.$invalid
                  }
                }
              }else{
                return this.$v.form.visitor.$invalid
              }
            }

            if(this.step === 3){
              return this.$v.form.applicantfio.$invalid || this.$v.form.applicantid.$invalid
            }
          },
        },
        data() {
            return {
              step: 0,
              minstep: 0,
              maxstep: 3,
              createMessage: false,
              projects: [],
              taxes: [],
              form: {
                type: 'temporary',
                project: '',
                visitor: '',
                carnumber: '',
                carid: '',
                humanfio: '',
                humanid: '',
                avatar: null,
                passport: null,
                mechanizmnumber: '',
                mechanizmid: '',
                mechanizmmodel: '',
                mechanizmtype: '',
                mechanizmname: '',
                mechanizmcolor: '',
                owner: false,
                ownerfio: '',
                ownerid: '',
                applicantfio: '',
                applicantid: '',
                date: moment(new Date()).format('YYYY-MM-DD'),
                comment: false,
                commenttext: ''
              },
              carData: [],
              carcheck: true,
              applicant: '',
              applicantcheck: true,
              applicantStatus: false,
              human: '',
              humancheck: true,
              humanData: [],
              humanlength: 0,
              humans: [],
              mechanizmData: [],
              mechanizmcheck: true,
              requiredText: 'Это поле должно быть заполнено, чтобы продолжить',
              carnumberTokens: {
                R: {
                  pattern: /[аА-яЯ]/,
                  transform: v => v.toLocaleUpperCase()
                },
                '#': {
                  pattern: /\d/
                },
              }
            }
        },
        watch: {
          form : {
            handler: function(v) {
              if(v.carnumber.length < 8){
                this.carData = []
                this.form.carid = ''
                this.carcheck = true
              }

              if(v.mechanizmnumber.length < 8){
                this.mechanizmData = [];
                this.form.mechanizmid = '';
                this.$v.form.mechanizmmodel.$reset()
                this.$v.form.mechanizmtype.$reset()
                this.$v.form.mechanizmname.$reset()
                this.form.mechanizmmodel = '';
                this.form.mechanizmtype = '';
                this.form.mechanizmname = '';
                this.form.mechanizmcolor = '';

                this.mechanizmcheck = true
              }
            },
            deep: true
          },
          human : function(newHuman, oldHuman){
            if(newHuman.length > oldHuman.length){
              this.findHuman(newHuman)
            }else{
              this.humanData = []
              this.form.humanfio = ''
              this.form.humanid = ''
              this.humancheck = true
            }
          },

          applicant : function(newApplicant, oldApplicant){
            if(newApplicant.length > oldApplicant.length){
              this.findHuman(newApplicant)
            }else{
              this.form.applicantfio = ''
              this.form.applicantid = ''
              this.applicantcheck = true
            }
          },
          'form.visitor': function (newVal, oldVal){
             if(newVal != oldVal){
               this.human = ''
               this.humans = []
               this.$v.human.$reset()
             }
         },
        },
        validations: {
          form: {
            project: {
              required
            },
            visitor: {
              required
            },
            carnumber: {
              required,
            },
            carid: {
              required
            },
            humanfio: {
              required,
            },
            humanid: {
              required
            },
            mechanizmnumber: {
              required,
            },
            mechanizmid: {
              required
            },
            mechanizmmodel: {
              required
            },
            mechanizmtype: {
              required
            },
            mechanizmname: {
              required
            },
            owner: {
              required
            },
            ownerfio: {
              required
            },
            ownerid: {
              required
            },
            applicantfio: {
              required
            },
            applicantid: {
              required
            },
          },
          human: {
            required,
          },
          applicant: {
            required,
          },
        }
    }
</script>
<style>
.slide-fade-enter-active {
  transition: all 0.5s ease;
}
.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}
</style>
