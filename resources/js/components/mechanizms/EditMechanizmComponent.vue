<template>
  <div>
    <transition name="slide-fade">
      <div v-if="updateMessage" class="alert alert-success" role="alert">
         Вы успешно обновлили!
      </div>
    </transition>
    <Loader v-if="loader"/>
    <div v-else class="row justify-content-center">
        <p class="lead mb-2">Редактировать технику:</p>
        <div class="col-12">
          <div class="form-floating mb-3">
            <input  @blur="$v.form.number.$touch()"
                    :class="status($v.form.number)"
                    v-model="form.number" type="text" class="form-control" id="floatingNumber" placeholder="А100АА77"/>
            <label for="floatingNumber">Номер</label>
            <div v-if="errors && errors.number" class="alert alert-danger mt-1" role="alert">{{ errors.number[0] }}</div>
            <div v-if="errors && errors.type" class="alert alert-danger mt-1" role="alert">
              {{ errors.type }}
            </div>
          </div>

          <div class="form-floating mb-3">
            <input v-model="form.model" type="text" class="form-control" id="floatingModel" placeholder="Тойота">
            <label for="floatingModel">Марка техники</label>
          </div>
          <div class="form-floating mb-3">
            <select v-model="form.type" class="form-select" id="floatingSelectType" aria-label="Тип техники">
              <option value="Спецтехника до 1,5 т">Спецтехника до 1,5 т</option>
              <option value="Спецтехника от 1,5 т до 3 т">Спецтехника от 1,5 т до 3 т</option>
              <option value="Спецтехника от 3 т до 7 т">Спецтехника от 3 т до 7 т</option>
              <option value="Спецтехника свыше 7 т">Спецтехника свыше 7 т</option>
              <option value="Грузовой">Грузовой</option>
              <option value="Длинномер/Еврофура">Длинномер/Еврофура</option>
            </select>
            <label for="floatingSelectType" class="h5">Тип техники</label>
          </div>
          <div class="form-floating mb-3">
            <input v-model="form.name" type="text" class="form-control" id="floatingName" placeholder="Кран">
            <label for="floatingName">Название</label>
          </div>
          <div class="form-floating mb-3">
            <input v-model="form.color" type="text" class="form-control" id="floatingColor" placeholder="Красный">
            <label for="floatingColor">Цвет техники</label>
          </div>

          <div class="alert alert-info" role="alert">
            {{form.owner}}
          </div>
        </div>

        <div v-if="addOwner" class="col-12">
          <div>
            <p class="lead mb-2">Укажите владельца:</p>
            <search-item-component type="owner" @checkItem="selectOwnerId"></search-item-component>
          </div>
        </div>
        <div class="col-12 my-2">
          <div class="form-check form-switch">
            <input v-model="addOwner" class="form-check-input" type="checkbox" id="addOwner">
            <label class="form-check-label" for="addOwner">Сменить/указать владельца авто</label>
          </div>
        </div>
        <div class="d-grid gap-2 mb-2">
          <button @click.prevent="updateMechanizm" class="btn btn-primary btn-lg text-white"type="submit">Обновить информацию</button>
        </div>

      </div>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['id'],
        mounted() {
            console.log('Компонент по регистрации новой техники установлен.')
            this.getMechanizm()
        },
        data() {
            return {
              loader: true,
              errors: [],
              updateMessage: false,
              addOwner: false,
              form: {
                id: null,
                number: '',
                model: '',
                type: 'Спецтехника до 1,5 т',
                name: '',
                color: '',
                owner: null,
              }
            }
        },
        methods: {
          getMechanizm(){
            this.loader = true
            axios.post('/api/get-mechanizm', { id : this.id}).then((response) =>{
              this.form = response.data
              this.form.newOwner = null
              this.addOwner = false
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
          selectOwnerId(id){
            this.form.newOwner = id
          },
          updateMechanizm(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/mechanizm/update', this.form).then((response) =>{
                this.getMechanizm()
                this.createMessage = true;
                // убрать сообщение о регистрации
                setTimeout(() => {
                  this.createMessage = false
                }, 5000)
                this.$emit('refreshList');
              }).catch((error) =>{
                console.log(error.response.data)
                  this.errors = error.response.data.errors
              })
            }
          },
        },
        watch:{
          'addOwner': function (newVal){
             if(newVal === false){
               this.form.newOwner = null
             }
           },
        },
        validations: {
          form: {
            number: {
              required
            }
          },
        }
    }
</script>
