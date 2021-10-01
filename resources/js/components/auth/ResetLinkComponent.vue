<template>
  <div class="container">
      <div class="row vh-100 justify-content-center align-items-center">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-header text-center"><h3>Получение ссылки на восстановление</h3></div>

                  <div class="card-body">
                      <div v-if="success" class="alert alert-success" role="alert">
                        {{message}}
                      </div>
                      <form class="form-signin" novalidate>
                          <div class="form-floating mb-3">
                            <input id="email" type="email" v-model="form.email" class="form-control" :class="{'is-invalid':errors && errors.email, 'is-valid': success}" aria-describedby="emailHelp" placeholder="name@example.com" autofocus>
                            <label for="email"><i class="bi bi-envelope-fill"></i> Ваш E-mail</label>
                            <div v-if="errors && errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
                            <div id="emailHelp" class="form-text small">Укажите e-mail, использованный при регистрации (на него придет ссылка)</div>
                          </div>
                          <div class="d-grid gap-2 mb-1">
                            <button @click.prevent="sendLink" type="submit" class="btn btn-block btn-primary text-white">
                                Отправить ссылку на восстановление
                            </button>
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
        props: ['passwordEmailRoute'],
        mounted() {
            console.log('Компонент загружен')
        },
        data() {
            return {
                errors: [],
                success: false,
                message: '',
                form: {
                  email: '',
                }
            }
        },
        methods:{
          sendLink(){
             axios.post(this.passwordEmailRoute, this.form).then((response) =>{
               this.errors = []
               this.form.email = ''
               this.success = true
               this.message = response.data.message
               // убрать сообщение о регистрации
               setTimeout(() => {
                 this.success = false
                 this.message = ''
               }, 5000)

             }).catch((error) =>{
                 this.errors = error.response.data.errors
             })
         }
        }
    }
</script>
