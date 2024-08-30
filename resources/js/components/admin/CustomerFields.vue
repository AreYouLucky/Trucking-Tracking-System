<template>

  <v-data-table :headers="headers" :items="customers" :search="search" :loading="loading">
    <template v-slot:top>
      <v-toolbar color="blue">
        <v-btn prepend-icon="mdi-plus-circle" @click="addCustomer = true" elevation="3" color="white" variant="flat" rounded="xl">
          <b>Add Customer</b>
        </v-btn>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" clearable density="comfortable" hide-details placeholder="Search" theme="light"
          prepend-inner-icon="mdi-magnify" style="max-width: 400px;" variant="solo" class="mr-2" rounded="xl"></v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-btn icon="mdi-trash-can" variant="text" color="red" @click="popDelete(item.user_id)">
      </v-btn> <v-btn icon="mdi-pen" variant="text" color="success" @click="editCustomer(item.user_id)"></v-btn>
    </template>
  </v-data-table>

  <!-- Dialogs -->
  <v-row justify="center">
    <v-dialog v-model="addCustomer" persistent width="1024">
      <v-card>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="fields.fname" variant="outlined" rounded="xl" color="primary" label="First Name" required
                  :rules="[rules.required]" :error-messages="errors.fname ? errors.fname[0] : ''"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="fields.mname" variant="outlined" rounded="xl" color="primary"
                  label="Middle Name"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="fields.lname" variant="outlined" rounded="xl" color="primary" label="Last Name"
                  :rules="[rules.required]" :error-messages="errors.lname ? errors.lname[0] : ''"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="fields.suffix" variant="outlined" rounded="xl" color="primary" label="Suffix"></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-select v-model="fields.sex" variant="outlined" rounded="xl" color="primary" :items="['Male', 'Female']" label="Sex"
                  required :rules="[rules.required]" :error-messages="errors.sex ? errors.sex[0] : ''"></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="fields.email" variant="outlined" rounded="xl" color="primary" label="Email" required
                  :rules="[rules.required, rules.email]"
                  :error-messages="errors.email ? errors.email[0] : ''"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="fields.contact" variant="outlined" rounded="xl" color="primary" label="Phone Number" required
                  :rules="[rules.required, rules.phone]"
                  :error-messages="errors.contact ? errors.contact[0] : ''"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-select item-value="provCode" item-title="provDesc" :items="provinces" v-model="fields.province"
                  @update:modelValue="loadCities" variant="outlined" rounded="xl" color="primary" label="Province" required
                  :error-messages="errors.province ? errors.province[0] : ''" :rules="[rules.required]"></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-select item-value="citymunCode" item-title="citymunDesc" :items="cities" v-model="fields.city"
                  @update:modelValue="loadBarangays" variant="outlined" rounded="xl" color="primary" label="City"
                  :error-messages="errors.city ? errors.city[0] : ''" :rules="[rules.required]"></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-select item-value="brgyCode" item-title="brgyDesc" :items="barangays" v-model="fields.barangay"
                  variant="outlined" rounded="xl" color="primary" label="Barangay" :rules="[rules.required]"
                  :error-messages="errors.barangay ? errors.barangay[0] : ''"></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="fields.street" variant="outlined" rounded="xl" color="primary" label="Street"
                  :rules="[rules.required]" :error-messages="errors.street ? errors.street[0] : ''"></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="fields.username" variant="outlined" rounded="xl" color="primary" label="Username" required
                  :rules="[rules.required]" :error-messages="errors.username ? errors.username[0] : ''"></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4" v-if="row === ''">
                <v-text-field v-model="fields.password" label="Password" color="primary" :rules="[rules.required]"
                  :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
                  variant="outlined" rounded="xl" @click:append-inner="visible = !visible"></v-text-field>
              </v-col>

            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="mb-4">
          <v-spacer></v-spacer>
          <v-btn elevation="3" color="dark" variant="tonal" @click="closeCustomerDialog" rounded="xl">
            Close
          </v-btn>
          <v-btn elevation="3" color="info" variant="tonal" v-if="row === ''" @click="submitCustomer" rounded="xl">
            Save
          </v-btn>
          <v-btn elevation="3" color="info" variant="tonal" v-if="row" @click="updateCustomer" rounded="xl">
            Update
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>

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
  <v-snackbar v-model="editSnack" timeout="3000" color="blue">
    Customer Data Successfully Updated!
    <template v-slot:actions>
      <v-btn color="info" variant="text" @click="deleteSnack = false"></v-btn>
    </template>
  </v-snackbar>
  <v-snackbar v-model="storeSnack" timeout="3000" variant="tonal" color="blue">
    Customer Successfully Added!
    <template v-slot:actions>
      <v-btn color="success" variant="text" @click="storeSnack = false"></v-btn>
    </template>
  </v-snackbar>

