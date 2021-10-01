<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="row">
            <div class="col-sm-4 mb-2 mb-sm-0">
              <div class="card my-2">
                <img v-if="data.avatar != 'Фото отсутствует'" :src="data.avatar" class="card-img-top" alt="Аватар">
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-person-circle card-img-top my-1 mx-auto" viewBox="0 0 16 16">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>
                <div class="bg-transparent p-1" style="position: absolute; top: 0; right: 0;">
                  <label
                        class="btn btn-primary text-white" for="avatar">
                        <input type="file" @change="selectAvatar" id="avatar" class="d-none"/>
                        <i class="bi bi-person-bounding-box"></i>
                        </label>
                        <button v-if="data.avatar != 'Фото отсутствует'" type="button" class="btn btn-danger text-white ms-2" @click="deleteAvatar"><i class="bi bi-trash-fill"></i></button>
                </div>
                <div v-if="errors && errors.avatar" class="card-body">
                  <ul class="list-group">
                    <li v-for="error in errors" class="list-group-item list-group-item-danger">{{error[0]}}</li>
                  </ul>
                </div>
              </div>
              <personal-account-component :profile-id="profileId" :key="profileId"/>
            </div>
            <div class="col-sm-8">
                <transition name="slide-fade">
                  <div v-if="success.check" class="alert alert-success" role="alert">
                     {{success.text}}
                  </div>
                </transition>
                <div class="card my-2">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Общая информация</h5>
                  </div>

                  <div class="card-body">
                    <form novalidate>
                      <div class="form-floating mb-3">
                        <input  @blur="$v.form.fio.$touch()"
                                :class="status($v.form.fio)"
                                v-model="form.fio" type="text" class="form-control" id="floatingfio" placeholder="Иванов">
                        <label for="floatingfio">ФИО</label>
                      </div>
                      <div class="form-floating mb-3">
                        <select @blur="$v.form.sex.$touch()"
                                :class="status($v.form.sex)"
                                v-model="form.sex" class="form-select" id="floatingSelectSex" aria-label="Выбор пола">
                          <option disabled value="">Выберите пол...</option>
                          <option value="Мужской">Мужской</option>
                          <option value="Женский">Женский</option>
                        </select>
                        <label for="floatingSelectSex" class="h5">Ваш пол <i class="bi bi-people"></i></label>
                        <div class="invalid-feedback" v-if="!$v.form.sex.required">{{ requiredText }}</div>
                      </div>

                      <div class="form-floating mb-3">
                        <VueDatePicker @blur="$v.form.dateofbirth.$touch()"
                                       :class="status($v.form.dateofbirth)"
                                       v-model="form.dateofbirth" :visible-years-number="100" class="form-control mb-2" value="DD.MM.YYYY" format="DD.MM.YYYY" id="date"/>
                        <label for="date" class="h5">Дата вашего рождения</label>
                        <div class="invalid-feedback" v-if="!$v.form.dateofbirth.required">{{ requiredText }}</div>
                      </div>


                      <div class="d-grid gap-2">
                        <button @click.prevent="update" class="btn btn-primary btn-lg text-white" type="submit">Обновить</button>
                      </div>

                    </form>
                  </div>
                </div>

                <div class="card my-2">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Контактная информация</h5>
                  </div>

                  <div class="card-body">
                    <form novalidate>
                      <div class="form-floating mb-3">
                        <input v-model="contacts.address" type="text" class="form-control" id="floatingAddress" placeholder="Москва">
                        <label for="floatingAddress"><i class="bi bi-house-fill"></i> Адрес проживания</label>
                      </div>
                      <div class="form-floating mb-3">

                        <the-mask
                        @blur.native="$v.contacts.phone.$touch()"
                        :mask="['!+!7(###)-###-##-##']"
                        :tokens="phoneTokens"
                        :class="status($v.contacts.phone)"
                        v-model="contacts.phone"
                        class="form-control"
                        id="floatingPhone" />
                        <label for="floatingPhone" class="h5">Ваш номер телефона</label>
                        <div class="invalid-feedback" v-if="!$v.contacts.phone.required">{{ requiredText }}</div>
                        <div class="invalid-feedback" v-if="!$v.contacts.phone.minLength">Номер должен содержать 10 цифр без кода страны</div>
                        <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                      </div>

                      <div class="form-floating mb-3">
                        <input @blur="$v.contacts.email.$touch()"
                               :class="status($v.contacts.email)"
                               v-model="contacts.email"
                               type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                        <div class="invalid-feedback" v-if="!$v.contacts.email.required">{{ requiredText }}</div>
                        <div class="invalid-feedback" v-if="!$v.contacts.email.email">Поле должно быть e-mail адресом</div>
                        <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                      </div>

                      <div class="d-grid gap-2">
                        <button @click.prevent="updateContacts" class="btn btn-primary btn-lg text-white" type="submit">Обновить</button>
                      </div>

                    </form>
                  </div>
                </div>

                <profile-documents-component :profile-id="profileId" :key="profileId"/>
                <profile-cars-component :profile-id="profileId" :key="profileId"/>
                <profile-mechanizms-component :profile-id="profileId" :key="profileId"/>

            </div>
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
            setTimeout(() => {
              this.getProfile()
            }, 3000)
        },
        methods: {
          getProfile(){
            this.loader = true
            axios.post('/api/get-profile', { id : this.profileId}).then((response) =>{
              this.data = response.data
              this.form.dateofbirth = moment(this.data.dateofbirth, 'DD.MM.YYYY').format('YYYY-MM-DD')
              this.form.fio = this.data.profile.fio
              this.form.sex = this.data.profile.sex
              this.data.profile.address != 'Без уточнения' ? this.contacts.address = this.data.profile.address : this.contacts.address = ''
              this.contacts.phone = this.data.profile.phone
              this.contacts.email = this.data.profile.email
              this.docs = this.data.documents
              this.loader = false
            })
          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          },
          selectAvatar(event) {
            this.avatar = event.target.files[0]
            if(this.avatar != null){
              this.uploadAvatar()
            }
          },
          uploadAvatar(){
            let formData = new FormData()
            formData.append('avatar', this.avatar)
            formData.append('id', this.profileId)

            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
            }
            axios.post('/profile/upload-avatar', formData, config).then((response) =>{
              this.errors = []
              this.getProfile()


              this.success.check = true
              this.success.text = 'Вы успешно загрузили Аватар !'

              setTimeout(() => {
                this.success.check = false
                this.success.text = ''
              }, 5000)
            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
          },
          deleteAvatar(){
            axios.post('/profile/delete-avatar', {id : this.profileId}).then((response) =>{
              this.errors = []
              this.getProfile()
              this.success.check = true
              this.success.text = 'Вы успешно удалили аватар!'

              setTimeout(() => {
                this.success.check = false
                this.success.text = ''
              }, 5000)

            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
          },
          update(){
            axios.post('/profile/update', this.form).then((response) =>{
              this.errors = []
              this.getProfile()
              this.success.check = true
              this.success.text = 'Вы успешно обновили информацию о себе!'

              setTimeout(() => {
                this.success.check = false
                this.success.text = ''
              }, 5000)

            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
          },
          updateContacts(){
            axios.post('/profile/update-contacts', this.contacts).then((response) =>{
              this.editContacts = false
              this.errors = []
              this.getProfile()
              this.success.check = true
              this.success.text = 'Вы успешно обновили свои контактные данные!'

              setTimeout(() => {
                this.success.check = false
                this.success.text = ''
              }, 5000)

            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
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
              errors: [],
              form: {
                id: this.profileId,
                fio: '',
                sex: '',
                dateofbirth: null,
              },
              contacts: {
                id: this.profileId,
                address: '',
                phone: null,
                email: '',
              },
              avatar: null,
              requiredText: 'Это поле должно быть заполнено !',
              phoneTokens: {
                '#': {
                  pattern: /\d/
                },
                '!': {escape: true}
              }
            }
        },
        validations: {
          form: {
            fio: {
              required
            },
            sex: {
              required
            },
            dateofbirth: {
              required
            },
          },
          contacts: {
            phone: {
              required,
              minLength: minLength(10)
            },
            email: {
              required,
              email
            },
          }
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
