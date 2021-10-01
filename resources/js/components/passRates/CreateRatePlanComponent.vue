<template>
    <div>
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title">Добавление нового тарифного плана с тарифами</h5>
          <p class="card-text">Тут Вы можете добавить тарифы, прикрепить их к группе объектов и к объектам в частности.</p>
          <div class="form-floating mb-2">
            <input v-model="form.name" type="text" class="form-control" id="floatingNamePlan" placeholder="Тарифный план">
            <label for="floatingNamePlan">Название тарифного плана</label>
          </div>
          <div class="form-check">
            <input v-model="form.default" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
              Назначить по умолчанию
            </label>
          </div>
        </div>
      </div>
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title">Добавление тарифов для "Разового пропуска"</h5>
          <p class="card-text">Тут Вы можете добавить тарифы,которые будут использоваться при оформлении разового пропуска (оплата устанавливается за 1 въезд в рублях).</p>
          <p class="small">Тарифы по умолчанию нельзя удалять, так как они используются при автоматическом определении тарифа при создании пропуска, если других уточнений нет. Если вы хотите добавить тариф с уточнением (например, роли), воспользуйтесь функцей добавления нового тарифа.</p>

          <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
              <tbody>
                <tr>
                  <th scope="row">Если посетитель на авто</th>
                  <td>Роль</td>
                  <td>Заметка</td>
                  <td>Цена</td>
                </tr>
                <tr v-for="(item, index) in form.temporary" v-if="item.visitor_type == 'cars'" class="bg-secondary bg-gradient">
                  <th class="rounded-start" scope="row">{{item.visitor}}</th>
                  <td>{{item.role}}</td>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">Заметка</span>
                      <textarea class="form-control" aria-label="With textarea"></textarea>
                    </div>
                  </td>
                  <td class="rounded-end">
                    <div class="input-group mb-3">
                      <span class="input-group-text" :id="'price'+index">Цена</span>
                      <input v-model.number="item.price" type="number" class="form-control">

                      <button v-if="item.role != 'По умолчанию'" type="button" class="btn btn-danger" @click="deleteTemporaryItem(index)">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="bg-light bg-gradient">
                  <th class="rounded-start" scope="row">
                    <div class="form-floating">
                      <select v-model="temporaryCar.type" class="form-select" id="floatingSelectTemporaryCar" aria-label="Floating label select example">
                        <option value="Легковое">Легковое</option>
                        <option value="Грузовое">Грузовое</option>
                      </select>
                      <label for="floatingTemporaryCar">Выберите тип авто</label>
                    </div>
                  </th>
                  <td colspan="2">
                    <div class="form-floating">
                      <select v-model="temporaryCar.role" class="form-select" id="floatingTemporaryCarRole" aria-label="Для какой роли">
                        <option v-for="role in roles" :value="role.name">{{role.name}}</option>
                      </select>
                      <label for="floatingSelectTemporaryCarRole">Выберите роль</label>
                    </div>
                  </td>
                  <td class="rounded-end">
                    <div class="d-grid gap-2">
                      <button type="button" class="btn btn-dark" @click="addTemporaryCar">
                        Добавить тариф
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">Если посетитель физ. лицо:</th>
                  <td>Роль</td>
                  <td>Заметка</td>
                  <td>Цена</td>
                </tr>
                <tr v-for="(item, index) in form.temporary" v-if="item.visitor_type == 'profiles'" class="bg-secondary bg-gradient">
                  <th class="rounded-start" scope="row">{{item.visitor}}</th>
                  <td>{{item.role}}</td>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">Заметка</span>
                      <textarea class="form-control" aria-label="With textarea"></textarea>
                    </div>
                  </td>
                  <td class="rounded-end">
                    <div class="input-group mb-3">
                      <span class="input-group-text" :id="'price'+index">Цена</span>
                      <input v-model.number="item.price" type="number" class="form-control" :aria-describedby="'price'+index">


                      <button v-if="item.role != 'По умолчанию'" type="button" class="btn btn-danger" @click="deleteTemporaryItem(index)">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="bg-light bg-gradient">
                  <th class="rounded-start" scope="row">
                    {{temporaryProfile.type}}
                  </th>
                  <td colspan="2">
                    <div class="form-floating">
                      <select v-model="temporaryProfile.role" class="form-select" id="floatingTemporaryProfileRole" aria-label="Для какой роли">
                        <option v-for="role in roles" :value="role.name">{{role.name}}</option>
                      </select>
                      <label for="floatingSelectTemporaryProfileRole">Выберите роль</label>
                    </div>
                  </td>
                  <td class="rounded-end">
                    <div class="d-grid gap-2">
                      <button type="button" class="btn btn-dark" @click="addTemporaryProfile">
                        Добавить тариф
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">Если посетитель на технике</th>
                  <td colspan="2">Заметка</td>
                  <td>Цена</td>
                </tr>
                <tr v-for="item in form.temporary" v-if="item.visitor_type == 'mechanizms'" class="bg-secondary bg-gradient">
                  <th class="rounded-start" scope="row">{{item.visitor}}</th>
                  <td colspan="2">
                    <div class="input-group">
                      <span class="input-group-text">Заметка</span>
                      <textarea class="form-control" aria-label="With textarea"></textarea>
                    </div>
                  </td>
                  <td class="rounded-end">
                    <div class="input-group mb-3">
                      <span class="input-group-text" :id="'price'+item.id">Цена</span>
                      <input v-model.number="item.price" type="number" class="form-control" :aria-describedby="'price'+item.id">
                    </div>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title">Добавление тарифов для "Постоянного пропуска"</h5>
          <p class="card-text">Тут Вы можете добавить тарифы,которые будут использоваться при оформлении постоянного пропуска (оплата устанавливается за 1 месяц/полгода в рублях).</p>
          <p class="small">Тарифы по умолчанию нельзя удалять, так как они используются при автоматическом определении тарифа при создании пропуска, если других уточнений нет. Если вы хотите добавить тариф с уточнением (например, роли), воспользуйтесь функцей добавления нового тарифа.</p>

          <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
              <tbody>
                <tr>
                  <th scope="row">Если посетитель на авто</th>
                  <td>Роль</td>
                  <td>Заметка</td>
                  <td>Срок</td>
                  <td>Цена</td>
                </tr>
                <tr v-for="(item, index) in form.permanent" v-if="item.visitor_type == 'cars'" class="bg-secondary bg-gradient">
                  <th class="rounded-start" scope="row">{{item.visitor}}</th>
                  <td>{{item.role}}</td>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">Заметка</span>
                      <textarea class="form-control" aria-label="With textarea"></textarea>
                    </div>
                  </td>
                  <td>{{item.time}}</td>
                  <td class="rounded-end">
                    <div class="input-group mb-3">
                      <span class="input-group-text" :id="'price'+index">Цена</span>
                      <input v-model.number="item.price" type="number" class="form-control">

                      <button v-if="item.role != 'По умолчанию'" type="button" class="btn btn-danger" @click="deletePermanentItem(index)">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="bg-light bg-gradient">
                  <th class="rounded-start" scope="row">
                    <div class="form-floating">
                      <select v-model="permanentCar.type" class="form-select" id="floatingSelectPermanentCar" aria-label="Floating label select example">
                        <option value="Легковое">Легковое</option>
                        <option value="Грузовое">Грузовое</option>
                      </select>
                      <label for="floatingPermanentCar">Выберите тип авто</label>
                    </div>
                  </th>
                  <td>
                    <div class="form-floating">
                      <select v-model="permanentCar.role" class="form-select" id="floatingPermanentCarRole" aria-label="Для какой роли">
                        <option v-for="role in roles" :value="role.name">{{role.name}}</option>
                      </select>
                      <label for="floatingSelectPermanentCarRole">Выберите роль</label>
                    </div>
                  </td>
                  <th  colspan="2">
                    <div class="form-floating">
                      <select v-model="permanentCar.time" class="form-select" id="floatingSelectPermanentCar" aria-label="Floating label select example">
                        <option value="1 месяц">1 месяц</option>
                        <option value="полгода">Полгода</option>
                      </select>
                      <label for="floatingPermanentCar">Выберите срок</label>
                    </div>
                  </th>
                  <td class="rounded-end">
                    <div class="d-grid gap-2">
                      <button type="button" class="btn btn-dark" @click="addPermanentCar">
                        Добавить тариф
                      </button>
                    </div>
                  </td>
                </tr>

                <tr>
                  <th scope="row">Если посетитель физ. лицо:</th>
                  <td>Роль</td>
                  <td>Заметка</td>
                  <td>Срок</td>
                  <td>Цена</td>
                </tr>
                <tr v-for="(item, index) in form.permanent" v-if="item.visitor_type == 'profiles'" class="bg-secondary bg-gradient">
                  <th class="rounded-start" scope="row">{{item.visitor}}</th>
                  <td>{{item.role}}</td>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">Заметка</span>
                      <textarea class="form-control" aria-label="With textarea"></textarea>
                    </div>
                  </td>
                  <td>{{item.time}}</td>
                  <td class="rounded-end">
                    <div class="input-group mb-3">
                      <span class="input-group-text" :id="'price'+index">Цена</span>
                      <input v-model.number="item.price" type="number" class="form-control" :aria-describedby="'price'+index">


                      <button v-if="item.role != 'По умолчанию'" type="button" class="btn btn-danger" @click="deletePermanentItem(index)">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="bg-light bg-gradient">
                  <th class="rounded-start" scope="row">
                    {{permanentProfile.type}}
                  </th>
                  <td>
                    <div class="form-floating">
                      <select v-model="permanentProfile.role" class="form-select" id="floatingProfileProfileRole" aria-label="Для какой роли">
                        <option v-for="role in roles" :value="role.name">{{role.name}}</option>
                      </select>
                      <label for="floatingSelectProfileProfileRole">Выберите роль</label>
                    </div>
                  </td>
                  <th colspan="2">
                    <div class="form-floating">
                      <select v-model="permanentProfile.time" class="form-select" id="floatingSelectPermanentProfile" aria-label="Floating label select example">
                        <option value="1 месяц">1 месяц</option>
                        <option value="полгода">Полгода</option>
                      </select>
                      <label for="floatingPermanentProfile">Выберите срок</label>
                    </div>
                  </th>
                  <td class="rounded-end">
                    <div class="d-grid gap-2">
                      <button type="button" class="btn btn-dark" @click="addPermanentProfile">
                        Добавить тариф
                      </button>
                    </div>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title">Назначьте тарифный план для групп объектов/объектов</h5>
          <p class="card-text">Тут Вы можете выбрать группу или группы/объект или объекты, для которых будет действовать данный тариф.</p>
          <p class="card-text">Выбранно: групп - {{form.selectedGroups.length}} / объектов - {{form.selectedProjects.length}}</p>
          <div class="row mb-2">
            <div class="col">
              <div class="d-grid gap-2 my-2">
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addItemsToPlan" @click="typeItems = 'project-groups'">
                  Добавить группы объектов
                </button>
              </div>
            </div>
            <div class="col">
              <div class="d-grid gap-2 my-2">
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addItemsToPlan" @click="typeItems = 'projects'">
                  Добавить объекты
                </button>
              </div>
            </div>
          </div>

          <div class="d-grid gap-2 my-2">
            <button type="button" class="btn btn-success btn-lg text-white" @click="createRatePlan">
              Создать тарифный план
            </button>
          </div>
        </div>
      </div>

      <transition name="slide-fade">
        <div class="toast-container fixed-bottom m-3 translate-middle-y" id="toastPlacement" data-original-class="toast-container position-absolute p-3">
          <div class="toast align-items-center text-white bg-success border-0"  id="addNewRate" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                Тариф добавлен успешно!
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>

          <div class="toast align-items-center text-white bg-danger border-0"  id="error" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                Ошибка! Такой тариф уже добавлен.
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      </transition>


      <div class="modal fade" id="addItemsToPlan" tabindex="-1" aria-labelledby="addItemsToPlanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addItemsToPlanLabel">Выбор элементов, для которых будет действовать план</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <multisearch-item-component :type="typeItems" :key="typeItems" :old-items="typeItems === 'projects'? form.selectedProjects : form.selectedGroups" @selectItems="selectItems"/>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    var Modal, myModal,Toast, eToast, successToast, errorToast
    function initModal(){
      Modal = document.getElementById('addItemsToPlan')
      myModal = new bootstrap.Modal(Modal)
    }

    function initToast(){
      Toast = document.getElementById('addNewRate')
      successToast = new bootstrap.Toast(Toast)
      eToast = document.getElementById('error')
      errorToast = new bootstrap.Toast(eToast)
    }
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getRoles()
            initToast()
            initModal()
        },
        data(){
          return{
            loader: true,
            form: {
              name: '',
              default: false,
              temporary: [
                {visitor_type: 'cars', visitor: 'Легковое', role: 'По умолчанию', description: '', price: 0 },
                {visitor_type: 'cars', visitor: 'Грузовое', role: 'По умолчанию',description: '', price: 0 },
                {visitor_type: 'profiles', visitor: 'Человек', role: 'По умолчанию', description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Спецтехника до 1,5 т', role: 'По умолчанию',description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Спецтехника от 1,5 т до 3 т', role: 'По умолчанию',description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Спецтехника от 3 т до 7 т', role: 'По умолчанию',description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Спецтехника свыше 7 т', role: 'По умолчанию',description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Грузовой', role: 'По умолчанию', description: '', price: 0 },
                {visitor_type: 'mechanizms', visitor: 'Длинномер/Еврофура', role: 'По умолчанию', description: '', price: 0 },
              ],
              permanent: [
                {visitor_type: 'cars', visitor: 'Легковое', role: 'По умолчанию', description: '', price: 0, time: '1 месяц' },
                {visitor_type: 'cars', visitor: 'Легковое', role: 'По умолчанию', description: '', price: 0, time: 'полгода' },
                {visitor_type: 'cars', visitor: 'Грузовое', role: 'По умолчанию',description: '', price: 0 , time: '1 месяц'},
                {visitor_type: 'cars', visitor: 'Грузовое', role: 'По умолчанию',description: '', price: 0 , time: 'полгода'},
                {visitor_type: 'profiles', visitor: 'Человек', role: 'По умолчанию', price: 0 , time: '1 месяц', time: '1 месяц'},
                {visitor_type: 'profiles', visitor: 'Человек', role: 'По умолчанию', price: 0 , time: '1 месяц', time: 'полгода'},

              ],
              selectedGroups: [],
              selectedProjects: [],
            },
            roles: [],
            filteredItems: [],
            typeItems: '',
            temporaryCar: {
              type: 'Легковое',
              role: 'Работник'
            },
            temporaryProfile: {
              type: 'Человек',
              role: 'Работник'
            },
            permanentCar: {
              type: 'Легковое',
              role: 'Работник',
              time: 'полгода'
            },
            permanentProfile: {
              type: 'Человек',
              role: 'Работник',
              time: 'полгода'
            },
          }
        },
        methods:{
          getRoles(){
            axios.post('/api/get-roles').then((response) =>{
              this.roles = response.data
            })
          },
          addTemporaryCar(){
            var app = this
            this.filteredItems = _.filter(this.form.temporary, function(v, k){
              return v.visitor_type.indexOf('cars') > -1 && v.visitor.indexOf(app.temporaryCar.type) > -1 && v.role.indexOf(app.temporaryCar.role) > -1
            })

            if(this.filteredItems.length === 0){
              this.form.temporary.push(
                { visitor_type: 'cars', visitor: this.temporaryCar.type, role: this.temporaryCar.role, description: '', price: 0 }
              )
              successToast.show()
            }else{
              errorToast.show()
            }
          },
          addTemporaryProfile(){
            var app = this
            this.filteredItems = _.filter(this.form.temporary, function(v, k){
              return v.visitor_type.indexOf('profiles') > -1 && v.visitor.indexOf(app.temporaryProfile.type) > -1 && v.role.indexOf(app.temporaryProfile.role) > -1
            })

            if(this.filteredItems.length === 0){
              this.form.temporary.push(
                { visitor_type: 'profiles', visitor: this.temporaryProfile.type, role: this.temporaryProfile.role, description: '', price: 0 }
              )
              successToast.show()
            }else{
              errorToast.show()
            }
          },
          deleteTemporaryItem(index){
            this.form.temporary.splice(this.form.temporary.indexOf(index), 1);
          },
          addPermanentCar(){
            var app = this
            this.filteredItems = _.filter(this.form.permanent, function(v, k){
              return v.visitor_type.indexOf('cars') > -1 && v.visitor.indexOf(app.permanentCar.type) > -1 && v.role.indexOf(app.permanentCar.role) > -1 && v.time.indexOf(app.permanentCar.time) > -1
            })

            if(this.filteredItems.length === 0){
              this.form.permanent.push(
                { visitor_type: 'cars', visitor: this.permanentCar.type, role: this.permanentCar.role, description: '', price: 0, time: this.permanentCar.time }
              )
              successToast.show()
            }else{
              errorToast.show()
            }
          },
          addPermanentProfile(){
            var app = this
            this.filteredItems = _.filter(this.form.permanent, function(v, k){
              return v.visitor_type.indexOf('profiles') > -1 && v.visitor.indexOf(app.permanentProfile.type) > -1 && v.role.indexOf(app.permanentProfile.role) > -1 && v.time.indexOf(app.permanentProfile.time) > -1
            })

            if(this.filteredItems.length === 0){
            this.form.permanent.push(
              { visitor_type: 'profiles', visitor: this.permanentProfile.type, role: this.permanentProfile.role, description: '', price: 0, time: this.permanentProfile.time, }
            )
              successToast.show()
            }else{
              errorToast.show()
            }
          },
          deletePermanentItem(index){
            this.form.permanent.splice(this.form.permanent.indexOf(index), 1);
          },
          selectItems(items){
            myModal.toggle()
            if(this.typeItems === 'projects'){
              this.form.selectedProjects = []
              _.each(items, (value, key) => {
                this.form.selectedProjects.push(value.id)
              })
            }else{
              this.form.selectedGroups = []
              _.each(items, (value, key) => {
                this.form.selectedGroups.push(value.id)
              })
            }
          },
          createRatePlan(){
            axios.post('/create/pass-rate-plan', this.form).then((response) =>{
              this.$emit('refreshList');
            })
          }
        },
        computed: {

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
</style>
