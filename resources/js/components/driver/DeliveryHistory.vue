<template>
  <v-data-table :headers="headers" :items="deliveries" :search="search" :loading="loading" v-model:sort-by="sortBy">
    <template v-slot:top>
      <v-toolbar color="blue">
        <v-spacer></v-spacer>
        <v-text-field v-model="search" clearable density="comfortable" hide-details placeholder="Search"
          prepend-inner-icon="mdi-magnify" style="max-width: 400px;" variant="solo" class="mr-2" theme="light" rounded="xl"></v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-btn icon="mdi-eye-arrow-right" variant="text" color="primary" :href="`/view-delivery/${item.delivery_id}`"
        target="_blank"></v-btn>
      <v-btn icon="mdi-file-chart-check" variant="text" color="yellow" :href="`/proof/${item.delivery_id}`"
        target="_blank" v-if="item.is_delivered >= 3"></v-btn>
      <v-btn icon="mdi-sign-direction" variant="text" color="red" :href="`/route/${item.delivery_id}`" target="_blank"
        v-if="item.is_delivered >= 3"></v-btn>
    </template>


    <template v-slot:item.status="{ item }">
      <v-btn variant="tonal" color="success" prepend-icon="mdi-check-circle"
        v-if="item.is_delivered === 0">Pending</v-btn>
      <v-btn variant="tonal" color="yellow" prepend-icon="mdi-truct-fast"
        v-if="item.is_delivered === 1 || item.is_delivered === 2">Delivering</v-btn>
      <v-btn variant="tonal" color="orange" prepend-icon="mdi-ticket-confirmation" v-if="item.is_delivered === 3">Waiting
        Confirmation</v-btn>
      <v-btn variant="tonal" color="secondary" prepend-icon="mdi-truck-check"
        v-if="item.is_delivered === 4">Delivered</v-btn>
    </template>
  </v-data-table>

  <!-- Dialogs -->

  <v-dialog v-model="confirmDelete" width="auto" persistent>
    <v-card>
      <v-card-text>
        Are you sure?
      </v-card-text>
      <v-card-actions>
        <v-btn color="info" variant="outlined" @click="confirmDelete = false">Close</v-btn>
        <v-btn color="red" variant="outlined" @click="deleteCustomer">Yes</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-snackbar v-model="deleteSnack" timeout="3000" color="red">
    Customer Successfully Deleted!
    <template v-slot:actions>
      <v-btn color="info" variant="text" @click="deleteSnack = false"></v-btn>
    </template>
  </v-snackbar>
</template>
<script>
export default {
  data() {
    return {
      sortBy: [{ key: 'delivery_id', order: 'desc' }],
      loading: false,
      headers: [
        {
          title: ' Customer Last Name',
          align: 'center',
          sortable: true,
          key: 'lname',
        },
        {
          title: 'Customer First name',
          align: 'center',
          sortable: true,
          key: 'fname',
        },
        {
          title: 'Reciever Name',
          align: 'center',
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
        {
          title: 'Status',
          align: 'center',
          sortable: true,
          key: 'status',
        },
        {
          title: 'View Location',
          align: 'center',
          sortable: false,
          key: 'actions',
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
    showDeliveries: function () {
      axios.get('/deliveries').then(
        res => {
          this.loading = true
          setTimeout(() => {
            this.loading = false
          }, 2000)

          this.deliveries = res.data;
        }
      )
    },
    initData() {
      this.showDeliveries();
    },

  },
  mounted() {
    this.initData();
  }
}
</script>
<style scoped>
.logout {
  background-color: #F3D7CA;
}

.button-refresh {
  background-color: rgb(83, 178, 255);
}
</style>