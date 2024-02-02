<template>
    <div>
      <div id="map1" class="map-container"></div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import L from 'leaflet';
  import 'leaflet-routing-machine';
  import axios from 'axios';
  import boxImage from './../../../img/box.png'
  import homeImage from './../../../img/home.png'
  import markImage from './../../../img/mark.png'
  
  
  export default {
    data() {
      return {
        driver_id: null,
        location: [],
        map: null,
      };
    },
    methods: {
      getLocation() {
        return new Promise((resolve, reject) => {
          axios.get('/driver-location-data/' + this.driver_id).then(
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
        var customIcon1 = L.icon({
          iconUrl: homeImage,
          iconSize: [50, 50],
          iconAnchor: [20, 40],
          popupAnchor: [5, -40]
        });
        var customIcon3 = L.icon({
          iconUrl: markImage,
          iconSize: [30, 50],
          iconAnchor: [15, 54],
          popupAnchor: [5, -38]
        });
  
        var mark1 = L.marker([this.location.from_lat, this.location.from_long], { icon: customIcon }).addTo(this.map)
          .bindPopup('Pickup Location Here!').openPopup(); 
  
        var mark2 = L.marker([this.location.to_lat, this.location.to_long],{ icon: customIcon1 }).addTo(this.map)
          .bindPopup('Delivery Location Here!').openPopup(); 

          var mark2 = L.marker([this.location.driver_lat, this.location.driver_long],{ icon: customIcon3 }).addTo(this.map)
          .bindPopup('Driver Location Here!').openPopup(); 

      },
    },
    mounted() {
      const urlParts = window.location.href.split("/");
      this.driver_id = urlParts[urlParts.length - 1];
      
      this.getLocation().then(() => {
        this.initializeMap();
      }).catch(error => {
        console.error('Error fetching location:', error);
      });
    }
  };
  </script>
  
  <style scoped>
  .map-container {
    height: 100vh;
    width: 100%;
    margin-bottom: 20px;
  }
  *{
    color: black;
  }
  </style>
  