</template>
<script>
export default {
  data() {
    return {
      rules: {
        required: value => !!value || 'Required.',
        min: v => v.length >= 4 || 'Minimum 4 characters',
        email(value) {
          if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true

          return 'Must be a valid e-mail.'
        },
        phone(value) {
          if (/^(09\d{9}|\\+639\d{9})$/i.test(value)) return true

          return 'Must be a valid phone number.'
        },
      },
      loading: false,
      headers: [
        {
          title: 'Actions',
          align: 'center',
          sortable: false,
          key: 'actions',
        },
        {
          title: 'First Name',
          align: 'start',
          sortable: true,
          key: 'fname',
        },
        {
          title: 'Last Name',
          align: 'start',
          sortable: true,
          key: 'lname',
        },
        {
          title: 'Contact',
          align: 'center',
          sortable: false,
          key: 'contact_no',
        },
        {
          title: 'Email',
          align: 'center',
          sortable: false,
          key: 'email',
        },
        {
          title: 'Province',
          align: 'start',
          sortable: false,
          key: 'provDesc',
        },
        {
          title: 'City',
          align: 'center',
          sortable: false,
          key: 'citymunDesc',
        },
        {
          title: 'Barangay',
          align: 'center',
          sortable: false,
          key: 'brgyDesc',
        },
        {
          title: 'Street',
          align: 'center',
          sortable: false,
          key: 'street',
        },


      ],
      visible: false,
      itemsPerPage: 4,
      addCustomer: false,
      customers: [

      ],
      fields: {
      },
      search: '',
      provinces: [
      ],
      province: '',
      city: '',
      barangay: '',
      cities: [],
      barangays: [],
      confirmDelete: false,
      row: '',
      storeSnack: false,
      editSnack: false,
      deleteSnack: false,
      errors: {},
    }
  },
  methods: {
    showCustomer: function () {
      axios.get('/show-customers').then(
        res => {
          this.loading = true
          setTimeout(() => {
            this.loading = false
          }, 2000)

          this.customers = res.data;
        }
      )
    },
    loadProvinces: function () {
      axios.get('/load-provinces').then(res => {
        this.provinces = res.data;
      })
    },
    loadCities: function () {
      axios.get('/load-cities?prov=' + this.fields.province).then(res => {
        this.cities = res.data;
      })
    },

    loadBarangays: function () {
      axios.get('/load-barangays?prov=' + this.fields.province + '&city_code=' + this.fields.city).then(res => {
        this.barangays = res.data;
      })
    },
    initData() {
      this.loadProvinces();
      this.showCustomer();
    },
    clearFields() {
      this.fields = {};
      this.row = '';
    },
    popDelete(row) {
      this.row = row;
      this.confirmDelete = true;
    },

    submitCustomer() {

      axios.post('/add-customer', this.fields, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then(res => {
        this.storeSnack = true;
        this.showCustomer();
        this.addCustomer = false;
        this.clearFields();
        this.errors = {}
      })
        .catch(error => {
          this.errors = error.response.data.errors
        })
    },
    deleteCustomer() {
      axios.post('/delete-customer/' + this.row).then(res => {
        this.showCustomer();
        this.confirmDelete = false;
        this.row = ''
      })
    },
    showEdit(row) {
      axios.get('/edit-customer/' + row).then(res => {
        this.fields.lname = res.data[0].customer.lname;
        this.fields.fname = res.data[0].customer.fname;
        this.fields.mname = res.data[0].customer.mname;
        this.fields.suffix = res.data[0].customer.suffix;
        this.fields.sex = res.data[0].customer.sex;
        this.fields.contact = res.data[0].customer.contact_no;
        this.fields.email = res.data[0].customer.email;
        this.fields.province = res.data[0].customer.province;
        this.fields.city = res.data[0].customer.city;
        this.fields.barangay = res.data[0].customer.barangay;
        this.fields.street = res.data[0].customer.street;
        this.fields.username = res.data[0].username;
        this.fields.user_id = res.data[0].user_id;
      })
    },
    closeCustomerDialog() {
      this.addCustomer = false;
      this.row = '';
      this.clearFields();
    },
    editCustomer(row) {
      this.fields = {};
      this.addCustomer = true
      this.row = row;
      if (this.row > 0) {
        this.showEdit(row)
      }
    },
    updateCustomer() {
      axios.post('update-customer', this.fields).then(
        res => {
          this.showCustomer();
          this.addCustomer = false;
          this.errors = {};
          this.fields = {}
        }
      ).catch(error => {
        this.errors = error.response.data.errors
      })
    }

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
</style>