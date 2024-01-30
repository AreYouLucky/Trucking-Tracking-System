<template>

  <v-data-table
  :headers="headers"
  :items="deliveries"
  :search="search"
  :loading="loading"
  v-model:sort-by="sortBy"
>
  <template v-slot:top>
    <v-toolbar color="third">
      <v-spacer></v-spacer>
      <v-text-field v-model="search" clearable density="comfortable" hide-details placeholder="Search" prepend-inner-icon="mdi-magnify" style="max-width: 400px;" variant="solo" class="mr-2"
        ></v-text-field>
    </v-toolbar>
  </template>
  <template v-slot:item.actions="{ item }">
    <v-btn icon="mdi-eye-arrow-right" variant="text" color="primary" :href="`/view-delivery/${item.delivery_id}`" target="_blank"></v-btn>
  </template>

  <template v-slot:item.status="{ item }">
    <v-btn variant="tonal" color="success" prepend-icon="mdi-check-circle" v-if="item.is_delivered === 0">Pending</v-btn>
    <v-btn variant="tonal" color="yellow" prepend-icon="mdi-eye-circle-outline" v-if="item.is_delivered === 1">Delivered</v-btn>
  </template>
  </v-data-table>

<!-- Dialogs -->

<v-dialog
    v-model="confirmDelete"
    width="auto"
    persistent
  >
    <v-card>
      <v-card-text>
        Are you sure?
      </v-card-text>
      <v-card-actions>
        <v-btn color="info" variant="outlined" @click="confirmDelete = false">Close</v-btn>
        <v-btn color="red"  variant="outlined" @click="deleteCustomer">Yes</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

<v-snackbar
  v-model="deleteSnack"
  timeout="3000"
  color="red"
>
  Customer Successfully Deleted!
    <template v-slot:actions>
      <v-btn
        color="info"
        variant="text"
        @click="deleteSnack = false"
      ></v-btn>
    </template>
</v-snackbar>

</template>
<script>
  export default {
    data () {
      return {
          sortBy: [{ key: 'delivery_id', order: 'desc' }],
          loading: false,
          headers: [
            {
              title: 'View Location',
              align: 'center',
              sortable: false,
              key: 'actions',
            },
            {
              title: 'Status',
              align: 'center',
              sortable: true,
              key: 'status',
            },
            {
              title: ' Driver Last Name',
              align: 'start',
              sortable: true,
              key: 'lname',
            },
            {
              title: 'Customer Username',
              align: 'start',
              sortable: true,
              key: 'username',
            },
            {
              title: 'Reciever Name',
              align: 'start',
              sortable: false,
              key: 'reciever_name',
            },
            {
              title: 'Reciever No',
              align: 'center',
              sortable: false,
              key: 'reciever_no',
            },
            {
              title: 'Pickup Date',
              align: 'center',
              sortable: false,
              key: 'date',
            },
            {
              title: 'Pickup Time',
              align: 'center',
              sortable: false,
              key: 'time',
            },
          ],
        visible: false,
        itemsPerPage: 4,       
        search: '',       
        confirmDelete: false,
        row: '',
        deleteSnack: false,
        deliveries: [],
      }
    },
    methods: {
      showDeliveries: function(){
        axios.get('/load-deliveries').then(
          res=>{
            this.loading = true
            setTimeout(() => {
              this.loading = false
            }, 2000)

            this.deliveries = res.data;
          }
        )
      },
      initData(){
        this.showDeliveries();
      },
      popDelete(row){
        this.row = row;
        this.confirmDelete = true;
      },
      deleteCustomer(){
        axios.post('/delete-customer/'+this.row).then(res=>{
            this.showDeliveries();
            this.confirmDelete = false;
            this.row = ''
        })
      },

    },
    mounted() {
      this.initData();
  }
  }
</script>
<style scoped>
.logout{
      background-color: #F3D7CA;
  }
  .button-refresh{
  background-color: rgb(83, 178, 255); 
}


</style>