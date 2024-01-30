<template>
    <v-data-iterator
      :items="drivers"
      items-per-page="8"
      :search="search"
    >
    <template v-slot:header>
        <v-toolbar class="px-3 pt-2" color="third">
        <v-btn prepend-icon="mdi-plus-circle" @click="addDriver = true"  elevation="3">
            <b>Add Driver</b>
        </v-btn>
        <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            clearable
            density="comfortable"
            hide-details
            placeholder="Search"
            prepend-inner-icon="mdi-magnify"
            style="max-width: 400px;"
            variant="solo"
          ></v-text-field>
        </v-toolbar>
      </template>
  
      <template v-slot:default="{ items }">
        <v-row class="pa-4">
          <v-col
            v-for="(item, i) in items"
            :key="i"
            cols="12"
            sm="6"
            md ="4"
            lg="4"
          >
            <v-sheet border class="pa-4" elevation="4">

              <v-list-item
                :title="item.raw.lname"
                density="comfortable"
    
              >
                <template v-slot:title>
                  <strong>
                    <h4>{{ item.raw.lname}}, {{ item.raw.fname}} {{ item.raw.mname}}</h4>
                  </strong>
                </template>
              </v-list-item>
  
              <v-table density="compact" class="text-caption">
                <tbody>
                  <tr align="right">
                    <th>Status:</th>
  
                    <td class="py-2">
                      <v-btn variant="tonal" color="success" prepend-icon="mdi-check-circle" v-if="item.raw.is_available === 1">Available</v-btn>
                      <v-btn variant="tonal" color="yellow" prepend-icon="mdi-eye-circle-outline" v-if="item.raw.is_available === 0">Delivering</v-btn>
                    </td>
                  </tr>

                  <tr align="right">
                    <th>Contact No:</th>
  
                    <td>{{ item.raw.contact_no }}</td>
                  </tr>
  
                  <tr align="right">
                    <th>License ID:</th>
                    <td>{{ item.raw.license_id }}</td>
                  </tr>
  
                  <tr align="right">
                    <th>Province:</th>
  
                    <td>{{ item.raw.provDesc }}</td>
                  </tr>
  
                  <tr align="right">
                    <th>City:</th>
                    <td>{{ item.raw.citymunDesc}}</td>
                  </tr>
  
                  <tr align="right">
                    <th>Barangay:</th>
  
                    <td>{{ item.raw.brgyDesc }}</td>
                  </tr>
                  <tr align="right">
                    <th>Actions:</th>
  
                    <v-btn icon="mdi-trash-can" variant="text" color="red" @click="popDelete(item.raw.user_id)">
                    </v-btn> <v-btn icon="mdi-pen" variant="text" color="success" @click="editDriver(item.raw.user_id)"></v-btn>
                  </tr>
                </tbody>
              </v-table>
            </v-sheet>
          </v-col>
        </v-row>
      </template>
  
      <template v-slot:footer="{ page, pageCount, prevPage, nextPage }">
        <div class="d-flex align-center justify-center pa-4">
          <v-btn
            :disabled="page === 1"
            icon="mdi-arrow-left"
            density="comfortable"
            variant="tonal"
            rounded
            @click="prevPage"
          ></v-btn>

          <div class="text-caption">
            Page {{ page }} of {{ pageCount }}
          </div>

          <v-btn
            :disabled="page >= pageCount"
            icon="mdi-arrow-right"
            density="comfortable"
            variant="tonal"
            rounded
            @click="nextPage"
          ></v-btn>
        </div>
      </template>
    </v-data-iterator>

