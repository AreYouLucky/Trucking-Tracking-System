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
          <v-btn color="fourth" block @click="start=true" v-if="start==false && location.is_delivered!=2">
            Start Pickup
          </v-btn>
          <v-btn color="warning" block @click="end=true" v-if="start==true && end == false">
            Arrived at Pickup Location!
          </v-btn>
          <v-btn color="success" block @click="end=true" v-if="start==true && end == true">
            Items Delivered!
          </v-btn>
        </v-row>
        </v-card-text>
      </v-card>
      <div id="map1" class="map-container"></div>
      <button @click="transferRoute">Transfer Route to Delivery Location</button>
    </div>


    <v-dialog
        v-model="dialogStart"
        width="auto"
        persistent
        >
        <v-card color="secondary">
            <v-card-text>
                Do you want to start the delivery?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="logout"  @click="dialogStart= false">Close</v-btn>
                <v-btn color="secondary"  class="logout" @click="startDelivery">confirm</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="dialog"
        width="auto"
        >
        <v-card color="secondary">
            <v-card-text>
                Are you you want to logout?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="logout"  @click="dialog = false">Close</v-btn>
                <v-btn color="primary"  class="logout" href="/logout">confirm</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="dialog"
        width="auto"
        >
        <v-card color="secondary">
            <v-card-text>
                Start Delivery?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="logout"  @click="dialog = false">Close</v-btn>
                <v-btn color="primary"  class="logout" href="/logout">Yes</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
  </template>
  
  <script>
  import { ref } from 'vue';
  import L from 'leaflet';
  import 'leaflet-routing-machine';
  import axios from 'axios';
  import boxImage from './../../../img/box.png'
  
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
      };
    },
    methods: {
      startDelivery(){
        axios.post('/start-delivery',this.location.delivery_id).then(
          res=>{
            this.getLocation();
          }
        )
      },
      getLocation() {
        return new Promise((resolve, reject) => {
          axios.get('/driver-delivery').then(
            res => {
              this.location = res.data;
              resolve();
            }
          ).catch(error => {
            reject(error);
          });
        });
      },
      initializeMap() {
        this.map = L.map('map1').setView([this.location.from_lat, this.location.from_long], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          minZoom: 13,
        }).addTo(this.map);
  
        var customIcon = L.icon({
          iconUrl: boxImage,
          iconSize: [50, 50],
          iconAnchor: [26, 25],
          popupAnchor: [5, -38]
        });


        L.marker([this.location.from_lat, this.location.from_long], { icon: customIcon }).addTo(this.map)
          .bindPopup('Pickup Location Here!').openPopup();
  
        L.marker([this.location.to_lat, this.location.to_long]).addTo(this.map)
          .bindPopup('Delivery Location Here!').openPopup();
      },
      watchGeolocation() {
        navigator.geolocation.watchPosition(this.success, this.error);
      },
      success(pos) {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        const accuracy = pos.coords.accuracy;
  
        if (this.marker) {
          this.map.removeLayer(this.marker);
          this.map.removeLayer(this.routingControl);
        }
  
        this.marker = L.marker([lat, lng]).addTo(this.map).bindPopup('Driver Location Here!').openPopup();;
        this.routingControl = L.Routing.control({
          waypoints: [
            L.latLng(lat, lng),
            L.latLng(this.location.from_lat, this.location.from_long)
          ],
          routeWhileDragging: true
        }).addTo(this.map);
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
          const waypoints = this.routingControl.getWaypoints();
          waypoints[0].latLng = L.latLng(waypoints[0].latLng.lat, waypoints[0].latLng.lng); // Set user's current location
          waypoints[1].latLng = L.latLng(this.location.to_lat, this.location.to_long); // Set delivery location
          this.routingControl.setWaypoints(waypoints);
        }
      }
    },
    mounted() {
      const urlParts = window.location.href.split("/");
      this.data_id = urlParts[urlParts.length - 1];
  
      this.getLocation().then(() => {
        this.initializeMap();
      }).catch(error => {
        console.error('Error fetching location:', error);
      });
      this.watchGeolocation();
    }
  };
  </script>
  
  <style scoped>
  .map-container {
    height: 80vh;
    width: 100%;
    margin-bottom: 20px;
  }
  *{
    color: black;
  }
  .heads{
    color: beige;
  }
  </style>
  