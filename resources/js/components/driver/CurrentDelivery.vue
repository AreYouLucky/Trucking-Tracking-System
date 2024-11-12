<template>
  <div>
    <v-card color="black pa-5">
      <v-card-title class="heads text-center my-5">
        DELIVERY INFORMATION
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" sm="6" md="4" class="heads">
            <a v-if="location.reciever_name" class="heads">Reciever's Name: {{ location.reciever_name }}</a>
            <a v-else class="heads">Reciever's Name: <h4></h4> no data</a>
          </v-col>
          <v-col cols="12" sm="6" md="3" class="heads">
            <a v-if="location.reciever_no" class="heads">Reciever's Contact: {{ location.reciever_no }}</a>
            <a v-else class="heads">Reciever's Contact: no data</a>
          </v-col>
          <v-col cols="12" sm="6" md="3" class="heads">
            <a v-if="location.date" class="heads">Pickup Date: {{ location.date }}</a>
            <a v-else class="heads">Pickup Date: no data</a>
          </v-col>
          <v-col cols="12" sm="6" md="2" class="heads">
            <a v-if="location.time" class="heads">Pickup Time: {{ location.time }}</a>
            <a v-else class="heads">Pickup Time: no data</a>
          </v-col>
          <v-col cols="12" sm="4" md="4" class="heads">
            <a v-if="location.reciever_no" class="heads">Delivery Weight: {{ location.delivery_weight }}</a>
            <a v-else class="heads">Delivery Weight: no data</a>
          </v-col>
          <v-col cols="12" sm="8" md="8" class="heads">
            <a v-if="location.reciever_no" class="heads">Delivery Information: {{ location.delivery_info }}</a>
            <a v-else class="heads">Delivery Information: no data</a>
          </v-col>

          <v-col cols="12" sm="12" md="12" class="heads">
            <a v-if="from" class="heads">Pickup Location: {{ from.provDesc }},{{ from.brgyDesc }},{{ from.citymunDesc
              }},{{ from.from_street }},</a>
            <a v-else class="heads">Pickup Location: no data</a>
          </v-col>

          <v-col cols="12" sm="12" md="12" class="heads">
            <a v-if="from" class="heads">Delivery Location: {{ to.provDesc }},{{ to.brgyDesc }},{{ to.citymunDesc }},{{
              to.to_street }},</a>
            <a v-else class="heads">Delivery Location: no data</a>
          </v-col>

          <v-btn color="fourth" block @click="dialogStart = true" v-if="location.is_delivered == 0">
            Start Pickup
          </v-btn>
          <v-btn color="warning" block @click="dialogMid = true" v-if="location.is_delivered == 1">
            Arrived at Pickup Location!
          </v-btn>
          <v-btn color="success" block @click="dialogFin = true" v-if="location.is_delivered == 2">
            Items Delivered!
          </v-btn>
        </v-row>
      </v-card-text>
    </v-card>
    <div id="map1" class="map-container"></div>
  </div>

  <v-dialog v-model="dialogStart" width="auto" persistent>
    <v-card color="primary">
      <v-card-text>
        Do you want to start the delivery?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="secondary" variant="outlined" class="logout" @click="dialogStart = false">Close</v-btn>
        <v-btn color="fourth" variant="outlined" class="logout" @click="sendSmsCustomer()">confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog v-model="dialogMid" width="auto" persistent>
    <v-card color="primary">
      <v-card-text>
        Done picking up all items to be delivered?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="secondary" variant="outlined" class="logout" @click="dialogMid = false">Close</v-btn>
        <v-btn color="fourth" variant="outlined" class="logout" @click="sendSmsReciever()">confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog v-model="dialogFin" width="100vh" persistent>
    <v-card color="warning">
      <v-card-text>
        <v-file-input label="Add Proof of Delivery" variant="outlined" prepend-icon="mdi-camera" accept="image/*"
          v-on:change="onChange" :error-messages="errors.image ? errors.image[0] : ''"></v-file-input>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" variant="outlined" class="logout" @click="saveRoute()">confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { ref } from 'vue';
