<template>
  <div class="container">
      <div class="row vh-100 justify-content-center align-items-center">
          <div class="col-md-6">
            <div v-if="success" class="d-flex justify-content-center my-2">
              <button class="btn btn-success text-white" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Идет переадресация
              </button>
            </div>
              <div class="card">
                  <div class="card-header text-center"><h3>Регистрация</h3></div>

                  <div class="card-body">
                      <div v-if="success" class="alert alert-success" role="alert">
                        {{message}}
                      </div>
                      <form class="form-signin" novalidate>
                        <div v-if="unchecked">
                          <div class="form-floating mb-3">
                            <input @blur="$v.ref.code.$touch()"
                                   :class="status($v.ref.code)"
                                   v-model="ref.code"
                                   id="code" type="text" class="form-control" aria-describedby="emailHelp" placeholder="carbon">
                            <label for="code"><i class="bi bi-link-45deg"></i> Ваш регистрационный код</label>
                            <div class="invalid-feedback" v-if="!$v.ref.code.required">{{ reqText }}</div>
                            <div v-if="errors && errors.code" class="text-danger">{{ errors.code }}</div>
                          </div>
                          <div class="d-grid gap-2 mb-1">
                            <button @click.prevent="checkCode" type="submit" class="btn btn-block btn-primary text-white">
                                Пройти проверку
                            </button>
                          </div>
                        </div>
                        <div v-else>
                          <div class="form-floating mb-3">
                            <input @blur="$v.form.login.$touch()"
                                   :class="status($v.form.login)"
                                   v-model="form.login"
                                   id="login" type="text" class="form-control" aria-describedby="emailHelp" placeholder="carbon">
                            <label for="login"><i class="bi bi-at"></i> Ваш логин</label>
                            <div class="invalid-feedback" v-if="!$v.form.login.required">{{ reqText }}</div>
                            <div v-if="errors && errors.login" class="text-danger">{{ errors.login[0] }}</div>
                          </div>
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
                            <button @click.prevent="register" type="submit" class="btn btn-block btn-primary text-white">
                                Зарегестрироваться
                            </button>
                          </div>
                        </div>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
    import {required, email, minLength, sameAs, helpers} from 'vuelidate/lib/validators'
    const alpha = helpers.regex('alpha', /^[а-яёА-ЯЁ ]*$/)
    export default {
        props: ['loginRoute','registerRoute'],
        mounted() {
            console.log('Компонент загружен')
        },
        data() {
            return {
                errors: [],
                success: false,
                message: '',
                reqText: 'Поле обязательно для заполнения',
                minLengthText: 'Минимальная длина 6 символов!',
                passwordConfirmText: 'Пароли не совпадают',
                unchecked: true,
                ref:{
                  code: '',
                },
                form: {
                  id: '',
                  login:'',
                  password: '',
                  password_confirmation: '',
                },
                phoneTokens: {
                  '#': {
                    pattern: /\d/
                  },
                  '!': {escape: true}
                },
            }
        },
        methods:{
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': !validation.$error && validation.$dirty,
             }
          },
          checkCode(){
            if(this.$v.ref.$invalid){
              this.$v.ref.$touch()
            }else{
              axios.post('/api/check-code', {code: this.ref.code}).then((response) =>{
                this.form.id = response.data
                this.unchecked = false
                // сбросить все поля
                for (let input in this.ref) {
                    this.ref[input] = ''
                }
                this.$v.ref.$reset()
              }).catch((error) =>{
                  this.errors = error.response.data.errors
                  console.log(this.errors)
              })
            }
          },
          register(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/register', this.form).then((response) =>{
                this.errors = []
                this.form.email = ''
                this.success = true
                this.message = response.data.message
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.success = false
                  this.message = ''
                  location.href = '/login'
                }, 3000)

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
                login: {
                  required
                },
                password: {
                  required,
                  minLength: minLength(6)
                },
                password_confirmation: {
                  required,
                  sameAs: sameAs('password')
                },
            },
            ref: {
              code: {
                required,
              }
            }
        },
    }
</script>
