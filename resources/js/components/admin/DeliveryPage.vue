<template>
    <div>
      <div id="map1" class="map-container"></div>
    </div>
  </template>
  
  <script>
 import { ref } from 'vue';
 import { faCube, faFlag, faTruckFast } from "@fortawesome/free-solid-svg-icons";

export default {
  data() {
    return {
      data_id: null,
      location: [],
      map: null,
    };
  },
  methods: {
    getLocation() {
      return new Promise((resolve, reject) => {
        axios.get('/delivery-location/' + this.data_id).then(
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
      const mapCenter = {
        lat: parseFloat(this.location[0].from_lat),
        lng: parseFloat(this.location[0].from_long)
      };
      this.map = new google.maps.Map(document.getElementById("map1"), {
        center: mapCenter,
        zoom: 13,
      });

      // const pickupMarker = new google.maps.Marker({
      //   position: { lat: parseFloat(this.location[0].from_lat), lng: parseFloat(this.location[0].from_long) },
      //   map: this.map,
      //   title: 'Pickup Location Here!'
      // });

      // const deliveryMarker = new google.maps.Marker({
      //   position: { lat: parseFloat(this.location[0].to_lat), lng: parseFloat(this.location[0].to_long) },
      //   map: this.map,
      //   title: 'Delivery Location Here!'
      // });

      const directionsService = new google.maps.DirectionsService();
      const directionsRenderer = new google.maps.DirectionsRenderer({ map: this.map,  suppressMarkers: true, });

      directionsService.route(
        {
          origin: { lat: parseFloat(this.location[0].from_lat), lng: parseFloat(this.location[0].from_long) },
          destination: { lat: parseFloat(this.location[0].to_lat), lng: parseFloat(this.location[0].to_long) },
          travelMode: google.maps.TravelMode.WALKING,
          optimizeWaypoints:true,
          provideRouteAlternatives:true,
        },
        (response, status) => {
          if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(response);
          } else {
            console.error('Directions request failed due to ' + status);
          }
        }
      );

      new google.maps.Marker({
        position: { lat: parseFloat(this.location[0].from_lat), lng: parseFloat(this.location[0].from_long) },
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
        position: { lat: parseFloat(this.location[0].driver_lat), lng: parseFloat(this.location[0].driver_long) },
        map: this.map,
        title: 'Driver Location',
        label: 'Driver',
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

      new google.maps.Marker({
        position: { lat: parseFloat(this.location[0].to_lat), lng: parseFloat(this.location[0].to_long) },
        map:this.map,
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
  },
  mounted() {
    const urlParts = window.location.href.split("/");
    this.data_id = urlParts[urlParts.length - 1];

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
  