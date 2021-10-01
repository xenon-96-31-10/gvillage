<template>
    <div>
      <transition name="slide-fade">
        <div v-if="success" class="alert alert-success" role="alert">
           <strong>Успешно!</strong>
        </div>
      </transition>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="card my-2">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Техника</h5>
            </div>
          </div>
          <div class="card-body">
            <div v-if="search">
              <div v-if="mechanizms.length > 0" class="input-group my-1">
                <input  v-model="searchMechanizms"
                        type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
                <button @click="searchMechanizms = ''" :class="{'disabled': searchMechanizms ==''}"
                        class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
              </div>
              <ol class="list-group list-group-numbered mb-2">
                <li v-for="mechanizm in Mechanizms" :key="mechanizm.id" class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">{{mechanizm.number}}</div>
                    {{mechanizm.type}}
                  </div>
                  <div>
                    <button type="button" class="btn btn-danger text-white me-2" @click="deleteMechanizm(mechanizm.id)"><i class="bi bi-trash-fill"></i></button>
                    <button type="button" class="btn btn-primary text-white me-2" @click="editMechanizm(mechanizm.id)"><i class="bi bi-pencil-square"></i></button>
                  </div>
                </li>
                <li v-if="Mechanizms.length < 1"  class="list-group-item list-group-item-danger d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Нет техники</div>
                  </div>
                </li>
              </ol>
            </div>
            <div v-else>
              <edit-mechanizm-component :id="editId" @refreshList="actionMechanizm('update')"/>
              <div class="d-grid gap-2 my-2">
                <button class="btn btn-dark" type="button" @click="backToSearch">Отменить</button>
              </div>
            </div>

          </div>
        </div>

        <div class="card my-2">
          <div class="card-body">
            <create-mechanizm-component v-if="createMechanizm" :id-owner="profileId" @refreshList="actionMechanizm('create')"/>
            <div class="d-grid gap-2 my-2">
              <button v-if="createMechanizm" class="btn btn-dark" type="button" @click="createMechanizm = false">Отменить</button>
              <button v-else class="btn btn-primary text-white" type="button" @click="createMechanizm = true">Добавить технику</button>
            </div>
          </div>
        </div>

      </div>
    </div>
</template>

<script>
    export default {
        props: ['profileId'],
        mounted() {
            console.log('Component mounted.')
            this.getProfileMechanizms()
        },
        data() {
            return {
              loader: true,
              mechanizms: [],
              search: true,
              success: false,
              editId: null,
              createMechanizm: false,
              searchMechanizms: '',
            }
        },
        methods: {
          getProfileMechanizms(){
            this.loader = true
            axios.post('/api/get-profile-mechanizms', { id : this.profileId}).then((response) =>{
              this.mechanizms = response.data
              this.loader = false
            })
          },
          actionMechanizm(type){
            type === 'create' ? this.createMechanizm = false  : this.search = true
            this.success = true
            this.getProfileMechanizms()
            setTimeout(() => {
              this.success = false
            }, 5000)
          },
          deleteMechanizm(id){
            axios.post('/mechanizm/delete', { id}).then((response) =>{
              this.success = true
              this.getProfileMechanizms()
              setTimeout(() => {
                this.success = false
              }, 5000)
            })
          },
          editMechanizm(id){
            this.editId = id
            this.search = false
          },
          backToSearch(){
            this.editId = null
            this.search = true
          }
        },
        computed: {
          Mechanizms() {
            return this.mechanizms.filter(item => item.number.toLowerCase().indexOf(this.searchMechanizms.toLowerCase()) !== -1)
          },
        },
    }
</script>
