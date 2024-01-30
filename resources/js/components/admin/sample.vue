<template>
    <div>
      <div id="map1" style="height: 400px;"></div>
      <div id="map2" style="height: 400px;"></div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import L from 'leaflet';
  
  export default {
    data() {
      return {
        click1Lat: null,
        click1Lng: null,
        click2Lat: null,
        click2Lng: null,
        map1: null,
        map2: null
      };
    },
    mounted() {
      this.initializeMaps();
    },
    methods: {
      initializeMaps() {
        this.map1 = L.map('map1').setView([51.505, -0.09], 13);
        this.map2 = L.map('map2').setView([40.7128, -74.0060], 13);
  
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap'
        }).addTo(this.map1);
  
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap'
        }).addTo(this.map2);
  
        this.map1.on('click', (e) => this.onMapClick(e, this.map1, 'click1'));
        this.map2.on('click', (e) => this.onMapClick(e, this.map2, 'click2'));
      },
      onMapClick(e, map, clickType) {
        const { lat, lng } = e.latlng;
        this[clickType + 'Lat'] = lat;
        this[clickType + 'Lng'] = lng;
        L.marker([lat, lng]).addTo(map);
      }
    }
  };
  </script>
  
  <style scoped>
  #map1, #map2 {
    width: 100%;
  }
  </style>
  