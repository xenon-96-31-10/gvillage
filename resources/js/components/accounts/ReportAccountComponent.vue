<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else-if="!id" class="alert alert-danger" role="alert">
        У Вас нет ЛС! Сначала заведите его, прежде чем смотреть историю.
      </div>
      <div v-else-if="logs.length < 1" class="alert alert-danger" role="alert">
        Пока нет никакой деятельности.
      </div>
      <div v-else class="row">         
        <div class="input-group my-1">
          <input  v-model="searchLogs"
                  type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
          <button v-on:click="searchLogs = ''" :class="{'disabled': searchLogs ==''}"
                  class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
        </div>
        <div style="overflow-x: auto; height: 300px">
          <transition-group name="slide-fade">
            <div v-for="log in Logs" v-bind:key="log.id" class="alert my-2" :class="status(log.status)" role="alert">
              <div class="d-flex flex-row justify-content-between">
                <div class="p-2 border-end">{{log.id}}</div>
                <div class="p-2 border-end">{{log.status}}</div>
                <div class="p-2 border-end">{{log.total}} &#8381;</div>
                <div class="p-2">{{log.date}}</div>
              </div>
            </div>
          </transition-group>
        </div>

      </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        mounted() {
            console.log('Component mounted.')
            if(this.id) {
              this.getLogs()
            }else{
              this.loader = false
            }
        },
        methods: {
          getLogs(){
            axios.post('/api/get-personal-account-report', {id: this.id}).then((response) =>{
              this.logs = response.data
              this.loader = false
            })
          },
          status(type){
            return {
              'alert-success': type === 'Пополнение',
              'alert-danger': type === 'Списание',
            }
          }
        },
        computed: {
          Logs(){
            return this.logs.filter(item => item.status.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1 || item.total.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1 || item.date.toLowerCase().indexOf(this.searchLogs.toLowerCase()) !== -1)
          }
        },
        data(){
          return {
            loader: true,
            searchLogs: '',
            logs: [],
          }
        }
    }
</script>
<style scoped>
.slide-fade-enter-active {
  transition-group: all 0.5s ease;
}

.slide-fade-leave-active {
  transition-group: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}
.offcanvas-bottom{
  height: 40vh!important;
}
</style>
