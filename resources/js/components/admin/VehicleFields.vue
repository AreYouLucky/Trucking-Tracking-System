<template>
    <v-data-iterator
      :items="vehicles"
      items-per-page="8"
      :search="search"
      :loading ="loading"
    >
    <template v-slot:header>
        <v-toolbar class="px-3 pt-2" color="third">
        <v-btn prepend-icon="mdi-plus-circle" @click="addVehicle = true"  elevation="3" color="fourth">
            <b>Add Vehicle</b>
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
                :title="item.raw.name"
                density="comfortable"
    
              >
                <template v-slot:title>
                  <strong>
                    <h3>{{ item.raw.name}}</h3>
                  </strong>
                </template>
              </v-list-item>
  
              <v-table density="compact" class="text-caption">
                <tbody>
                  <tr align="right">
                    <th>Status:</th>
  
                    <td class="py-2">
                      <v-btn variant="tonal" color="blue" prepend-icon="mdi-check-circle" v-if="item.raw.is_available === 1">Available</v-btn>
                      <v-btn variant="tonal" color="red" prepend-icon="mdi-truck" v-if="item.raw.is_available === 0">Unavailable</v-btn>
                    </td>
                  </tr>
  
                  <tr align="right">
                    <th>Vehicle Type:</th>
                    <td>{{ item.raw.vehicle_type }}</td>
                  </tr>
  
                  <tr align="right">
                    <th>Plate No:</th>
                    <td>{{ item.raw.plate_no }}</td>
                  </tr>
                  <tr align="right">
                    <th>Actions:</th>
  
                    <v-btn icon="mdi-trash-can" variant="text" color="red" @click="popDelete(item.raw.vehicle_id)">
                    </v-btn> <v-btn icon="mdi-pen" variant="text" color="success" @click="editDriver(item.raw.vehicle_id)"></v-btn>
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
      v-model="addVehicle"
      persistent
      width="400"
    >
      <v-card color="primary">
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="fields.name"
                  variant="outlined"
                  label="Vehicle Name"
                  required
                  hint="Include the model"
                  :rules="[rules.required]"
                  :error-messages="errors.name ? errors.name[0] : ''"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="fields.plate_no"
                  variant="outlined"
                  label="Plate No"
                  :rules="[rules.required]"
                  :error-messages="errors.plate_no ? errors.plate_no[0] : ''"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="fields.vehicle_type"
                  variant="outlined"
                  :items="['Truck','Van','Trailer','Panel']"
                  label="Vehicle Type"
                  required
                  :rules="[rules.required]"
                  :error-messages="errors.vehicle_type ? errors.vehicle_type[0] : ''"
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="mb-4">
          <v-spacer></v-spacer>
          <v-btn
            elevation="3"
            variant="tonal"
            @click="closeVehicleDialog"
          >
            Close
          </v-btn>
          <v-btn
            elevation="3"
            color="success"
            variant="tonal"
            @click="submitVehicle"
            v-if="row===''"
          >
            Save
          </v-btn>
          <v-btn
            elevation="3"
            color="info"
            variant="tonal"
            @click="updateVehicle"
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
          <v-btn color="red"  variant="outlined" @click="deleteVehicle">Yes</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


  <v-snackbar
    v-model="deleteSnack"
    timeout="3000"
    color="red"
  >
    Vehicle Successfully Deleted!
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
    Vehicle Data Successfully Updated!
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
    Vehicle Successfully Added!
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
            },
          visible: false,
          itemsPerPage: 6,
          addVehicle: false,
          vehicles: [
            
          ],
          errors: {},
          loading: false,
          fields: {
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
        showVehicles: function(){
          axios.get('/show-vehicles').then(
            res=>{
              this.loading = true;
              setTimeout(() => {
                this.loading = false;
              }, 2000)
              this.vehicles = res.data;
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
          this.loadProvinces();
          this.showVehicles();
        },
        clearFields(){
          this.fields= {};
          this.errors = {};
        },
        popDelete(row){
          this.row = row;
          this.confirmDelete = true;
        },

        submitVehicle(){

          axios.post('/add-vehicle',this.fields,{headers: {
          'Content-Type': 'multipart/form-data',
        }}).then(res=>{
              this.storeSnack = true;
              this.showVehicles();
              this.addVehicle = false;
              this.clearFields();
          }).catch(error=>{
                this.errors = error.response.data.errors
              })
        },
        deleteVehicle(){
          axios.post('/delete-vehicle/'+this.row).then(res=>{
              this.showVehicles();
              this.confirmDelete = false;
              this.deleteSnack = true;
          })
        },
        showEdit(row){
          axios.get('/edit-vehicle/' + row).then(res=>{
            this.fields.name = res.data[0].name;
            this.fields.plate_no = res.data[0].plate_no;
            this.fields.vehicle_type = res.data[0].vehicle_type;
            this.fields.vehicle_id = res.data[0].vehicle_id;
            })
        },
        closeVehicleDialog(){
          this.addVehicle = false;
          this.fields = {}
          this.row = '';
        },
        editDriver(row){
          this.fields = {};
          this.addVehicle = true
          this.row = row;
          if(this.row > 0){
              this.showEdit(row)
          }
        },
        updateVehicle(){
          axios.post('update-vehicle',this.fields).then(
            res=>{
              this.showVehicles();
              this.addVehicle= false;
              this.clearFields();
              this.editSnack = true;
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