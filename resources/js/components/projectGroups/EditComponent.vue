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
        <div class="d-grid gap-2 my-2">
          <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addItemsToGroup" @click="typeItems = 'projects'">
            Изменить объекты
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
              <multisearch-item-component :type="typeItems" :key="typeItems" :old-items="form.projects" @selectItems="selectItems"/>
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
        props: ['projectId'],
        mounted() {
            console.log('Component mounted.')
            this.getProjectGroup()
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
                projects: [],
              },
              typeItems: '',
            }
        },
        methods: {
          getProjectGroup(){
            this.loader = true
            axios.post('/api/get-project-group', { id : this.projectId}).then((response) =>{
              this.data = response.data
              this.form.id = this.data.id
              this.form.name = this.data.name
              this.form.projects = this.data.projects
              this.loader = false
            })
          },
          selectItems(items){
            myModal.toggle()
            if(this.typeItems === 'projects'){
              this.form.projects = []
              _.each(items, (value, key) => {
                this.form.projects.push(value.id)
              })
            }
          },
          update(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/project-group/update', this.form).then((response) =>{
                this.success = true
                this.getProjectGroup()
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
            }
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
