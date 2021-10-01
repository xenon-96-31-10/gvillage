<template>
    <div>
      <Loader v-if="loader"/>
      <div v-else>
        <div v-for="(item,index) in data" :key="index" class="mb-2">
          <p class="lead">{{item.role}} может: </p>
          <ol class="list-group list-group-numbered">
            <li  v-for="(item,index) in item.permissions" :key="index" class="list-group-item">{{item.name}}</li>
          </ol>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getRoles()
        },
        methods: {
          getRoles(){
            this.loader = true
            axios.post('/api/get-roles-and-permissions').then((response) =>{
              this.data = response.data
              this.loader = false
            })
          },
        },
        data() {
            return {
              loader: true,
              data: [],
            }
        },
    }
</script>
