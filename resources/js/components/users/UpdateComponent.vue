<template>
  <div>
    <div v-if="success" class="alert alert-success" role="alert">
      {{message}}
    </div>
    <Loader v-if="loader"/>
    <div v-else>
      <div class="card mb-3">
        <div class="card-header text-center"><h3>Обновление логина</h3></div>

        <div class="card-body">
          <h5 class="card-title">Ваш действующий логин: {{oldLogin}}</h5>
            <form class="form-login" novalidate>
              <div class="form-floating mb-3">
                <input @blur="$v.short.login.$touch()"
                       :class="status($v.short.login)"
                       v-model="short.login"
                       id="login" type="text" class="form-control" placeholder="Логин">
                <label for="password">Логин</label>
                <div class="invalid-feedback" v-if="!$v.short.login.required">{{ reqText }}</div>
                <div v-if="errors && errors.login" class="text-danger">{{ errors.login[0] }}</div>
              </div>

              <div class="d-grid gap-2 mb-1">
                <button @click.prevent="updateLogin" type="submit" class="btn btn-primary text-white">
                    Обновить логин
                </button>
              </div>
            </form>
        </div>
      </div>
      <div class="card">
        <div class="card-header text-center"><h3>Обновление пароля</h3></div>

        <div class="card-body">
            <form class="form-password" novalidate>
              <div class="form-floating mb-3">
                <input @blur="$v.form.password.$touch()"
                       :class="status($v.form.password)"
                       v-model="form.password"
                       id="password" type="password" class="form-control"placeholder="Новый пароль">
                <label for="password"><i class="bi bi-key-fill"></i> Ваш новый пароль</label>
                <div class="invalid-feedback" v-if="!$v.form.password.required">{{ reqText }}</div>
                <div class="invalid-feedback" v-if="!$v.form.password.minLength">{{minLengthText}}</div>
                <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
              </div>
              <div class="form-floating mb-3">
                <input @blur="$v.form.password_confirmation.$touch()"
                       :class="status($v.form.password_confirmation)"
                       v-model="form.password_confirmation"
                       id="password_confirmation" type="password" class="form-control" placeholder="Новый пароль">
                <label for="password_confirmation"><i class="bi bi-key-fill"></i> Подвердить новый пароль</label>
                <div class="invalid-feedback" v-if="!$v.form.password_confirmation.sameAs">{{passwordConfirmText}}</div>
                <div v-if="errors && errors.password_confirmation" class="text-danger">{{ errors.password_confirmation[0] }}</div>
              </div>
              <div class="d-grid gap-2 mb-1">
                <button @click.prevent="updatePassword" type="submit" class="btn btn-dark text-white">
                    Обновить пароль
                </button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import {required, minLength, sameAs} from 'vuelidate/lib/validators'
    export default {
        mounted() {
            console.log('Компонент загружен')
            this.getLogin()
        },
        data() {
            return {
                loader: true,
                errors: [],
                success: false,
                message: 'Вы успешно обновили данные для входа',
                reqText: 'Поле обязательно для заполнения',
                minLengthText: 'Минимальная длина 6 символов!',
                passwordConfirmText: 'Пароли не совпадают',
                short: {
                  login: '',
                },
                oldLogin: '',
                form: {
                  password: '',
                  password_confirmation: '',
                },
            }
        },
        methods:{
          getLogin(){
            this.loader = true
            axios.post('/api/get-user').then((response) =>{
              this.oldLogin = response.data
              this.loader = false
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': !validation.$error && validation.$dirty,
             }
          },
          updateLogin(){
            if(this.$v.short.login.$invalid){
              this.$v.short.login.$touch()
            }else{
              axios.post('/update-login-password', this.short).then((response) =>{
                this.errors = []
                this.success = true
                this.getLogin()
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.success = false
                }, 5000)
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
         },
          updatePassword(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/update-login-password', this.form).then((response) =>{
                this.errors = []
                this.success = true
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.success = false
                }, 5000)

                // сбросить все поля
                for (let input in this.form) {
                    this.form[input] = ''
                }
                this.$v.form.$reset()
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
         }
        },
        validations: {
            form: {
                password: {
                  required,
                  minLength: minLength(6)
                },
                password_confirmation: {
                  required,
                  sameAs: sameAs('password')
                },
            },
            short: {
              login: {
                required,
              },
            }
        },
    }
</script>
