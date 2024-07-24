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
      this.map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: parseFloat(this.location[0].latitude), lng: parseFloat(this.location[0].longitude) },
        zoom: 13,
      });

      for (var item of this.location) {
        new google.maps.Circle({
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 1,
          fillColor: '#FF0000',
          fillOpacity: 0.35,
          map: this.map,
          center: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
          radius: 10,
        });
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

* {
  color: black;
}
</style>