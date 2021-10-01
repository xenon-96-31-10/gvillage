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
              <h5 class="card-title mb-0">Автомобили</h5>
            </div>
          </div>
          <div class="card-body">
            <div v-if="search">
              <div v-if="cars.length > 0" class="input-group my-1">
                <input  v-model="searchCars"
                        type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
                <button @click="searchCars = ''" :class="{'disabled': searchCars ==''}"
                        class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
              </div>
              <ol class="list-group list-group-numbered mb-2">
                <li v-for="car in Cars" :key="car.id" class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">{{car.number}}</div>
                    {{car.body}}
                  </div>
                  <div>
                    <button type="button" class="btn btn-danger text-white me-2" @click="deleteCar(car.id)"><i class="bi bi-trash-fill"></i></button>
                    <button type="button" class="btn btn-primary text-white me-2" @click="editCar(car.id)"><i class="bi bi-pencil-square"></i></button>
                  </div>
                </li>
                <li v-if="Cars.length < 1"  class="list-group-item list-group-item-danger d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold">Нет автомобилей</div>
                  </div>
                </li>
              </ol>
            </div>
            <div v-else>
              <edit-car-component :id="editId" @refreshList="actionCar('update')"/>
              <div class="d-grid gap-2 my-2">
                <button class="btn btn-dark" type="button" @click="backToSearch">Отменить</button>
              </div>
            </div>

          </div>
        </div>

        <div class="card my-2">
          <div class="card-body">
            <create-car-component v-if="createCar" :id-owner="profileId" @refreshList="actionCar('create')"/>
            <div class="d-grid gap-2 my-2">
              <button v-if="createCar" class="btn btn-dark" type="button" @click="createCar = false">Отменить</button>
              <button v-else class="btn btn-primary text-white" type="button" @click="createCar = true">Добавить автомобиль</button>
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
            this.getProfileCars()
        },
        data() {
            return {
              loader: true,
              cars: [],
              search: true,
              success: false,
              editId: null,
              createCar: false,
              searchCars: '',
            }
        },
        methods: {
          getProfileCars(){
            this.loader = true
            axios.post('/api/get-profile-cars', { id : this.profileId}).then((response) =>{
              this.cars = response.data
              this.loader = false
            })
          },
          actionCar(type){
            type === 'create' ? this.createCar = false  : this.search = true
            this.success = true
            this.getProfileCars()
            setTimeout(() => {
              this.success = false
            }, 5000)
          },
          deleteCar(id){
            axios.post('/car/delete', { id}).then((response) =>{
              this.success = true
              this.getProfileCars()
              setTimeout(() => {
                this.success = false
              }, 5000)
            })
          },
          editCar(id){
            this.editId = id
            this.search = false
          },
          backToSearch(){
            this.editId = null
            this.search = true
          }
        },
        computed: {
          Cars() {
            return this.cars.filter(item => item.number.toLowerCase().indexOf(this.searchCars.toLowerCase()) !== -1)
          },
        },
    }
</script>
