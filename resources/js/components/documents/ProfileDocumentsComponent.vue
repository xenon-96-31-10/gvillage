<template>
    <div>
      <transition name="slide-fade">
        <div v-if="success.check" class="alert alert-success" role="alert">
           {{success.text}}
        </div>
      </transition>
      <Loader v-if="loader"/>
      <div v-else>
        <div class="card my-2">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">Документы</h5>
            </div>
          </div>

          <div class="card-body">
            <div v-if="docs.length > 0" class="input-group my-1">
              <input  v-model="searchDocs"
                      type="text" class="form-control" placeholder="Поиск" aria-label="Поиск" aria-describedby="clear">
              <button @click="searchDocs = ''" :class="{'disabled': searchDocs ==''}"
                      class="btn btn-outline-secondary" type="button"><i class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="list-group">
              <label v-for="doc in Docs" :key="doc.id" class="list-group-item">
                <input v-model="selectedDocs" class="form-check-input me-1" type="checkbox" :value="doc.id">
                {{doc.name}}
              </label>
              <label v-if="Docs.length < 1" class="list-group-item list-group-item-danger">
                Нет документов
              </label>
            </div>
            <div v-if="Docs.length > 0" class="d-grid gap-2 d-md-flex justify-content-md-end my-2">
              <button class="btn btn-danger text-white me-md-2" type="button" @click="deleteDocs">Удалить выбранные</button>
              <button class="btn btn-info text-white" type="button" @click="downloadZip">Скачать выбранные архивом</button>
            </div>

          </div>
        </div>

        <div class="card my-2">
          <div class="card-body">
            <div class="row g-2 my-2">
              <div class="col-sm-8">
                <div class="form-floating">
                  <select v-model="doc.name" class="form-select" id="floatingSelectGrid" aria-label="Floating label select example">
                    <option value="Паспорт">Паспорт</option>
                    <option value="СНИЛС">СНИЛС</option>
                    <option value="Водительское удостоверение">Водительское удостоверение</option>
                    <option value="Договор">Договор</option>
                  </select>
                  <label for="floatingSelectGrid">Выберите документ для загрузки</label>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="d-grid gap-2 my-auto">
                  <label
                        class="btn btn-primary btn-lg text-white" for="doc">
                        <input type="file" @change="selectDoc" id="doc" class="d-none"/>
                        Загрузить и добавить
                        </label>
                </div>
              </div>
            </div>
            <ul v-if="errors"class="list-group">
              <li v-for="error in errors" class="list-group-item list-group-item-danger">{{error[0]}}</li>
            </ul>
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
            this.getProfileDocs()
        },
        data() {
            return {
              loader: true,
              docs: [],
              doc: {
                id: this.profileId,
                file: null,
                name: 'Паспорт',
              },
              selectedDocs: [],
              success: {
                check: false,
                text: ''
              },
              errors: [],
              searchDocs: '',
            }
        },
        methods: {
          getProfileDocs(){
            this.loader = true
            axios.post('/api/get-profile-documents', { id : this.profileId}).then((response) =>{
              this.docs = response.data
              this.loader = false
            })
          },
          selectDoc(event) {
            this.doc.file = event.target.files[0]
            if(this.doc.file != null){
              this.uploadDoc()
            }
          },
          uploadDoc(){
            let formData = new FormData()
            formData.append('file', this.doc.file)
            formData.append('id', this.doc.id)
            formData.append('name', this.doc.name)
            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
            }
            axios.post('/document/upload-document', formData, config).then((response) =>{
              this.errors= []
              this.getProfileDocs()

              this.success.check = true
              this.success.text = 'Вы успешно загрузили Документ !'

              setTimeout(() => {
                this.success.check = false
                this.success.text = ''
              }, 5000)
            }).catch((error) =>{
              this.errors = error.response.data.errors
            })
          },
          downloadZip(){
            const config = {
                    headers: {
                        responseType: "arraybuffer",
                    }
            }
            if(this.selectedDocs.length > 0){
              axios.post('/document/download-documents-to-zip', {docs: this.selectedDocs, id: this.profileId}, {
                    responseType: 'blob'
               }).then((response) =>{
                const url = window.URL.createObjectURL(new Blob([response.data], {type: 'application/zip'}));
                const link = document.createElement('a');
                 link.href = url;
                 link.setAttribute('download', 'download.zip');
                 document.body.appendChild(link);
                 link.click();
                 this.selectedDocs = []
              })
            }else{
              alert('Сначала выберите документы для скачивания')
            }
          },
          deleteDocs(){
            if(this.selectedDocs.length > 0){
              axios.post('/document/delete-documents', {docs: this.selectedDocs}).then((response) =>{
                this.getProfileDocs()

                this.success.check = true
                this.success.text = 'Вы удалили документы!'
                this.selectedDocs = []

                setTimeout(() => {
                  this.success.check = false
                  this.success.text = ''
                }, 5000)
              })
            }else{
              alert('Сначала выберите документы для скачивания')
            }
          },
        },
        computed: {
          Docs() {
            return this.docs.filter(item => item.name.toLowerCase().indexOf(this.searchDocs.toLowerCase()) !== -1)
          },
        },
    }
</script>
