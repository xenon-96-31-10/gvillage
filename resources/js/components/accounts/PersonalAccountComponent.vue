<template>
  <div>
    <transition name="slide-fade">
      <div v-if="updateAccountMessage" class="alert alert-success" role="alert">
         <p>Вы успешно пополнили счет!</p>
      </div>
    </transition>
    <Loader v-if="loader"/>
    <div v-else>
      <div v-if="account === 'Без ЛС'">
        <div class="alert alert-primary mb-2" role="alert">
          <h4 class="alert-heading">К сожалению, к вашему аккаунту еще не подключен профиль.</h4>
          Ничего страшного, это можно исправить!
          <hr>
          <div class="d-grid gap-2">
            <button v-if="createAccount" class="btn btn-dark" type="button" @click="createAccount = false">Отменить</button>
            <button v-else class="btn btn-light" type="button" @click="createAccount = true">Подключить</button>
          </div>
        </div>
        <create-account-component v-if="createAccount" :profile-id="profileId" @refreshProfile="getPersonalAccount"/>
      </div>
      <div v-else>
        <div class="card my-2 rounded-3 shadow-sm border-primary">
          <div class="card-header py-3 text-white bg-primary border-primary">
            <h5 class="my-0 fw-normal"><i class="bi bi-credit-card-2-back-fill"></i> {{account.number}}</h5>
          </div>
          <div class="card-body">
            <p class="card-title pricing-card-title">На балансе: <strong class="text-success">{{account.balance}}</strong> &#8381;</p>
            <p class="card-title pricing-card-title">Заморожено для пропусков: <strong class="text-primary">{{account.frozen}}</strong> &#8381;</p>
            <p class="card-title pricing-card-title">Доступно: <strong class="text-info">{{account.balance - account.frozen}}</strong> &#8381;</p>
          </div>
        </div>
        <div class="card my-3 rounded-3 shadow-sm border-dark">
          <div class="card-header py-3 text-white bg-dark border-dark d-flex justify-content-between">
            <h5 class="my-0 fw-normal">Пополнение счета</h5>

            <button class="btn btn-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRefill" aria-expanded="false" aria-controls="collapseRefill">
              <i class="bi bi-plus"></i>
            </button>
          </div>
          <div class="collapse" id="collapseRefill">
            <div class="card-body">
              <refill-account-component :idAccount="account.id" :key="account.id" @refreshList="gotMoney"></refill-account-component>
            </div>
          </div>
        </div>
        <div class="card my-3 rounded-3 shadow-sm border-secondary">
          <div class="card-header py-3 text-white bg-secondary border-secondary d-flex justify-content-between">
            <h5 class="my-0 fw-normal">История пополнений/списаний по счету</h5>

            <button class="btn btn-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
              <i class="bi bi-plus"></i>
            </button>
          </div>
          <div class="collapse" id="collapseReport">
            <div class="card-body">
              <report-account-component :id="account.id" :key="account.id"/>
            </div>
          </div>
        </div>

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
          this.getPersonalAccount()
        },
        data() {
            return {
              loader: true,
              account: [],
              createAccount: false,
              updateAccountMessage: false,
            }
        },
        methods: {
          getPersonalAccount(){
            this.loader = true
            axios.post('/api/get-personal-account', {id: this.profileId}).then((response) =>{
              this.account = response.data
              this.loader = false
            })
          },
          gotMoney(){
            this.updateAccountMessage = true,
            this.getPersonalAccount()
            setTimeout(() => {
              this.updateAccountMessage = false
            }, 5000)
          },
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
