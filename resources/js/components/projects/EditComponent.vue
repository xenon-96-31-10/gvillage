<template>
    <div>
      <transition name="slide-fade">
        <div v-if="success" class="alert alert-success" role="alert">
           Вы успешно обновили объект!
        </div>
      </transition>
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
        <div v-if="chooseOwner" class="mb-2">
          <div class="d-grid gap-2 mb-2">
            <button class="btn btn-dark"  @click="cOwner('back')" type="button">Отменить</button>
          </div>
          <h3>Выберите другого собственника</h3>
          <hr>
          <search-item-component type="profiles" @checkItem="selectOwnerId"/>
        </div>
        <div v-else class="mb-2">
          <p class="lead">Собственник: {{data.owner.fio}}</p>
          <div class="d-grid gap-2 mb-2">
            <button class="btn btn-secondary"  @click="cOwner('change')" type="button">Выбрать другого</button>
          </div>
        </div>
        <div class="d-grid gap-2 my-3">
          <button class="btn btn-primary"  @click="update" type="button">Обновить</button>
        </div>
      </div>
    </div>
</template>

<script>
    import {required, minLength, email} from 'vuelidate/lib/validators'
    export default {
        props: ['projectId'],
        mounted() {
            console.log('Component mounted.')
            this.getProject()
            this.getProjectGroups()
            this.getProjectTypes()
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
                description: '',
                address: '',
                totalarea: 0,
                livingarea: 0,
                group: null,
                type: null,
                owner: null,
              },
              chooseOwner: false,
              projectTypes:[],
              projectGroups: {
    	          value: null,
                searchable: true,
                placeholder: 'Выберите группу для проекта',
                options: [],
              },
            }
        },
        methods: {
          getProject(){
            this.loader = true
            axios.post('/api/get-project', { id : this.projectId}).then((response) =>{
              this.data = response.data
              this.form.id = this.data.id
              this.form.address = this.data.address
              this.form.name = this.data.name
              this.form.description = this.data.description
              this.form.totalarea = this.data.totalarea
              this.form.livingarea = this.data.livingarea
              this.form.group = this.projectGroups.value = this.data.group.id
              this.form.type = this.data.type.id
              this.form.owner = this.data.owner.id
              this.loader = false
            })
          },
          update(){
            axios.post('/project/update', this.form).then((response) =>{
              this.success = true
              this.chooseOwner = false
              this.getProject()
              setTimeout(() => {
                this.success = false
              }, 5000)
            })
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
          cOwner(type){
            if(type ==='back'){
              this.chooseOwner = false
              this.form.owner = this.data.owner.id
            }else{
              this.chooseOwner = true
            }
          },
          selectOwnerId(id){
            if(id === null ){
              id = this.data.owner.id
            }
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
