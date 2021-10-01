<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div v-if="data.profile.role === 'По умолчанию' || data.profile.code == null">
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
          <div class="d-grid gap-2">
            <button @click.prevent="updateRole" class="btn btn-primary btn-lg text-white" type="submit">Сгенерировать код регистрации</button>
          </div>
        </div>
        <div v-else>
          <h1>Код для регистрации</h1>
          <div class="input-group mb-3">
            <button class="btn btn-outline-secondary" type="button" id="button-addon1" @click="doCopy"><i class="bi bi-clipboard-plus"></i> Копиповать</button>
            <input v-model="data.profile.code" type="text" class="form-control copyLinkInput" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
          </div>
          <transition name="slide-fade">
            <div v-if="success.check" class="alert alert-success" role="alert">
               {{success.text}}
            </div>
          </transition>
        </div>
      </div>
    </div>
</template>

<script>
    import {required, minLength, email} from 'vuelidate/lib/validators'
    import moment from 'moment';
    export default {
        props: ['profileId'],
        mounted() {
            console.log('Component mounted.')
            this.getProfile()
        },
        created(){
          this.getRoles()
        },
        methods: {
          getProfile(){
            this.loader = true
            axios.post('/api/get-profile', { id : this.profileId}).then((response) =>{
              this.data = response.data
              this.data.profile.role != 'По умолчанию' ? this.form.role = this.data.profile.role : this.form.role = ''
              this.loader = false
            })
          },
          getRoles(){
            axios.post('/api/get-roles').then((response) =>{
              this.roles = response.data
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          updateRole(){
            axios.post('/profile/update-role', this.form).then((response) =>{
              this.errors = []
              this.getProfile()
            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
          },
          doCopy: function (e) {
            let selectEl = $(e.target).parents('.input-group').find('.copyLinkInput')[0];
            selectEl.select();
            document.execCommand("copy");
            this.success.check = true
            this.success.text = 'Код скопирован! Вы можете его отправить пользователю, удобным для Вас способом!'

            setTimeout(() => {
              this.success.check = false
              this.success.text = ''
            }, 3000)
          }
        },
        data() {
            return {
              data: [],
              loader: true,
              success: {
                check: false,
                text: ''
              },
              roles: [],
              errors: [],
              form: {
                id: this.profileId,
                role: '',
              },
              requiredText: 'Это поле должно быть заполнено !',

            }
        },
        validations: {
          form: {
            role: {
              required
            },
          },
        },
    }
</script>
<style scoped>
.slide-fade-enter-active {
  transition: all 0.5s ease;
}
.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}
.table{
  border-collapse: separate!important;
  border-spacing: 0 5px;
  cursor: pointer;
}
.offcanvas-bottom{
  height: 40vh!important;
}
</style>
