<template>
  <div>
    <p class="lead mb-2">Добавить технику:</p>
    <div class="form-floating mb-3">
      <input  @blur="$v.form.number.$touch()"
              :class="status($v.form.number)"
              v-model="form.number" type="text" class="form-control" id="floatingNumber" placeholder="А100АА77"/>
      <label for="floatingNumber">Номер в формате: А100АА77</label>
      <div v-if="errors && errors.number" class="text-danger">{{ errors.number[0] }}</div>
    </div>
    <div v-if="errors && errors.number && idOwner === null" class="d-grid gap-2 mb-2">
      <button @click.prevent="addMechanizmToOrganization" class="btn btn-warning btn-lg"type="submit">Добавить к организации</button>
      <button class="btn btn-secondary" @click="errors = []" type="button">Отменить</button>
    </div>
    <div v-else>
      <div class="form-floating mb-3">
        <input v-model="form.model" type="text" class="form-control" id="floatingModel" placeholder="Тойота">
        <label for="floatingModel">Марка техники (не обязательно)</label>
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
        <label for="floatingName">Название (не обязательно): Насос, кран и т.д</label>
      </div>
      <div class="form-floating mb-3">
        <input v-model="form.color" type="text" class="form-control" id="floatingColor" placeholder="Красный">
        <label for="floatingColor">Цвет техники (не обязательно)</label>
      </div>

      <div v-if="addOwner" class="col-12">
        <div>
          <p class="lead mb-2">Укажите владельца (не обязательно):</p>
          <search-item-component type="owner" @checkItem="selectOwnerId"></search-item-component>
        </div>
      </div>
      <div class="col-12 my-2">
        <div class="form-check form-switch">
          <input v-model="addOwner" class="form-check-input" type="checkbox" id="addOwner" :disabled="form.owner != null">
          <label class="form-check-label" for="addOwner">Указать владельца авто</label>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button @click.prevent="createMechanizm"  class="btn btn-primary btn-lg text-white"type="submit">Добавить технику</button>
      </div>
    </div>

  </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['number', 'project', 'idOwner'],
        mounted() {
          console.log('Компонент по регистрации новой техники установлен.')
          if(this.number){
            this.form.number = this.number
          }

          if(this.idOwner ){
            this.form.owner = this.idOwner
          }

          this.form.project = this.project
        },
        data() {
            return {
              errors: [],
              addOwner: false,
              form: {
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
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'ring': validation.$error,
               'is-valid': validation.required,
             }
          },
          selectOwnerId(id){
            this.form.owner = id
          },
          createMechanizm(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/mechanizm/create', this.form).then((response) =>{
                var data = {
                  'id': response.data,
                  'title': this.form.number,
                }
                for (let input in this.form) {
                    this.form[input] = ''
                }
                this.form.type = 'Спецтехника до 1,5 т'
                this.form.owner = null
                this.$v.$reset()
                this.addOwner = false
                this.errors = []
                this.$emit('refreshList', data);
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
          },
          addMechanizmToOrganization(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/mechanizm/add-to-organization', this.form).then((response) =>{

                var data = {
                  'id': response.data,
                  'title': this.form.number,
                }
                for (let input in this.form) {
                    this.form[input] = ''
                }
                this.form.type = 'Спецтехника до 1,5 т'
                this.form.owner = null
                this.$v.$reset()
                this.addOwner = false
                this.errors = []
                this.$emit('refreshList', data);
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
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
