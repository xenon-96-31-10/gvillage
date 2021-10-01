<template>
    <div>
      <div v-if='unRead > 0'>      
        <div v-for="note in unReadNotifications" class="alert alert-success" role="alert">
          {{note.data.text}}
        </div>
      </div>
      <div v-else>
        <div v-for="note in Notifications" class="alert alert-info" role="alert">
          {{note.data.text}}
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getNotification()
        },
        data() {
            return {
              unReadNotifications: [],
              Notifications: [],
              unRead: null,
            }
        },
        methods: {
          getNotification(){
            axios.post('/api/get-notifications').then((response) =>{
              this.unReadNotifications = response.data.unReadNotifications
              this.Notifications = response.data.Notifications
              this.unRead = response.data.unRead
            })
          }
        }
    }
</script>
