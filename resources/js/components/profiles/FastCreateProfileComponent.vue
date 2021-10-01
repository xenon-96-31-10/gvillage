<template>
  <div>
      <form enctype="multipart/form-data" novalidate>
        <p class="lead mb-2">Добавить человека (без регистрации):</p>
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
        <div class="row my-1">
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

        <div class="d-grid gap-2 mb-2">
          <button @click.prevent="createProfile"  class="btn btn-primary btn-lg text-white"type="submit">Добавить человека</button>
        </div>
        <hr>
      </form>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['fio', 'project'],
        mounted() {
          console.log('Компонент по быстрому добавлению профиля для пропуска.')
          if(this.fio){
            this.fio = this.fio.split(' ')
            this.form.surname = this.fio[0]
            this.form.name = this.fio[1] || ''
            this.form.lastname = this.fio[2] || ''
          }
          if(this.project){            
            this.form.project = this.project
          }
        },
        data() {
            return {
              errors: [],
              form: {
                surname: '',
                name: '',
                lastname: '',
                avatar: null,
                passport: null,
                project: null,
              }
            }
        },
        methods: {
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
              axios.post('/profile/fast-create', formData, config).then((response) =>{
                var data = {
                  'id': response.data,
                  'title': this.form.surname + ' '+ this.form.name,
                }
                // сбросить все поля
                for (let input in this.form) {
                    this.form[input] = ''
                }
                this.form.avatar = null
                this.form.passport = null
                this.$v.$reset()
                this.$emit('refreshList', data);
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
            }
          },
        }
    }
</script>
