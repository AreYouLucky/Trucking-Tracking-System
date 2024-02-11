<template>
    <div>
      <div id="map" class="map-container"></div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';  
  export default {
    data() {
      return {
        delivery_id: null,
        location: [],
        map: null,
      };
    },
    methods: {
      getLocation() {
        return new Promise((resolve, reject) => {
          axios.get('/get-route/' + this.delivery_id).then(
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
        this.map = L.map('map').setView([this.location[0].latitude, this.location[0].longitude], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap,<a href="https://www.graphhopper.com/">GraphHopper API</a> ',
          maxZoom: 18,
          minZoom: 5,
        }).addTo(this.map);
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap, Powered by <a href="https://www.graphhopper.com/">GraphHopper API</a>',
            maxZoom: 18,
            minZoom: 5,
          });

          var eri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community, Powered by <a href="https://www.graphhopper.com/">GraphHopper API</a>',
            maxZoom: 18,
          minZoom: 5,
        });
        var baseMaps = {
          "OpenStreetMap": osm,
          "WorldImagery": eri
        };

        var layerControl = L.control.layers(baseMaps).addTo(this.map);

        for(var item of this.location){
            L.circle([item.latitude, item.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 50
                }).addTo(this.map);
        }
        

      },
    },
    mounted() {
      const urlParts = window.location.href.split("/");
      this.delivery_id = urlParts[urlParts.length - 1];
      
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
  