export default {
  data() {
    return{
      searchItem: '',
      filteredItems: [],
      items: [],
      paginatedItems: [],
      noResult: false,
      pagination: {
        range: 5,
        currentPage: 1,
        itemPerPage: 2,
        items: [],
        filteredItems: [],
      },
    }
  },
  methods:{
    clearSearchItem(){
      this.searchItem = undefined
      this.searchInTheList('')
      this.noResult = false
    },
    searchInTheList(searchText, currentPage){
      this.noResult = false
      if(_.isUndefined(searchText)){
        this.filteredItems = _.filter(this.items, function(v, k){
          return !v.selected
        })
      }
      else{
        this.filteredItems = _.filter(this.items, function(v, k){
          return !v.selected && (v.title.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.description.toLowerCase().indexOf(searchText.toLowerCase()) > -1 || v.data.toLowerCase().indexOf(searchText.toLowerCase()) > -1)
        })
      }

      if(this.filteredItems.length === 0){
        this.noResult = true
      }

      this.buildPagination()

      if(_.isUndefined(currentPage)){
        this.selectPage(1)
      }
      else{
        this.selectPage(currentPage)
      }
    },
    buildPagination(){
      let numberOfPage = Math.ceil(this.filteredItems.length/this.pagination.itemPerPage)
      this.pagination.items = []
      for(var i=0; i<numberOfPage; i++){
        this.pagination.items.push(i+1)
      }
    },
    selectPage(item) {
      this.pagination.currentPage = item

      let start = 0
      let end = 0
      if(this.pagination.currentPage < this.pagination.range-2){
        start = 1
        end = start+this.pagination.range-1
      }
      else if(this.pagination.currentPage <= this.pagination.items.length && this.pagination.currentPage > this.pagination.items.length - this.pagination.range + 2){
        start = this.pagination.items.length-this.pagination.range+1
        end = this.pagination.items.length
      }
      else{
        start = this.pagination.currentPage-2
        end = this.pagination.currentPage+2
      }
      if(start<1){
        start = 1
      }
      if(end>this.pagination.items.length){
        end = this.pagination.items.length
      }

      this.pagination.filteredItems = []
      for(var i=start; i<=end; i++){
        this.pagination.filteredItems.push(i);
      }
      this.paginatedItems = this.filteredItems.filter((v, k) => {
        return Math.ceil((k+1) / this.pagination.itemPerPage) == this.pagination.currentPage
      })
    },
  }
}
