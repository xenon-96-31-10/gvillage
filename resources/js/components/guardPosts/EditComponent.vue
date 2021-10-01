<template>
    <div>
      <transition name="slide-fade">
        <div v-if="success" class="alert alert-success" role="alert">
           Вы успешно обновили объект!
        </div>
      </transition>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="form-floating mb-2">
          <input  @blur="$v.form.name.$touch()"
                  :class="status($v.form.name)"
                  v-model="form.name" type="text" class="form-control" id="floatingName" placeholder="д. Солослово">
                  <div class="invalid-feedback" v-if="!$v.form.name.required">{{ requiredText }}</div>
          <label for="floatingName">Название</label>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="phonePost-addon">+7</span>
          <input
                  @blur="$v.form.phone.$touch()"
                  :class="status($v.form.phone)"
                  v-model="form.phone" type="text" class="form-control" placeholder="Телефон" aria-label="Username" aria-describedby="phonePost-addon">
          <div class="invalid-feedback" v-if="!$v.form.phone.required">{{ requiredText }}</div>
        </div>
        <div class="d-grid gap-2 my-2">
          <div class="fw-bold">
            {{form.guards.length}} - колличество охранников на посту
          </div>
          <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addItemsToGroup" @click="typeItems = 'users'">
            Изменить/добавить охранников
          </button>
        </div>
        <h5 class="card-title mb-2">Входы/выходы:</h5>
        <ol class="list-group list-group-numbered mb-2">
          <li v-for="(enter,index) in form.enters" :key="enter.id" class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
              <div class="input-group mb-3">
                <span class="input-group-text" id="name-addon">Название</span>
                <input v-model="enter.name" type="text" class="form-control" placeholder="КПП" aria-label="Кпп" aria-describedby="name-addon">
              </div>
            </div>
            <div class="ms-2 me-auto">
              <div class="input-group mb-3">
                <span class="input-group-text" id="phone-addon">+7</span>
                <input v-model="enter.phone" type="text" class="form-control" placeholder="Телефон" aria-label="Username" aria-describedby="phone-addon">
              </div>
            </div>
            <div class="ms-2 me-auto">
              <div class="input-group mb-3">
                <label class="input-group-text" for="inputGroupSelect01">Статус</label>
                <select v-model="enter.status" class="form-select" id="inputGroupSelect01">
                  <option value="Активный">Активный</option>
                  <option value="Закрыт">Закрыт</option>
                </select>
                <button v-if="enter.id === 'new'" type="button" class="btn btn-danger" @click="deleteEnter(index)">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </div>
            </div>
          </li>
        </ol>
        <div class="d-grid gap-2 mb-3">
          <button type="button" class="btn btn-dark" @click="addEnter">
            Добавить еще вход
          </button>
        </div>
        <div class="d-grid gap-2 my-3">
          <button class="btn btn-success"  @click="update" type="button">Обновить</button>
        </div>
      </div>
      <div class="modal fade" id="addItemsToGroup" tabindex="-1" aria-labelledby="addItemsToGroupLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addItemsToGroupLabel">Выбор элементов, для которых будет действовать план</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <multisearch-item-component :type="typeItems" :key="typeItems" :old-items="form.guards" @selectItems="selectItems"/>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    var Modal, myModal,Toast, eToast, successToast, errorToast
    function initModal(){
      Modal = document.getElementById('addItemsToGroup')
      myModal = new bootstrap.Modal(Modal)
    }
    import {required, minLength, email} from 'vuelidate/lib/validators'
    export default {
        props: ['guardpostId'],
        mounted() {
            console.log('Component mounted.')
            this.getGuardPost()
            initModal()
        },
        data() {
            return {
              data: [],
              loader: true,
              errors: [],
              success: false,
              requiredText: 'Это поле должно быть заполнено!',
              form: {
                id: null,
                name: '',
                phone: '',
                guards: [],
                enters: [],
              },
              typeItems: '',
            }
        },
        methods: {
          getGuardPost(){
            this.loader = true
            axios.post('/api/get-guard-post', { id : this.guardpostId}).then((response) =>{
              this.data = response.data
              this.form.id = this.data.id
              this.form.name = this.data.name
              this.form.phone = this.data.phone
              this.form.guards = this.data.guards
              this.form.enters = this.data.enters
              this.loader = false
            })
          },
          addEnter(){
            this.form.enters.push(
              { id: 'new', name: '', phone: '', status: 'Активный' }
            )
          },
          deleteEnter(index){
            this.form.enters.splice(this.form.enters.indexOf(index), 1);
          },
          selectItems(items){
            myModal.toggle()
            if(this.typeItems === 'users'){
              this.form.guards = []
              _.each(items, (value, key) => {
                this.form.guards.push(value.id)
              })
            }
          },
          update(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/guardpost/update', this.form).then((response) =>{
                this.success = true
                this.getGuardPost()
                this.$emit('refreshList');
                setTimeout(() => {
                  this.success = false
                }, 5000)
              })
            }

          },
          status(validation) {
             return {
               'is-invalid': validation.$error,
               'is-valid': validation.required,
             }
          }
        },
        validations: {
          form: {
            name:{
              required,
            },
            phone:{
              required,
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
