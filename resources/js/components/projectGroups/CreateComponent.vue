<template>
    <div>
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
            Добавить объекты
          </button>
        </div>

        <div v-if="organizations.options.length > 1" class="mb-3">
          <hr>
          <p class="lead">Укажаите название организации для нового менеджера</p>
          <Multiselect  @blur="$v.form.organization.$touch()"
                        :class="status($v.form.organization)"
                        v-model="organizations.value"
                        v-bind="organizations"
                        @select="form.organization = organizations.value"
                        @change="form.organization = ''"
                      ></Multiselect>
                      <div class="invalid-feedback" v-if="!$v.form.organization.required">{{ requiredText }}</div>
            <div class="form-text">Напишите в поле название организации для поиска и выбора из вспомогательного списка. Если такого названия нет в базе, то организация добавиться в базу.</div>
        </div>
        <div class="d-grid gap-2 my-3">
          <button class="btn btn-primary"  @click="create" type="button">Создать</button>
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
    import {required} from 'vuelidate/lib/validators'
    export default {
        props: ['ownerId'],
        mounted() {
            console.log('Component mounted.')
            this.getOrganizations()
            initModal()
        },
        data() {
            return {
              data: [],
              loader: false,
              errors: [],
              requiredText: 'Это поле должно быть заполнено!',
              form: {
                id: null,
                name: '',
                projects: [],
                organization: null,
              },
              typeItems: '',
              organizations: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите организацию',
                options: [],
              },
            }
        },
        methods: {
          create(){
            if(this.$v.form.$invalid){
              this.$v.form.$touch()
            }else{
              axios.post('/project-group/create', this.form).then((response) =>{
                this.$emit('refreshList');
              })
            }
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
          getOrganizations(){
            axios.post('/api/get-organizations').then((response) =>{
              this.organizations.options = response.data
              this.organizations.value = this.form.organization =  response.data[0].value
            })
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
            organization:{
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
