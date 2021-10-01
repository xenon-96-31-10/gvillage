<template>
  <div>
    <Loader v-if="loader"/>
    <div v-else-if="!refill">
      <p class="lead mb-1">Свяжитесь по телефону для пополнения ЛС</p>
      <p class="text-muted">Сейчас пополнение ЛС осуществляется через доверенные лица компании.</p>
      <div class="list-group">
        <label v-for="profile in list" :key="profile.id" class="list-group-item">
          {{profile.fio}} ({{profile.role}}) <a :href="'tel:+7'+profile.phone">+7{{profile.phone}}</a>
        </label>
      </div>
    </div>
    <div v-else>
      <div class="form-floating mb-3">
        <input  @blur="$v.form.amount.$touch()"
                :class="status($v.form.amount)"
                v-model="form.amount" type="text" class="form-control" id="floatingAmount" placeholder="1200"/>
        <label for="floatingAmount">Введите сумму пополнения, в рублях</label>
        <div v-if="errors && errors.amount" class="text-danger">{{ errors.amount[0] }}</div>
      </div>
      <div class="d-grid gap-2">
        <button @click.prevent="refillAccount"  class="btn btn-primary btn-lg text-white"type="submit">Пополнить счет</button>
      </div>
    </div>

  </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['idAccount','amount', 'idProject'],
        created() {
          this.checkAccess()
        },
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
              loader: true,
              errors: [],
              refill: false,
              list: [],
              form: {
                id: null,
                amount: null,
              }
            }
        },
        methods: {
          checkAccess(){
            axios.post('/api/access-refill-personal-account', {id: this.idProject}).then((response) =>{
              response.data ===  '200' ? this.refill = true : this.list = response.data
              this.form.id = this.idAccount
              this.form.amount = this.amount
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
          refillAccount(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/personal-account/refill', this.form).then((response) =>{
                for (let input in this.form) {
                    this.form[input] = null
                }
                this.form.id = this.idAccount
                this.$v.$reset()
                this.$emit('refreshList', response.data);
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
            }
          },
        }
    }
</script>
