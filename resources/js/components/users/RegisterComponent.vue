<template>
    <div>
        <div v-if="success" class="alert alert-success" role="alert">
          {{message}}
        </div>
        <form enctype="multipart/form-data" novalidate>
          <transition name="slide-fade">
            <div v-if="form.role === ''">
              <div class="form-floating">
                <select class="form-select" id="floatingSelect" v-model="form.role" aria-label="Floating label select example">
                  <option value="" disabled>Открыть список</option>
                  <optgroup v-for="(group, name) in roles" :label="name">
                    <option v-for="role in group" :value="role.name">
                      {{role.name}}
                    </option>
                  </optgroup>
                </select>
                <label for="floatingSelect">Выберите роль пользователя <i class="bi bi-people-fill"></i></label>
                <div class="form-text">Выберите роль, чтобы продолжить.</div>
              </div>
            </div>
          </transition>
          <transition name="slide-fade">
            <div v-if="form.role != ''">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="lead">Роль: {{form.role}}</span>
                <button @click="reset"
                        class="btn btn-info text-white" type="button">Сменить</button>
              </div>

              <div class="form-floating mb-2">
                  <dadata-suggestions
                      @blur.native="$v.form.general.fio.$touch()"
                      :class="status($v.form.general.fio)"
                      v-model="form.general.fio"
                      type="NAME"
                      field-value="unrestricted_value"
                      class="form-control" id="floatingName" placeholder="Иванов Иван Иванович"
                  />
                <label for="floatingName" class="h5">ФИО пользователя <i class="bi bi-person-badge-fill"></i></label>
                <div class="invalid-feedback" v-if="!$v.form.general.fio.required">{{ requiredText }}</div>
                <div class="invalid-feedback" v-if="!$v.form.general.fio.alpha">Это поле должно быть написано только русскими буквами</div>
              </div>

              <div class="row g-2">
                <div class="col-md">
                  <div class="form-floating">
                    <dadata-suggestions
                            @blur.native="$v.form.general.email.$touch()"
                            :class="status($v.form.general.email)"
                            v-model="form.general.email"
                            type="EMAIL"
                            field-value="unrestricted_value"
                            class="form-control" id="floatingEmail" placeholder="name@example.com"
                            />
                    <label for="floatingInputEmail" class="h5">Email <i class="bi bi-envelope-fill"></i></label>
                    <div class="invalid-feedback" v-if="!$v.form.general.email.required">{{ requiredText }}</div>
                    <div class="invalid-feedback" v-if="!$v.form.general.email.email">Поле должно быть e-mail адресом</div>
                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                  </div>
                </div>
                <div class="col-md">
                  <div class="form-floating mb-3">
                    <the-mask
                              @blur.native="$v.form.general.phone.$touch()"
                              :mask="['!+!7(###)-###-##-##']"
                              :tokens="phoneTokens"
                              :class="status($v.form.general.phone)"
                              v-model="form.general.phone"
                              type="phone"
                              class="form-control"
                              placeholder="+7(909)-777-88-18"
                              id="floatingPhone" />
                    <label for="floatingPhone" class="h5">Номер телефона <i class="bi bi-telephone-plus-fill"></i></label>
                    <div class="invalid-feedback" v-if="!$v.form.general.phone.required">{{ requiredText }}</div>
                    <div class="invalid-feedback" v-if="!$v.form.general.phone.minLength">Номер должен содержать 10 цифр без +7</div>
                    <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                  </div>
                </div>
              </div>

              <div v-if="profile">
                <div class="form-floating mb-3">
                  <select v-model="form.general.sex" class="form-select" id="floatingSelectSex" aria-label="Выбор пола">
                    <option disabled value="">Выберите пол...</option>
                    <option value="Мужской">Мужской</option>
                    <option value="Женский">Женский</option>
                  </select>
                  <label for="floatingSelectSex" class="h5">Пол <i class="bi bi-people"></i></label>
                </div>

                <div class="form-floating mb-3">
                  <VueDatePicker v-model="form.general.dateofbirth" class="form-control mb-2" :max-date="maxDate" :visible-years-number="100" value="DD.MM.YYYY" format="DD.MM.YYYY" id="date" no-header/>
                  <label for="date" class="h5">Дата вашего рождения</label>
                </div>
              </div>

              <div v-if="car">
                <p class="lead mb-2">Добавить автомобиль:</p>
                <div class="form-floating mb-3">
                  <the-mask
                          :mask="['R ### RR ##', 'R ### RR ###']"
                          :tokens="carnumberTokens"
                          v-model="form.car.number" type="text" class="form-control" id="floatingNumber" placeholder="name@example.com"/>
                  <label for="floatingNumber">Номер в формате: А100АА77</label>
                  <div v-if="errors && errors.number" class="text-danger">{{ errors.number[0] }}</div>
                </div>
                <div class="form-floating mb-3">
                  <input v-model="form.car.model" type="text" class="form-control" id="floatingModel" placeholder="name@example.com">
                  <label for="floatingModel">Марка авто</label>
                </div>
                <div class="form-floating mb-3">
                  <select v-model="form.car.body" class="form-select" id="floatingSelectBody" aria-label="Выбор типа">
                    <option value="Легковое">Легковое</option>
                    <option value="Грузовое">Грузовое</option>
                  </select>
                  <label for="floatingSelectBody" class="h5">Тип автомобиля</label>
                </div>
                <div class="form-floating mb-3">
                  <input v-model="form.car.color" type="text" class="form-control" id="floatingColor" placeholder="name@example.com">
                  <label for="floatingColor">Цвет авто</label>
                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex justify-content-sm-end mb-3">
                <input  v-model="car"
                        type="checkbox" class="btn-check" id="showCar" autocomplete="off">
                <label class="btn btn-secondary me-sm-2" for="showCar"><span v-if="!car">Добавить</span><span v-else>Не добавлять</span> авто <i class="bi bi-truck"></i></label>
                <input  v-model="profile"
                        type="checkbox" class="btn-check" id="showProfile" autocomplete="off">
                <label class="btn btn-outline-secondary" for="showProfile"><span v-if="!profile">Добавить</span><span v-else>Не добавлять</span> профиль <i class="bi bi-person-badge-fill"></i></label>
              </div>

              <div v-if="hideOrganization" class="mb-3">
                <hr>
                <p class="lead">Укажаите название организации для нового менеджера</p>
                <Multiselect  @blur="$v.form.organization.$touch()"
                              :class="status($v.form.organization)"
                              v-model="organizations.value"
                              v-bind="organizations"
                              @select="form.organization = organizations.value"
                              @change="form.organization = ''"
                            ></Multiselect>
                            <div class="invalid-feedback" v-if="!$v.form.organization.required">{{ requiredText }}</div>
                  <div class="form-text">Напишите в поле название организации для поиска и выбора из вспомогательного списка. Если такого названия нет в базе, то организация добавиться в базу.</div>
              </div>
              <div v-if="hideNewProject" class="mb-3">
                <hr>
                <p class="lead">Для регистрации нового собственника создайте объект <i class="bi bi-building"></i></p>
                <div class="form-floating mb-3">
                  <input  @blur="$v.form.project.name.$touch()"
                          :class="status($v.form.project.name)"
                          v-model="form.project.name"
                          type="text" class="form-control" id="floatingProjectName" placeholder="Двух этажный дом">
                  <label for="floatingProjectName" class="h5">Название объекта <i class="bi bi-person-badge-fill"></i></label>
                  <div class="invalid-feedback" v-if="!$v.form.project.name.required">{{ requiredText }}</div>
                </div>
                <div class="form-floating my-3">
                  <select @blur="$v.form.project.type.$touch()"
                          :class="status($v.form.project.type)"
                          class="form-select" id="floatingTypes" v-model="form.project.type" aria-label="Floating label select example">
                    <option value="" disabled>Открыть список</option>
                    <option v-for="type in projectTypes" :value="type.value">
                      {{type.label}}
                    </option>
                  </select>
                  <label for="floatingTypes">Выберите тип объекта <i class="bi bi-building"></i></label>
                  <div class="invalid-feedback" v-if="!$v.form.project.type.required">{{ requiredText }}</div>
                </div>
                <div class="form-floating my-3">
                  <textarea  v-model="form.project.description"
                             class="form-control" placeholder="Leave a comment here" id="floatingComment" style="height: 100px"></textarea>
                  <label for="floatingComment">Описание <i class="bi bi-chat-left-text"></i></label>
                </div>
                <vue-dadata :class="status($v.form.project.address)"
                            :token="dadata_api_key" placeholder="Поиск адреса" :on-change="address"></vue-dadata>
                <div class="invalid-feedback" v-if="!$v.form.project.address.required">{{ requiredText }}</div>
                <div v-if="form.project.address != ''">
                  <div class="row my-2">
                    <div class="col-12 col-sm-3">
                      <div class="form-group">
                        <label for="region">Регион</label>
                        <input  @blur="$v.form.project.region.$touch()"
                                :class="status($v.form.project.region)"
                                v-model="form.project.region"
                                type="text" class="form-control project" name="region" id="region" value="" placeholder="Регион">
                                <div class="invalid-feedback" v-if="!$v.form.project.region.required">{{ requiredText }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="form-group">
                        <label for="city">Город</label>
                        <input  @blur="$v.form.project.city.$touch()"
                                :class="status($v.form.project.city)"
                                v-model="form.project.city"
                                type="text" class="form-control project" name="city" id="city" value="" placeholder="Город">
                                <div class="invalid-feedback" v-if="!$v.form.project.city.required">{{ requiredText }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">
                      <div class="form-group">
                        <label for="street">Улица</label>
                        <input  @blur="$v.form.project.street.$touch()"
                                :class="status($v.form.project.street)"
                                v-model="form.project.street"
                                type="text" class="form-control project" name="street" id="street" value="" placeholder="Улица">
                                <div class="invalid-feedback" v-if="!$v.form.project.street.required">{{ requiredText }}</div>
                      </div>
                    </div>
                  </div>

                  <div class="row my-2">
                    <div class="col-12 col-sm-2">
                      <div class="form-group">
                        <label for="numberofhouse">Номер дома</label>
                        <input  @blur="$v.form.project.house.$touch()"
                                :class="status($v.form.project.house)"
                                v-model="form.project.house"
                                type="text" class="form-control project" name="numberofhouse" id="numberofhouse" placeholder="Номер дома">
                                <div class="invalid-feedback" v-if="!$v.form.project.house.required">{{ requiredText }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-2">
                      <div class="form-group">
                        <label for="houseBlock">Корпус/строение</label>
                        <input  v-model="form.project.block"
                                type="text" class="form-control project" name="numberofhouse" id="houseBlock" placeholder="Корпус/строение">
                      </div>
                    </div>
                    <div class="col-12 col-sm-2">
                      <div class="form-group">
                        <label for="numberofroom">Номер помещения</label>
                        <input  @blur="$v.form.project.flat.$touch()"
                                :class="status($v.form.project.flat)"
                                v-model="form.project.flat"
                                type="text" class="form-control project" id="numberofroom" placeholder="Номер помещения">
                                <div class="invalid-feedback" v-if="!$v.form.project.flat.required">{{ requiredText }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="form-group">
                        <label for="totalarea">Общая площадь, м<sup>2</sup></label>
                        <input  @blur="$v.form.project.totalarea.$touch()"
                                :class="status($v.form.project.totalarea)"
                                type="number" class="form-control project" v-model.number="form.project.totalarea"  id="totalarea" step="any" max="50" placeholder="Общая площадь">
                                <div class="invalid-feedback" v-if="!$v.form.project.totalarea.required">{{ requiredText }}</div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-3">
                      <div class="form-group">
                        <label for="livingarea">Жилая площадь, м<sup>2</sup></label>
                        <input @blur="$v.form.project.livingarea.$touch()"
                               :class="status($v.form.project.livingarea)"
                               v-model.number="form.project.livingarea"
                               type="number" class="form-control project" name="livingarea" id="livingarea" step="any" min="30" placeholder="Жилая площадь">
                               <div class="invalid-feedback" v-if="!$v.form.project.livingarea.required">{{ requiredText }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="row my-2">
                    <div class="col-12 col-sm-9">
                      <div v-if="projectGroup">
                        <Multiselect  @blur.native="$v.form.project.group.$touch()"
                                      :class="status($v.form.project.group)"
                                      v-model="projectGroups.value"
                                      v-bind="projectGroups"
                                      @select="form.project.group = projectGroups.value"
                                      @change="form.project.group = ''"
                                    ></Multiselect>
                                    <div class="invalid-feedback" v-if="!$v.form.organization.required">{{ requiredText }}</div>
                                    <div class="form-text">Напишите в поле название для поиска и выбора из вспомогательного списка. Если такого названия нет в базе, то оно добавиться в базу.</div>

                      </div>
                    </div>
                    <div class="col-12 col-sm-3 d-flex justify-content-end align-items-center">
                      <div class="form-check">
                        <input  v-model="projectGroup"
                                class="form-check-input" type="checkbox" id="ProjectGroup"
                                @change="form.project.group = 'false'"
                                >
                        <label class="form-check-label" for="ProjectGroup">
                          Добавить в/Создать группу
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div v-if="hideFamilies" class="mb-3">
                <hr>
                <p class="lead">Выберите семью для {{form.role}}</p>
                <Multiselect  @blur="$v.form.family.$touch()"
                              :class="status($v.form.family)"
                              v-model="families.value"
                              v-bind="families"
                              @select="form.family = families.value"
                              @change="form.family = ''"
                            ></Multiselect>
                            <div class="invalid-feedback" v-if="!$v.form.family.required">{{ requiredText }}</div>
              </div>
              <div v-if="hideProjects" class="mb-3">
                <hr>
                <p class="lead">Выберите объект для {{form.role}}</p>
                <Multiselect  @blur="$v.form.projectid.$touch()"
                              :class="status($v.form.projectid)"
                              v-model="projects.value"
                              v-bind="projects"
                              @select="form.projectid = projects.value"
                              @change="form.projectid = ''"
                            ></Multiselect>
                            <div class="invalid-feedback" v-if="!$v.form.projectid.required">{{ requiredText }}</div>
              </div>
              <div v-if="hideActivities" class="mb-3">
                <hr>
                <p class="lead">Виды выполняемых работ для {{form.role}}</p>
                <Multiselect
                    v-model="activities.value"
                    v-bind="activities"
                    @select="form.activities = activities.value"
                    @change="form.activities = []"

                  ></Multiselect>
                  <div class="d-flex justify-content-end align-items-center my-2">
                    <div class="form-check">
                      <input  v-model="equipment"
                              class="form-check-input" type="checkbox" id="equipment">
                      <label class="form-check-label" for="equipment">
                        Выдать обородувание
                      </label>
                    </div>
                  </div>
              </div>
              <div v-if="equipment" class="mb-3">
                <hr>
                <p class="lead">Оборудование для {{form.role}}</p>
                <Multiselect
                    v-model="equipments.value"
                    v-bind="equipments"
                    @select="form.equipments = equipments.value"
                    @change="form.equipments = []"

                  ></Multiselect>
              </div>

              <div v-if="hideGuardPosts" class="mb-3">
                <hr>
                <p class="lead">Выберите пост охраны для {{form.role}}</p>
                <Multiselect  @blur="$v.form.guardpost.$touch()"
                              :class="status($v.form.guardpost)"
                              v-model="guardposts.value"
                              v-bind="guardposts"
                              @select="form.guardpost = guardposts.value"
                              @change="form.guardpost = ''"
                            ></Multiselect>
                            <div class="invalid-feedback" v-if="!$v.form.guardpost.required">{{ requiredText }}</div>
              </div>

              <div class="d-grid gap-2">
                <button @click.prevent="register" class="btn btn-primary btn-lg text-white" type="submit">Зарегестрировать</button>
              </div>
            </div>
          </transition>
        </form>
    </div>
</template>

<script>
    import {required, minLength, helpers, email} from 'vuelidate/lib/validators'
    import moment from 'moment';
    const alpha = helpers.regex('alpha', /^[а-яёА-ЯЁ ]*$/)
    export default {
        props: ['RegisterUserRoute','getRolesRoute','getOrganizationsRoute', 'getProjectGroupsRoute', 'getFamiliesRoute', 'getFamilyProjectsRoute', 'getProjectTypesRoute','getActivitiesRoute', 'getEquipmentsRoute', 'getOptionProjectsRoute', 'getGuardPostsRoute'],
        mounted() {
            console.log('Component mounted.')
        },
        created(){
          this.getRoles()
          this.getOrganizations()
          this.getProjectGroups()
          this.getFamilies()
          this.getActivities()
          this.getEquipments()
          this.getGuardPost()
          this.getProjectTypes()
        },
        data() {
            return {
              step: 1,
              errors: [],
              maxDate: moment(new Date(), 'DD.MM.YYYY').format('YYYY-MM-DD'),
              success: false,
              message: 'Поздравляем, Вы успешно зарегестрировали Пользователя! Уведомление с паролем отправлено ему на почту.',
              roles: {
                'Группа управляющих': [],
                'Группа собственника': [],
                'Группа работников': [],
              },
              requiredText: 'Это поле должно быть заполнено !',
              organizations: {
    	          value: null,
                createTag: true,
                searchable: true,
                placeholder: 'Выберите организацию',
                options: [],
              },
              projectTypes:[],
              projectGroups: {
    	          value: null,
                createTag: true,
                searchable: true,
                placeholder: 'Выберите или создайте группу для проекта',
                options: [],
              },
              families: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите семью Собственника',
                options: [],
              },
              projects: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите объект',
                options: [],
              },
              activities: {
                mode: 'tags',
    	          value: null,
                createTag: true,
                searchable: true,
                placeholder: 'Выберите виды работ',
                options: [],
              },
              equipments: {
                mode: 'tags',
    	          value: null,
                searchable: true,
                placeholder: 'Выберите обородувание',
                options: [],
              },
              guardposts: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите пост',
                options: [],
              },
              equipment: false,
              dadata_api_key: '67fd1083a94980b339851baf73f373e31510c813',
              projectGroup: false,
              car: false,
              profile: false,
              form:{
                role: '',
                general:{
                  fio: '',
                  email: '',
                  phone: '',
                  sex: '',
                  dateofbirth: moment(new Date(), 'DD.MM.YYYY').format('YYYY-MM-DD'),
                },
                car: {
                  model: '',
                  number: '',
                  body: 'Легковое',
                  color: '',
                },
                organization: '',
                activities: [],
                equipments:[],
                project:{
                  name: '',
                  description: '',
                  type: '',
                  address: '',
                  region: '',
                  city: '',
                  street: '',
                  house: '',
                  block: '',
                  flat: '',
                  totalarea: '',
                  livingarea: '',
                  group: 'false',
                },
                family: '',
                projectid: '',
                guardpost: ''
              },
              phoneTokens: {
                '#': {
                  pattern: /\d/
                },
                '!': {escape: true}
              },
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
        methods: {
          getRoles(){
            axios.post(this.getRolesRoute).then((response) =>{
              _.each(response.data, (value, key) => {
                if(value.id<3){
                  this.roles['Группа управляющих'].push(value)
                }else if(value.id<7){
                  this.roles['Группа собственника'].push(value)
                }else{
                  this.roles['Группа работников'].push(value)
                }
              })
            })
          },
          getOrganizations(){
            axios.post(this.getOrganizationsRoute).then((response) =>{
              this.organizations.options = response.data
            })
          },
          getFamilies(){
            axios.post(this.getFamiliesRoute).then((response) =>{
              this.families.options = response.data
            })
          },
          getFamilyProjects(){
            axios.post(this.getFamilyProjectsRoute,{id : this.families.value}).then((response) =>{
              this.projects.options = response.data
            })
          },
          getProjectGroups(){
            axios.post(this.getProjectGroupsRoute).then((response) =>{
              this.projectGroups.options = response.data
            })
          },
          getActivities(){
            axios.post(this.getActivitiesRoute).then((response) =>{
              this.activities.options = response.data
            })
          },
          getEquipments(){
            axios.post(this.getEquipmentsRoute).then((response) =>{
              this.equipments.options = response.data
            })
          },
          getProjects(){
            axios.post(this.getOptionProjectsRoute).then((response) =>{
              this.projects.options = response.data
            })
          },
          getGuardPost(){
            axios.post(this.getGuardPostsRoute).then((response) =>{
              this.guardposts.options = response.data
            })
          },
          getProjectTypes(){
            axios.post(this.getProjectTypesRoute).then((response) =>{
              this.projectTypes = response.data
            })
          },
          register(){
            if(this.$v.form.general.$invalid){
              this.$v.form.general.$touch()
            }else if(this.form.role === 'Менеджер' && (this.$v.form.organization.$invalid)){
              this.$v.form.organization.$touch()
            }else if(this.form.role === 'Собственник' && (this.$v.form.project.$invalid)){
              this.$v.form.project.$touch()
            }else if((this.form.role === 'Член семьи' || this.form.role === 'Представитель собственника') && (this.$v.form.family.$invalid)){
              this.$v.form.family.$touch()
            }else if((this.form.role === 'Охранник территории/дома') && (this.$v.form.family.$invalid || this.$v.form.projectid.$invalid)){
              his.$v.form.family.$touch()
              this.$v.form.projectid.$touch()
            }else if((this.form.role === 'Охранник' || this.form.role === 'Диспетчер') && (this.$v.form.projectid.$invalid)){
              this.$v.form.projectid.$touch()
            }else if((this.form.role === 'Охранник на посту') && (this.$v.form.guardpost.$invalid)){
              this.$v.form.guardpost.$touch()
            }else{
              axios.post(this.RegisterUserRoute, this.form).then((response) =>{
                console.log(response.data)
                // сбросить все поля
                for (let input in this.form.general) {
                    this.form.general[input] = ''
                }
                this.reset()

                this.success = true
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.success = false
                }, 5000)

              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
          },
          reset(){
            this.errors =[]
            for (let input in this.form.project) {
                this.form.project[input] = ''
            }
            for (let input in this.form.car) {
                this.form.car[input] = ''
            }
            this.form.role = ''
            this.form.role = ''
            this.form.family = ''
            this.form.guardpost = ''
            this.form.organization = ''
            this.form.general.dateofbirth = moment(new Date(), 'DD.MM.YYYY').format('YYYY-MM-DD')
            this.organizations.value = null
            this.projectGroups.value = null
            this.families.value = null
            this.projects.value = null

            this.car= false
            this.form.car.body = 'Легковое'
            this.profile = false

            this.activities.value = null
            this.equipments.value = null
            this.guardposts.value = null

            this.equipment = false,
            this.projectGroup = false
            this.form.group = 'false'
            this.$v.$reset()
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': !validation.$error && validation.$dirty,
             }
          },
          address (event){
            this.form.project.address = event.value
            this.$v.form.project.address.$reset()
            this.form.project.region = event.data.region_with_type
            this.form.project.city = event.data.city
            this.form.project.street = event.data.street_with_type
            this.form.project.house = event.data.house
            this.form.project.block =event.data.block
            this.form.project.flat = event.data.flat
          },
        },
        computed: {
          hideOrganization() {
            if(this.form.role === 'Менеджер'){
              return true
            }else{
              return false
            }
          },
          hideNewProject() {
            if(this.form.role === 'Собственник'){
              return true
            }else{
              return false
            }
          },
          hideFamilies() {
            if(this.form.role === 'Член семьи' || this.form.role === 'Представитель собственника' || this.form.role === 'Охранник территории/дома'){
              return true
            }else{
              return false
            }
          },
          hideProjects() {
            if((this.form.role === 'Охранник территории/дома' && this.families.value != null) || this.form.role === 'Охранник' || this.form.role === 'Диспетчер'){
              return true
            }else{
              return false
            }
          },
          hideActivities() {
            if(this.form.role === 'Работник'){
              return true
            }else{
              return false
            }
          },
          hideGuardPosts() {
            if(this.form.role === 'Охранник на посту'){
              return true
            }else{
              return false
            }
          },
        },
        watch: {
          'families.value': function (newVal, oldVal){
             if(newVal != null && this.form.role === 'Охранник территории/дома'){
               this.projects.value= null
               this.getFamilyProjects()
             }
         },
         'form.role': function (newVal, oldVal){
            if(newVal === 'Охранник' || newVal === 'Диспетчер' ){
              this.getProjects()
            }
        },
        car: function (newVal, oldVal){
          if(newVal == false){
            for (let input in this.form.car) {
                this.form.car[input] = ''
            }
            this.form.car.body = 'Легковое'
          }

        },
        },
        validations: {
          form: {
            general:{
              fio: {
                required,
                alpha
              },
              phone: {
                required,
                minLength: minLength(10)
              },
              email: {
                required,
                email
              },
            },
            organization:{
              required
            },
            project:{
              name: {
                required
              },
              type: {
                required
              },
              address: {
                required
              },
              region: {
                required
              },
              city: {
                required
              },
              street: {
                required
              },
              house: {
                required
              },
              flat: {
                required
              },
              totalarea: {
                required
              },
              livingarea: {
                required
              },
              group: {
                required
              }
            },
            family: {
              required
            },
            projectid: {
              required
            },
            guardpost: {
              required
            },
          },
        },
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
.vue-dadata__input {
    font-size: 14px;
    width: 100%;
    height: 47px;
    outline: 0;
    border-radius: 4px;
    border: 1px solid #ced4da !important;
    transition: .3s;
    box-sizing: border-box;
    padding: 0 5px;
}
</style>