<!-- Dialogs -->
    <v-row justify="center">
    <v-dialog
      v-model="addDriver"
      persistent
      width="1024"
    >
      <v-card color="dark">
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="fields.fname"
                  variant="outlined"
                  label="First Name"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.fname ? errors.fname[0] : ''"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                v-model="fields.mname"
                  variant="outlined"
                  label="Middle Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="fields.lname"
                  variant="outlined"
                  label="Last Name"
                  :rules="[rules.required]"
                  :error-messages="errors.lname ? errors.lname[0] : ''"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="fields.suffix"
                  variant="outlined"
                  label="Suffix"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="3">
                <v-select
                  v-model="fields.sex"
                  variant="outlined"
                  :items="['Male','Female']"
                  label="Sex"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.sex ? errors.sex[0] : ''"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="fields.license_id"
                  variant="outlined"
                  label="License ID"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.license_id ? errors.license_id[0] : ''"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                v-model="fields.contact"
                  variant="outlined"
                  label="Phone Number"
                  required
                  :rules="[rules.required,rules.phone]"
                  :error-messages="errors.contact_no ? errors.contact_no[0] : ''"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="3">
                <v-select
                  item-value="provCode"
                  item-title="provDesc"
                  :items="provinces"
                  v-model="fields.province"
                  @update:modelValue="loadCities"
                  variant="outlined"
                  label="Province"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.province ? errors.province[0] : ''"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-select
                  item-value="citymunCode"
                  item-title="citymunDesc"
                  :items="cities"
                  v-model="fields.city"
                  @update:modelValue="loadBarangays"
                  variant="outlined"
                  label="City"
                  :rules="[rules.required]"
                  :error-messages="errors.city ? errors.city[0] : ''"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-select
                  item-value="brgyCode"
                  item-title="brgyDesc"
                  :items="barangays"
                  v-model="fields.barangay"
                  variant="outlined"
                  label="Barangay"
                  :rules="[rules.required]"
                  :error-messages="errors.barangay ? errors.barangay[0] : ''"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="fields.street"
                  variant="outlined"
                  label="Street"
                  :rules="[rules.required]"
                  :error-messages="errors.street ? errors.street[0] : ''"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="fields.username"
                  variant="outlined"
                  label="Username"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.username ? errors.username[0] : ''"
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-text-field
                    v-model ="fields.password"
                    label="Password"
                    :rules="[rules.required]"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    variant="outlined"
                    @click:append-inner="visible = !visible"
                    v-if="row===''"
                ></v-text-field>
              </v-col>

            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="mb-4">
          <v-spacer></v-spacer>
          <v-btn
            elevation="3"
            color="dark"
            variant="tonal"
            @click="closeDriverDialog"
          >
            Close
          </v-btn>
          <v-btn
            elevation="3"
            color="info"
            variant="tonal"
            @click="submitDriver"
            v-if="row===''"
          >
            Save
          </v-btn>
          <v-btn
            elevation="3"
            color="info"
            variant="tonal"
            @click="updateDriver"
            v-if="row"
          >
            Update
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>

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
          <v-btn color="red"  variant="outlined" @click="deleteDriver">Yes</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


  <v-snackbar
    v-model="deleteSnack"
    timeout="3000"
    color="red"
  >
    Driver Successfully Deleted!
    <template v-slot:actions>
        <v-btn
          color="blue"
          variant="text"
          @click="deleteSnack = false"
        >
          Close
        </v-btn>
      </template>
  </v-snackbar>

  <v-snackbar
    v-model="editSnack"
    timeout="3000"
    color="blue"
  >
    Driver Data Successfully Updated!
    <template v-slot:actions>
        <v-btn
          color="blue"
          variant="text"
          @click="editSnack = false"
        >
          Close
        </v-btn>
      </template>
  </v-snackbar>
  <v-snackbar
    v-model="storeSnack"
    timeout="3000"
    color="secondary"
  >
    Driver Successfully Added!
    <template v-slot:actions>
        <v-btn
          color="blue"
          variant="text"
          @click="storeSnack = false"
        >
          Close
        </v-btn>
      </template>
  </v-snackbar>

  </template>
  <script>
    export default {
      data () {
        return {
          rules: {
            required: value => !!value || 'Required.',
            min: v => v.length >= 4 || 'Minimum 4 characters',
            email (value) {
                if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true

                return 'Must be a valid e-mail.'
            },
            phone (value) {
                if (/^(09\d{9}|\\+639\d{9})$/i.test(value)) return true

                return 'Must be a valid phone number.'
            },
            },
          visible: false,
          itemsPerPage: 6,
          addDriver: false,
          drivers: [
            
          ],
          errors: {},
          loading: false,
          fields: {
            mname: '',
            suffix: ''
          },
          search: '',
          provinces: [
          ],
          cities: [],
          barangays: [],
          confirmDelete: false,
          row: '',
          storeSnack: false,
          editSnack: false,
          deleteSnack: false,
        }
      },
      methods: {
        showDrivers: function(){
          axios.get('/show-drivers').then(
            res=>{
              this.drivers = res.data;
            }
          ).catch(error=>{
              
            }
            )
        },
        loadProvinces: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },
        loadCities: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangays: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },
        initData(){
          this.drivers = [];
          this.loadProvinces();
          this.showDrivers();
        },
        refresh: function(){
          this.drivers = [];
          this.loadProvinces();
          this.showDrivers();
        },
        clearFields(){
          this.fields= {};
          this.errors = {};
        },
        popDelete(row){
          this.row = row;
          this.confirmDelete = true;
        },

        submitDriver(){

          axios.post('/add-driver',this.fields,{headers: {
          'Content-Type': 'multipart/form-data',
        }}).then(res=>{
              this.storeSnack = true;
              this.showDrivers();
              this.addDriver = false;
              this.clearFields();
          }).catch(error=>{
                this.errors = error.response.data.errors
              })
        },
        deleteDriver(){
          axios.post('/delete-driver/'+this.row).then(res=>{
              this.showDrivers();
              this.confirmDelete = false;
              this.deleteSnack = true;
          })
        },
        showEdit(row){
          axios.get('/edit-driver/' + row).then(res=>{
            this.fields.lname = res.data[0].driver.lname;
            this.fields.fname = res.data[0].driver.fname;
            this.fields.mname = res.data[0].driver.mname;
            this.fields.suffix = res.data[0].driver.suffix;
            this.fields.sex = res.data[0].driver.sex;
            this.fields.contact = res.data[0].driver.contact_no;
            this.fields.license_id = res.data[0].driver.license_id;
            this.fields.province = res.data[0].driver.province;
            this.fields.city = res.data[0].driver.city;
            this.fields.barangay = res.data[0].driver.barangay;
            this.fields.street = res.data[0].driver.street;
            this.fields.username = res.data[0].username;
            this.fields.user_id = res.data[0].user_id;
            })
        },
        closeDriverDialog(){
          this.addDriver = false;
          this.fields = {}
          this.row = '';
        },
        editDriver(row){
          this.fields = {};
          this.addDriver = true
          this.row = row;
          if(this.row > 0){
              this.showEdit(row)
          }
        },
        updateDriver(){
          axios.post('update-driver',this.fields).then(
            res=>{
              this.showDrivers();
              this.addDriver= false;
              this.clearFields();
            }
          ).catch(error=>{
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
.logout{
        background-color: #F3D7CA;
    }
.button-refresh{
  background-color: rgb(83, 178, 255); 
}


</style>