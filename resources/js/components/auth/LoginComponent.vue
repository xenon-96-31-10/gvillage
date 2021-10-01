<template>
    <div class="container">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-sm-6">
                <div v-if="success" class="d-flex justify-content-center my-2">
                  <button class="btn btn-success text-white" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Идет переадресация
                  </button>
                </div>
                <div class="card">
                    <div class="card-header text-center"> <h3>Авторизация на сайте</h3></div>

                    <div class="card-body">
                      <form class="form-signin" novalidate>

                          <div class="form-floating mb-3">
                            <input type="text" v-model="form.login" class="form-control" :class="{'is-invalid':errors && errors.login, 'is-valid': success}" id="login" placeholder="name@example.com">
                            <label for="login"><i class="bi bi-envelope-fill"></i> Ваш логин</label>
                            <div v-if="errors && errors.login" class="invalid-feedback">{{ errors.login[0] }}</div>
                          </div>
                          <div class="form-floating mb-3">
                            <input type="password" v-model="form.password" class="form-control" :class="{'is-invalid':errors && errors.password, 'is-valid': success}" id="password" placeholder="Password">
                            <label for="password"><i class="bi bi-key-fill"></i> Ваш пароль</label>
                            <div v-if="errors && errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
                          </div>
                          <div class="form-check mb-3">
                            <input class="form-check-input" v-model="form.remember" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">
                              Запомнить меня
                            </label>
                          </div>

                          <div class="d-grid gap-2 mb-1">
                            <button @click.prevent="loginUser" type="submit" class="btn btn-block btn-primary text-white">
                                Войти
                            </button>
                          </div>

                          <div class="text-end">
                            <a class="btn btn-link text-dark mr-0 text-center" :href="passwordRequestRoute">
                                Забыли пароль?
                            </a>
                          </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            console.log(this.form.remember)
        },
        props: ['passwordRequestRoute'],
        data() {
            return {
                errors: [],
                success: false,
                form: {
                  login: '',
                  password: '',
                  remember: false,
                }
            }
        },
        methods:{
          loginUser(){
             axios.post('/login', this.form).then(() =>{
               this.success = true;
               this.errors = [];
               location.reload()
             }).catch((error) =>{
                 this.errors = error.response.data.errors;
                 console.log(this.errors)
             })
         }
        }

    }
</script>
