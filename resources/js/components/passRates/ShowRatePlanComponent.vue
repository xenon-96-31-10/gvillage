<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">{{form.name}}</h5>
            <p class="card-text">{{form.default ? 'Тариф назначен по умолчанию' : 'Не по умолчанию'}}</p>
          </div>
        </div>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">"Разовый пропуск"</h5>

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
                      {{item.description}}
                    </td>
                    <td class="rounded-end">
                      {{item.price}} &#8381;
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
                      {{item.description}}
                    </td>
                    <td class="rounded-end">
                      {{item.price}} &#8381;
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
                      {{item.description}}
                    </td>
                    <td class="rounded-end">
                      {{item.price}} &#8381;
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">"Постоянный пропуск"</h5>

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
                      {{item.description}}
                    </td>
                    <td>{{item.time}}</td>
                    <td class="rounded-end">
                      {{item.price}} &#8381;
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
                      {{item.description}}
                    </td>
                    <td>{{item.time}}</td>
                    <td class="rounded-end">
                      {{item.price}} &#8381;
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card mb-2">
          <div class="card-body">
            <p class="card-text">Действует для: групп - {{form.selectedGroups.length}} / объектов - {{form.selectedProjects.length}}</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        mounted() {
            console.log('Component mounted.')
            this.getPlan()
        },
        data(){
          return{
            loader: true,
            form: {
              id: '',
              name: '',
              default: false,
              temporary: [

              ],
              permanent: [

              ],
              selectedGroups: [],
              selectedProjects: [],
            },
          }
        },
        methods:{
          getPlan(){
            axios.post('/api/get-pass-plan', {id: this.id}).then((response) =>{
              console.log(response.data)
              this.form = response.data
              this.loader = false
            })
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
</style>
