<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="form-floating my-2">
          <dadata-suggestions
                              @blur.native="$v.form.address.$touch()"
                              :class="status($v.form.address)"
                              v-model="form.address"
                              type="ADDRESS"
                              field-value="unrestricted_value"
                              class="form-control" id="floatingADDRESS" placeholder="Иванов Иван Иванович"
            />
          <label for="floatingADDRESS" class="h5">Адресс объекта</label>
          <div class="invalid-feedback" v-if="!$v.form.address.required">{{ requiredText }}</div>
        </div>


        <div class="form-floating mb-2">
          <input v-model="form.name" type="text" class="form-control" id="floatingName" placeholder="Проект двухэтажного дома">
          <label for="floatingName">Название объекта</label>
        </div>

        <div class="form-floating mb-2">
          <textarea  v-model="form.description"
                     class="form-control" placeholder="Описание объекта" id="floatingComment" style="height: 100px"></textarea>
          <label for="floatingComment">Заметка <i class="bi bi-chat-left-text"></i></label>
        </div>

        <div class="row mb-2">
          <div class="col">
            <div class="form-group">
              <label for="totalarea">Общая площадь, м<sup>2</sup></label>
              <input  type="number" class="form-control project" v-model.number="form.totalarea"  id="totalarea" step="any" max="50" placeholder="Общая площадь">
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="livingarea">Жилая площадь, м<sup>2</sup></label>
              <input  type="number" class="form-control project" v-model.number="form.livingarea"  id="totalarea" step="any" max="50" placeholder="Жилая площадь">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="group">Группа</label>
          <Multiselect  v-model="projectGroups.value"
                        id="group"
                        class="mb-2"
                        v-bind="projectGroups"
                        @select="form.group = projectGroups.value"
                        @change="form.group = null"
                      ></Multiselect>
        </div>
        <div class="form-floating mb-2">
          <select  class="form-select" id="floatingTypes" v-model="form.type" aria-label="Floating label select example">
            <option value="" disabled>Открыть список</option>
            <option v-for="type in projectTypes" :value="type.value">
              {{type.label}}
            </option>
          </select>
          <label for="floatingTypes">Выберите тип объекта <i class="bi bi-building"></i></label>
        </div>
        <div v-if="!ownerId" class="mb-2">
          <h3><i v-if="$v.form.owner.$invalid && $v.form.owner.$dirty" class="bi bi-x-circle-fill text-danger"></i><i v-if="!$v.form.owner.$invalid" class="bi bi-check-circle-fill text-success"></i> Выберите собственника</h3>
          <hr>
          <search-item-component type="profiles" @checkItem="selectOwnerId"/>
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
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    export default {
        props: ['ownerId'],
        mounted() {
            console.log('Component mounted.')
            this.getProjectGroups()
            this.getProjectTypes()
            this.getOrganizations()
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
                description: '',
                address: '',
                totalarea: 0,
                livingarea: 0,
                organization: null,
                group: null,
                type: 1,
                owner: null,
              },
              projectTypes:[],
              projectGroups: {
    	          value: null,
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
              axios.post('/project/create', this.form).then((response) =>{
                this.$emit('refreshList');
              })
            }
          },
          getProjectGroups(){
            axios.post('/api/get-project-groups').then((response) =>{
              this.projectGroups.options = response.data
            })
          },
          getProjectTypes(){
            axios.post('/api/get-project-types').then((response) =>{
              this.projectTypes = response.data
            })
          },
          getOrganizations(){
            axios.post('/api/get-organizations').then((response) =>{
              this.organizations.options = response.data
              this.organizations.value = this.form.organization =  response.data[0].value
            })
          },
          selectOwnerId(id){
            this.form.owner = id
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
            address:{
              required,
            },
            organization:{
              required,
            },
            owner:{
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
