<template>
  <div>
    <div class="row justify-content-center">
        <p class="lead mb-2">Добавить автомобиль:</p>
        <div class="col-12">
          <div class="form-floating mb-3">
            <input  @blur="$v.form.number.$touch()"
                    :class="status($v.form.number)"
                    v-model="form.number" type="text" class="form-control" id="floatingNumber" placeholder="А100АА77"/>
            <label for="floatingNumber">Номер в формате: А100АА77</label>
            <div v-if="errors && errors.number" class="alert alert-danger mt-1" role="alert">{{ errors.number[0] }}</div>
            <div v-if="errors && errors.type" class="alert alert-danger mt-1" role="alert">
              {{ errors.type }}
            </div>

          </div>
        </div>

        <div v-if="errors && errors.number && idOwner === null" class="d-grid gap-2 mb-2">
          <button @click.prevent="addCarToOrganization" class="btn btn-warning btn-lg"type="submit">Добавить к организации</button>
          <button class="btn btn-secondary" @click="errors = []" type="button">Отменить</button>
        </div>
        <div v-else-if="errors && errors.type" class="d-grid gap-2 mb-2">
          <button @click.prevent="changeCarType" class="btn btn-warning btn-lg"type="submit">Поменять тип и продолжить</button>
          <button class="btn btn-secondary" @click="errors = []" type="button">Отменить</button>
        </div>
        <div v-else>
          <div v-if="addData" class="col-12">

            <div class="form-floating mb-3">
              <input v-model="form.model" type="text" class="form-control" id="floatingModel" placeholder="Тойота">
              <label for="floatingModel">Марка авто (не обязательно)</label>
            </div>
            <div class="form-floating mb-3">
              <select v-model="form.body" class="form-select" id="floatingSelectBody" aria-label="Выбор типа авто">
                <option value="Легковое">Легковое</option>
                <option value="Грузовое">Грузовое</option>
              </select>
              <label for="floatingSelectBody" class="h5">Назначение автомобиля</label>
            </div>
            <div class="form-floating mb-3">
              <input v-model="form.color" type="text" class="form-control" id="floatingColor" placeholder="Красный">
              <label for="floatingColor">Цвет авто (не обязательно)</label>
            </div>
          </div>

          <div v-if="addOwner" class="col-12">
            <div>
              <p class="lead mb-2">Укажите владельца (не обязательно):</p>
              <search-item-component type="owner" @checkItem="selectOwnerId"></search-item-component>
            </div>
          </div>
          <div class="col-12 my-2">
            <div class="form-check form-switch">
              <input v-model="addData" class="form-check-input" type="checkbox" id="addData">
              <label class="form-check-label" for="addData">Указать дополнительную информацию</label>
            </div>
            <div class="form-check form-switch">
              <input v-model="addOwner" class="form-check-input" type="checkbox" id="addOwner" :disabled="form.owner != null">
              <label class="form-check-label" for="addOwner">Указать владельца авто</label>
            </div>
          </div>
          <div class="d-grid gap-2 mb-2">
            <button @click.prevent="createCar" class="btn btn-primary btn-lg text-white"type="submit">Добавить автомобиль</button>
          </div>
        </div>


      </div>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators'
    export default {
        props: ['number', 'project', 'passType', 'idOwner'],
        mounted() {
            console.log('Компонент по регистрации нового авто установлен.')
            if(this.number){
              this.form.number = this.number
            }
            if(this.passType === 'permanent'){
              this.form.type = 'Постоянное'
            }
            if(this.idOwner ){
              this.form.owner = this.idOwner
            }
            this.form.passType = this.passType
            this.form.project = this.project
        },
        data() {
            return {
              errors: [],
              addOwner: false,
              addData: false,
              form: {
                number: '',
                model: '',
                type: 'Разовое',
                body: 'Легковое',
                color: '',
                owner: null,
                project: null,
                passType: ''
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
          createCar(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/car/create', this.form).then((response) =>{

                var data = {
                  'id': response.data,
                  'title': this.form.number,
                }
                for (let input in this.form) {
                    this.form[input] = ''
                }
                if(this.passType === 'permanent'){
                  this.form.type = 'Постоянное'
                }else{
                  this.form.type = 'Разовое'
                }
                this.form.body = 'Легковое'
                this.form.passType = this.passType
                this.form.owner = null
                this.$v.$reset()
                this.addOwner = false
                this.addData = false
                this.errors = []
                this.$emit('refreshList', data);
              }).catch((error) =>{
                console.log(error.response.data)
                  this.errors = error.response.data.errors
              })
            }
          },
          addCarToOrganization(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/car/add-to-organization', this.form).then((response) =>{

                var data = {
                  'id': response.data,
                  'title': this.form.number,
                }
                for (let input in this.form) {
                    this.form[input] = ''
                }
                if(this.passType === 'permanent'){
                  this.form.type = 'Постоянное'
                }else{
                  this.form.type = 'Разовое'
                }
                this.form.body = 'Легковое'
                this.form.passType = this.passType
                this.form.owner = null
                this.$v.$reset()
                this.addOwner = false
                this.addData = false
                this.errors = []
                this.$emit('refreshList', data);
              }).catch((error) =>{
                  this.errors = error.response.data.errors
              })
            }
          },
          changeCarType(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              this.form.number = this.form.number.toUpperCase()
              axios.post('/car/change-type', this.form).then((response) =>{

                var data = {
                  'id': response.data,
                  'title': this.form.number,
                }
                for (let input in this.form) {
                    this.form[input] = ''
                }
                if(this.passType === 'permanent'){
                  this.form.type = 'Постоянное'
                }else{
                  this.form.type = 'Разовое'
                }
                this.form.body = 'Легковое'
                this.form.passType = this.passType
                this.form.owner = null
                this.$v.$reset()
                this.addOwner = false
                this.addData = false
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
