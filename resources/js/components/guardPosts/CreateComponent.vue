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

        <hr/>

        <div class="form-group">
          <label for="group">Группа</label>
          <Multiselect  v-model="projectGroups.value"
                        id="group"
                        class="mb-2"
                        v-bind="projectGroups"
                        @select="form.group = projectGroups.value"
                        @change="form.group = null"
                      ></Multiselect>
            <div class="form-text">Если список пуст, значит у всех групп уже есть посты. Напишите название группы и оно создаться автоматически, а уже потом можно будет отредактировать группу.</div>
        </div>

        <div class="mb-2">
          <h3><i v-if="$v.form.chief.$invalid && $v.form.chief.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.chief.$invalid" class="bi bi-check-circle-fill text-success"></i> Выберите начальника поста</h3>
          <hr>
          <search-item-component type="profiles" @checkItem="selectChiefId"/>
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
            <div class="form-text">Напишите в поле название организации для поиска и выбора из вспомогательного списка.</div>
        </div>

        <div class="d-grid gap-2 my-3">
          <button class="btn btn-success"  @click="create" type="button">Создать</button>
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
    import {required} from 'vuelidate/lib/validators'
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getOrganizations()
            this.getProjectGroups()
            initModal()
        },
        data() {
            return {
              data: [],
              loader: false,
              errors: [],
              requiredText: 'Это поле должно быть заполнено!',
              form: {
                name: '',
                guards: [],
                enters: [{
                  name: 'КПП',
                  phone: '',
                  status: 'Активный'
                }],
                group: null,
                chief: null,
                organization: null,
              },
              typeItems: '',
              projectGroups: {
    	          value: null,
                createTag: true,
                searchable: true,
                placeholder: 'Выберите группу для проекта',
                options: [],
              },
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
              axios.post('/guardpost/create', this.form).then((response) =>{
                this.$emit('refreshList');
              })
            }
          },
          addEnter(){
            this.form.enters.push(
              { name: '', phone: '', status: 'Активный' }
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
          getProjectGroups(){
            axios.post('/api/get-project-groups', {post: 'without'}).then((response) =>{
              this.projectGroups.options = response.data
            })
          },
          getOrganizations(){
            axios.post('/api/get-organizations').then((response) =>{
              this.organizations.options = response.data
              this.organizations.value = this.form.organization =  response.data[0].value
            })
          },
          selectChiefId(id){
            this.form.chief = id
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
            chief:{
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
