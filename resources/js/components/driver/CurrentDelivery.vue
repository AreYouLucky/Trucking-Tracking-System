<template>
  <div>
    <v-card color="primary pa-5">
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
          v-on:change="onChange"></v-file-input>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="secondary" variant="outlined" class="logout" @click="dialogFin = false">Close</v-btn>
        <v-btn color="primary" variant="outlined" class="logout" @click="saveRoute()">confirm</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

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
      marker: null
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
        console.log('Route Successfully Saved!')
        this.finishDelivery();
        this.getLocation();
        this.uploadFile();
        this.dialogFin = false;
      })
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
        })
        .catch(error => {
          console.error(error);
        });
    },
    updateDriverLocation() {
      axios.post('/driver-location', this.driverLoc).then(
        res => {
        }
      )
    },
    cacheLocation() {
      axios.post('/cache-location', this.driverLoc).then(
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
        title: 'Package Location'
      });

      new google.maps.Marker({
        position: { lat: parseFloat(this.location.to_lat), lng: parseFloat(this.location.to_long) },
        map: this.map,
        title: 'Destination Location'
      });
    },
    watchGeolocation() {
      navigator.geolocation.watchPosition(this.success, this.error);
    },
    success(pos) {
      var truckSvgPath = "M23.2,12.4c-0.3-0.6-0.6-1.1-1.1-1.6c-0.5-0.5-1.1-0.8-1.6-1.1V7.6C20.5,6.2,19.4,5,18,5H4C2.6,5,1.5,6.2,1.5,7.6v6.4c0,1.4,1.1,2.6,2.5,2.6h0.3c0.4,1.2,1.6,2,2.9,2c1.3,0,2.5-0.8,2.9-2h7.1c0.4,1.2,1.6,2,2.9,2c1.3,0,2.5-0.8,2.9-2h0.3c1.4,0,2.5-1.2,2.5-2.6C23.5,12.9,23.4,12.6,23.2,12.4z M4,8.6h10V13H4V8.6z M7,16c-0.8,0-1.5-0.7-1.5-1.5S6.2,13,7,13s1.5,0.7,1.5,1.5S7.8,16,7,16z M18,16c-0.8,0-1.5-0.7-1.5-1.5S17.2,13,18,13s1.5,0.7,1.5,1.5S18.8,16,18,16z M19,11H17V8.6h1.8c0.2,0,0.4,0.1,0.6,0.2c0.2,0.2,0.3,0.4,0.3,0.7V11z";
      var truckIcon = {
        path: truckSvgPath,
        fillColor: "blue",
        fillOpacity: 0.6,
        strokeWeight: 0,
        rotation: 0,
        scale: 2,  // Adjust the scale as needed
        anchor: new google.maps.Point(10, 25)
      };
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      this.driverLoc.latitude = lat;
      this.driverLoc.longitude = lng;
      this.driverLoc.id = this.location.delivery_id;

      this.updateDriverLocation();
      this.cacheLocation();
      
      if (this.marker) {
              this.driverMarker.setPosition({ lat: lat, lng: lng });
      } else {
        this.marker = new google.maps.Marker({
          position: { lat: lng, lng: lng },
          map: this.map,
          title: 'Driver Location',
        });
      }

      if (!this.routingControl) {
        this.routingControl = new google.maps.DirectionsRenderer();
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
