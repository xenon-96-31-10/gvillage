<template>
  <div>
      <transition name="slide-fade">
        <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           Вы успешно создали профиль, а также был сгенерирован код {{code}}.
           Пользователь должен его использовать, чтобы самым быстрым способом получить доступ к сервису без сложных проверок!
        </div>
      </transition>
      <form enctype="multipart/form-data" novalidate>
        <p class="lead mb-2">Предавторизация профиля человека:</p>
        <div class="row g-2 mb-3">
          <div class="col-sm">
            <div class="form-floating">
              <input  @blur="$v.form.surname.$touch()"
                      :class="status($v.form.surname)"
                      v-model="form.surname" type="text" class="form-control" id="floatingSurname" placeholder="Иванов">
              <label for="floatingSurname">Фамилия</label>
            </div>
          </div>
          <div class="col-sm">
            <div class="form-floating">
              <input @blur="$v.form.name.$touch()"
                      :class="status($v.form.name)"
                      v-model="form.name" type="text" class="form-control" id="floatingName" placeholder="Иван">
              <label for="floatingName">Имя</label>
            </div>
          </div>
        </div>
        <div class="form-floating mb-3">
          <input v-model="form.lastname" type="text" class="form-control" id="floatingLastname" placeholder="Иванович">
          <label for="floatingName">Отчество (не обязательно)</label>
        </div>
        <div class="form-floating">
          <select @blur="$v.form.role.$touch()"
                  :class="status($v.form.role)"
                  class="form-select" id="floatingSelect" v-model="form.role" aria-label="Floating label select example">
            <option value="" disabled>Открыть список</option>
            <option v-for="role in roles" :value="role.name">
              {{role.name}}
            </option>
          </select>
          <label for="floatingSelect">Выберите роль пользователя <i class="bi bi-people-fill"></i></label>
          <div class="form-text">Выберите роль, чтобы продолжить.</div>
        </div>
        <div class="row my-2">
          <div class="col-6">
            <div class="d-grid gap-2 mb-1">
              <label  class="btn p-1" :class="{'btn-outline-secondary': form.avatar == null, 'btn-success ': form.avatar != null}" for="avatar">
                      <input type="file" @change="selectAvatar" id="avatar" class="d-none"/>
                      <i class="bi bi-paperclip"></i> <span v-if="form.avatar == null">Добавить фотографию</span><span v-else>Добавлено</span>
                      </label>
            </div>
          </div>
          <div class="col-6">
            <div class="d-grid gap-2 mb-1">
              <label  class="btn p-1" :class="{'btn-outline-secondary': form.passport == null, 'btn-success ': form.passport != null}" for="passport">
                      <input type="file" @change="selectPassport" id="passport" class="d-none"/>
                      <i class="bi bi-paperclip"></i> <span v-if="form.passport == null">Добавить паспорт</span><span v-else>Добавлено</span>
                      </label>
            </div>
          </div>
        </div>
        <div v-if="organizations.options.length > 1" class="mb-3">
          <hr>
          <p class="lead">Укажаите название организации для нового менеджера</p>
          <Multiselect  @blur="$v.form.organization.$touch()"
                        :class="status($v.form.organization)"
                        v-model="organizations.value"
                        v-bind="organizations"
                        @select="form.organization = organizations.value"
                        @change="form.organization = ''"
                      ></Multiselect>
                      <div class="invalid-feedback" v-if="!$v.form.organization.required">Это поле должно быть заполнено!</div>
            <div class="form-text">Напишите в поле название организации для поиска и выбора из вспомогательного списка. Если такого названия нет в базе, то организация добавиться в базу.</div>
        </div>
        <div class="d-grid gap-2 mb-2">
          <button @click.prevent="createProfile"  class="btn btn-primary btn-lg text-white"type="submit">Добавить человека и сгенерировать ключ</button>
        </div>
        <hr>
      </form>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        mounted() {
          console.log('Компонент по быстрому добавлению профиля для пропуска.')
        },
        created(){
          this.getRoles()
          this.getOrganizations()
        },
        data() {
            return {
              errors: [],
              form: {
                surname: '',
                name: '',
                lastname: '',
                role: '',
                organization: '',
                avatar: null,
                passport: null,
                project: null,
              },
              success: false,
              code: '',
              roles: [],
              organizations: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите организацию',
                options: [],
              },
            }
        },
        methods: {
          getRoles(){
            axios.post('/api/get-roles').then((response) =>{
              this.roles = response.data
            })
          },
          getOrganizations(){
            axios.post('/api/get-organizations').then((response) =>{
              this.organizations.options = response.data
              this.organizations.value = this.form.organization =  response.data[0].value
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'ring': validation.$error,
               'is-valid': validation.required,
             }
          },
          selectAvatar(event) {
            this.form.avatar = event.target.files[0]
          },
          selectPassport(event) {
            this.form.passport = event.target.files[0];
          },
          createProfile(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
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
              axios.post('/profile/create', formData, config).then((response) =>{
                this.code = response.data
                this.success = true
                // сбросить все поля
                for (let input in this.form) {
                    this.form[input] = ''
                }
                this.form.avatar = null
                this.form.passport = null
                this.getOrganizations()
                this.$v.$reset()
              })
            }

          }
        },
        validations: {
          form: {
            surname: {
              required
            },
            name: {
              required
            },
            role:{
              required,
            },
            organization:{
              required,
            }
          },
        }
    }
</script>
