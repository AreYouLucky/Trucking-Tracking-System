<template>
  <v-row class="pa-5" style="background-color: black;">
    <v-col cols="12" md="12">
      <v-row class="d-flex justify-center">
        <v-col cols="12" md="6">
          <v-card>
            <v-card-title>
              <v-sheet class="d-flex justify-center" color="primary">
                <b class="text-white">Driver's Information</b>
              </v-sheet>
            </v-card-title>
            <v-card-text>
              <v-sheet color="secondary">
                <v-table theme="light">
                  <thead>
                    <tr>
                      <th>Driver's Name:</th>
                      <th>{{ driver.fname }} {{ driver.lname }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>Contact No:</th>
                      <td>{{ driver.contact_no }}</td>
                    </tr>
                    <tr>
                      <th>License ID:</th>
                      <td>{{ driver.license_id }}</td>
                    </tr>
                  </tbody>
                </v-table>
              </v-sheet>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="5">
          <v-card>
            <v-card-title>
              <v-sheet class="d-flex justify-center" color="fourth">
                <b class="text-white">Vehicle Information</b>
              </v-sheet>
            </v-card-title>
            <v-card-text>
              <v-sheet color="secondary">
                <v-table theme="light">
                  <thead>
                    <tr>
                      <th>Vehicle Model:</th>
                      <th>{{ vehicle.name }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>Vehicle Body:</th>
                      <td>{{ vehicle.vehicle_type }}</td>
                    </tr>
                    <tr>
                      <th>Plate No:</th>
                      <td>{{ vehicle.plate_no }}</td>
                    </tr>
                  </tbody>
                </v-table>
              </v-sheet>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="12">
      <div>
        <div id="map" style="height: 500px; width: 100%;"></div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import axios from 'axios';

export default {
  name: 'GoogleMap',
  data() {
    return {
      driver_id: null,
      location: {},
      map: null,
      directionsService: null,
      directionsRenderer: null,
      driver: {},
      vehicle: {},
      driverMarker: null,
    };
  },
  methods: {
    getDriver() {
      axios.get('/driver/' + this.location.driver_id).then(res => {
        this.driver = res.data;
      });
    },
    getVehicle() {
      axios.get('/vehicle/' + this.location.vehicle_id).then(res => {
        this.vehicle = res.data;
      });
    },
    getLocation() {
      return new Promise((resolve, reject) => {
        axios.get('/driver-location-data/' + this.driver_id).then(res => {
          this.location = res.data;
          this.getDriver();
          this.getVehicle();
          resolve();
        }).catch(error => {
          reject(error);
        });
      });
    },
    loadGoogleMapsScript() {
      return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src*="maps.googleapis.com"]`)) {
          resolve();
          return;
        }
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-kQ7xyVmRuUL3Dk-UiByLAny4HVE46I&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
      });
    },
    initMap() {
      const pickupLat = parseFloat(this.location.from_lat);
      const pickupLng = parseFloat(this.location.from_long);
      const deliveryLat = parseFloat(this.location.to_lat);
      const deliveryLng = parseFloat(this.location.to_long);

      if (isNaN(pickupLat) || isNaN(pickupLng) || isNaN(deliveryLat) || isNaN(deliveryLng)) {
        console.error('Invalid coordinates:', {
          pickupLat,
          pickupLng,
          deliveryLat,
          deliveryLng
        });
        return; 
      }

      const pickupLocation = { lat: pickupLat, lng: pickupLng };
      const deliveryLocation = { lat: deliveryLat, lng: deliveryLng };

      this.map = new google.maps.Map(document.getElementById('map'), {
        center: pickupLocation,
        zoom: 7,
      });

      this.directionsService = new google.maps.DirectionsService();
      this.directionsRenderer = new google.maps.DirectionsRenderer();
      this.directionsRenderer.setMap(this.map);

      this.calculateAndDisplayRoute(pickupLocation, deliveryLocation);
    },
    calculateAndDisplayRoute(pickupLocation, deliveryLocation) {
      this.directionsService.route(
        {
          origin: pickupLocation,
          destination: deliveryLocation,
          travelMode: 'DRIVING',
        },
        (response, status) => {
          if (status === 'OK') {
            this.directionsRenderer.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        }
      );
    },
    updateLocation() {
      const updateDriverLocation = () => {
        axios.get('/driver-location-data/' + this.driver_id)
          .then(res => {
            this.location = res.data;
            if (this.driverMarker) {
              this.driverMarker.setMap(null);
            }

            const driverLat = parseFloat(this.location.driver_lat);
            const driverLong = parseFloat(this.location.driver_long);

            this.driverMarker = new google.maps.Marker({
              position: { lat: driverLat, lng: this.location.driver_long },
              map: this.map,
              title: 'Driver Location',
            });
            if (this.location.is_delivered < 3) {
              setTimeout(updateDriverLocation, 1000);
            }
          })
          .catch(error => {
            console.error('Error fetching driver location:', error);
          });
      };
      updateDriverLocation();
    }
  },
  mounted() {
    const urlParts = window.location.href.split("/");
    this.driver_id = urlParts[urlParts.length - 1];
    this.getLocation().then(() => {
      this.loadGoogleMapsScript().then(() => {
        this.initMap();
        this.updateLocation();
      }).catch(error => {
        console.error('Error loading Google Maps script:', error);
      });
    }).catch(error => {
      console.error('Error fetching location data:', error);
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