import { faCube, faTruckFast, faFlag } from "@fortawesome/free-solid-svg-icons";
export default {
  data() {
    return {
      data_id: null,
      location: [],
      map: null,
      user: [],
      routingControl: null,
      fields: [],
      start: false,
      end: false,
      dialogStart: false,
      dialogMid: false,
      dialogFin: false,
      img: null,
      driverLoc: {},
      to: [],
      from: [],
      marker: null,
      errors: []
    };
  },
  methods: {
    sendSmsCustomer() {
      axios.post('/sms-customer/' + this.location.delivery_id).then(
        res => {
          console.log('sms sent to receiver!');
          this.startDelivery();
        }
      )
    },
    sendSmsReciever() {
      axios.post('/sms-reciever/' + this.location.delivery_id).then(
        res => {
          console.log('sms sent to customer!');
          this.midDelivery();
        }
      )
    },
    startDelivery() {
      axios.post('/start-delivery/' + this.location.delivery_id).then(
        res => {
          this.getLocation();
          this.dialogStart = false;
        }
      )
    },
    midDelivery() {
      axios.post('/mid-delivery/' + this.location.delivery_id).then(
        res => {
          this.getLocation();
          this.dialogMid = false;
          this.transferRoute();
        }
      )
    },
    finishDelivery() {
      axios.post('/fin-delivery/' + this.location.delivery_id).then(
        res => {
          this.location = [];
          this.to = [];
          this.from = [];
        }
      )
    },
    saveRoute() {
      axios.post('/save-route/' + this.location.delivery_id).then(res => {
        this.uploadFile();

      }).catch(
        err => {
          this.errors = err.data.errors
        }
      )
    },
    onChange(e) {
      this.img = e.target.files[0];
    },

    uploadFile() {
      let formData = new FormData();
      formData.append('image', this.img);
      formData.append('id', this.location.delivery_id);
      formData.append('driver_id', this.location.driver_id);
      formData.append('vehicle_id', this.location.vehicle_id);

      axios.post('/delivery-proof', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(response => {
          console.log(response.data);
          this.finishDelivery();
          this.dialogFin = false;
        })
        .catch(error => {
          this.errors = error.data.errors
        });
    },
    // updateDriverLocation() {
    //   axios.post('/driver-location', this.driverLoc).then(
    //     res => {
    //     }
    //   )
    // },
    broadcastLocation() {
      axios.post('/broadcast-location', this.driverLoc).then(
        res => {

        }
      )
    },
    getLocation() {
      return new Promise((resolve, reject) => {
        axios.get('/driver-delivery').then(
          res => {
            this.location = res.data.delivery;
            this.from = res.data.from[0];
            this.to = res.data.to[0];
            resolve();
          }
        ).catch(error => {
          reject(error);
        });
      });
    },
    initializeMap() {
      this.map = new google.maps.Map(document.getElementById("map1"), {
        center: { lat: parseFloat(this.location.from_lat), lng: parseFloat(this.location.from_long) },
        zoom: 14
      });

      new google.maps.Marker({
        position: { lat: parseFloat(this.location.from_lat), lng: parseFloat(this.location.from_long) },
        map: this.map,
        title: 'Package Location',
        label: 'Package',
        icon: {
          path: faCube.icon[4],
          fillColor: "#ff8754",
          fillOpacity: 1,
          anchor: new google.maps.Point(
            faCube.icon[0] / 2,
            faCube.icon[1],
          ),
          strokeWeight: 1,
          strokeColor: "#ffffff",
          scale: 0.055,
        },
      });

      new google.maps.Marker({
        position: { lat: parseFloat(this.location.to_lat), lng: parseFloat(this.location.to_long) },
        map: this.map,
        title: 'Destination Location',
        label: 'Destination',
        icon: {
          path: faFlag.icon[4],
          fillColor: "#00b822",
          fillOpacity: 1,
          anchor: new google.maps.Point(
            faFlag.icon[0] / 2,
            faFlag.icon[1],
          ),
          strokeWeight: 1,
          strokeColor: "#ffffff",
          scale: 0.055,
        },
      });
    },
    watchGeolocation() {
      navigator.geolocation.watchPosition(this.success, this.error);
    },
    success(pos) {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      this.driverLoc.latitude = lat;
      this.driverLoc.longitude = lng;
      this.driverLoc.id = this.location.delivery_id;

      // this.updateDriverLocation();
      this.broadcastLocation();


      if (this.marker) {
        this.marker.setPosition({ lat: lat, lng: lng });
      } else {
        this.marker = new google.maps.Marker({
          position: { lat: lat, lng: lng },
          map: this.map,
          title: 'Driver Location',
          label: 'T',
          icon: {
            path: faTruckFast.icon[4],
            fillColor: "#17c5ff",
            fillOpacity: 1,
            anchor: new google.maps.Point(
              faTruckFast.icon[0] / 2,
              faTruckFast.icon[1],
            ),
            strokeWeight: 1,
            strokeColor: "#ffffff",
            scale: 0.055,
          },
        });
      }

      if (!this.routingControl) {
        this.routingControl = new google.maps.DirectionsRenderer({ suppressMarkers: true, });
        this.routingControl.setMap(this.map);
        const request = {
          origin: { lat: this.driverLoc.latitude, lng: this.driverLoc.longitude },
          destination: { lat: parseFloat(this.location.from_lat), lng: parseFloat(this.location.from_long) },
          travelMode: google.maps.TravelMode.DRIVING
        };
        new google.maps.DirectionsService().route(request, (result, status) => {
          if (status === 'OK') {
            this.routingControl.setDirections(result);
          }
        });
      }
    },
    error(err) {
      if (err.code === 1) {
        alert("Please allow geolocation access");
      } else {
        alert("Cannot get current location");
      }
    },
    transferRoute() {
      if (this.routingControl) {
        const request = {
          origin: { lat: parseFloat(this.location.from_lat), lng: parseFloat(this.location.from_long) },
          destination: { lat: parseFloat(this.location.to_lat), lng: parseFloat(this.location.to_long) },
          travelMode: google.maps.TravelMode.DRIVING
        };
        new google.maps.DirectionsService().route(request, (result, status) => {
          if (status === 'OK') {
            this.routingControl.setDirections(result);
          }
        });
      }
    },
    initData() {
      const urlParts = window.location.href.split("/");
      this.data_id = urlParts[urlParts.length - 1];
      this.getLocation().then(() => {
        this.initializeMap();
      }).then(() => {
        this.watchGeolocation();
      }).catch(error => {
        console.error('Error fetching location:', error);
      });
    }
  },
  mounted() {
    this.initData();
  }
};
</script>

<style scoped>
.map-container {
  height: 80vh;
  width: 100%;
  margin-bottom: 20px;
}

* {
  color: black;
}

.heads {
  color: beige;
}
</style>
