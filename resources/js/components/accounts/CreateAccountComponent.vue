<template>
  <div>
    <Loader v-if="loader"/>
    <div v-else-if="!create">
      <p class="lead mb-1">Свяжитесь по телефону для подключения ЛС</p>
      <div class="list-group">
        <label v-for="profile in list" :key="profile.id" class="list-group-item">
          {{profile.fio}} ({{profile.role}}) <a :href="'tel:+7'+profile.phone">+7{{profile.phone}}</a>
        </label>
      </div>
    </div>
    <div v-else>
      <transition name="slide-fade">
        <div v-if="updateAccountMessage" class="alert alert-success" role="alert">
           <p>Вы успешно пополнили счет!</p>
        </div>
      </transition>
      <div class="form-floating mb-3">
        <input  @blur="$v.form.number.$touch()"
                :class="status($v.form.number)"
                v-model="form.number" type="text" class="form-control" id="floatingNumber" placeholder="1200"/>
        <label for="floatingNumber">Введите номер ЛС</label>
        <div v-if="errors && errors.amount" class="text-danger">{{ errors.number[0] }}</div>
      </div>
      <div class="form-floating mb-3">
        <input  @blur="$v.form.amount.$touch()"
                :class="status($v.form.amount)"
                v-model.number="form.amount" type="number" class="form-control" min="0" id="floatingAmount" placeholder="1200"/>
        <label for="floatingAmount">Введите баланс, в рублях</label>
        <div v-if="errors && errors.amount" class="text-danger">{{ errors.amount[0] }}</div>
      </div>
      <div class="d-grid gap-2">
        <button @click.prevent="createAccount"  class="btn btn-primary btn-lg text-white"type="submit">Подключить ЛС</button>
      </div>
    </div>
  </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['profileId'],
        mounted() {
            console.log('Component mounted.')
        },
        created() {
          this.checkAccess()
        },
        data() {
            return {
              loader: true,
              errors: [],
              create: false,
              list: [],
              form: {
                id: this.profileId,
                number: '',
                amount: 0,
              }
            }
        },
        methods: {
          checkAccess(){
            axios.post('/api/access-create-personal-account', {id: this.idProject}).then((response) =>{
              response.data ===  '200' ? this.create = true : this.list = response.data
              this.loader = false
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'ring': validation.$error,
               'is-valid': validation.required,
             }
          },
          createAccount(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/personal-account/create', this.form).then((response) =>{
                for (let input in this.form) {
                    this.form[input] = null
                }
                this.$emit('refreshPersonalAccount');
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
          }
        },
        validations: {
          form: {
            amount: {
              required
            },
            number: {
              required
            }
          },
        }
    }
</script>
