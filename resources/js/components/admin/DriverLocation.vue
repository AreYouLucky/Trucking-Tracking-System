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
                      <th>
                        Driver's Name:
                      </th>
                      <th>
                        {{ driver.fname }} {{ driver.lname }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>
                        Contact No:
                      </th>
                      <td>
                        {{ driver.contact_no }}
                      </td>
                    </tr>
                    <tr>
                      <th>
                        License ID:
                      </th>
                      <td>
                        {{ driver.license_id }}
                      </td>
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
                      <th>
                        Vehicle Model:
                      </th>
                      <th>
                        {{ vehicle.name }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>
                        Vehicle Body:
                      </th>
                      <td>
                        {{ vehicle.vehicle_type }}
                      </td>
                    </tr>
                    <tr>
                      <th>
                        Plate No:
                      </th>
                      <td>
                        {{ vehicle.plate_no }}
                      </td>
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
        <div id="map1" class="map-container"></div>
      </div>
    </v-col>

  </v-row>

</template>

<script>
import { ref } from 'vue';
// import L from 'leaflet';
// import 'leaflet-routing-machine';
import axios from 'axios';
import boxImage from './../../../img/box.png'
import homeImage from './../../../img/home.png'
import markImage from './../../../img/truck.png'


export default {
  data() {
    return {
      driver_id: null,
      location: [],
      map: null,
      routingControl: null,
      driver: {},
      vehicle: {}
    };
  },
  methods: {
    getDriver() {
      axios.get('/driver/' + this.location.driver_id).then(
        res => {
          this.driver = res.data
        }
      )
    },
    getVehicle() {
      axios.get('/vehicle/' + this.location.vehicle_id).then(
        res => {
          this.vehicle = res.data
        }
      )
    },
    getLocation() {
      return new Promise((resolve, reject) => {
        axios.get('/driver-location-data/' + this.driver_id).then(
          res => {
            this.location = res.data;
            this.getDriver()
            this.getVehicle()
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

      var customIcon = L.icon({
        iconUrl: boxImage,
        iconSize: [50, 50],
        iconAnchor: [26, 25],
        popupAnchor: [5, -38]
      });
      var customIcon1 = L.icon({
        iconUrl: homeImage,
        iconSize: [50, 50],
        iconAnchor: [60, 40],
        popupAnchor: [-10, -20]
      });

      var mark1 = L.marker([this.location.from_lat, this.location.from_long], { icon: customIcon }).addTo(this.map)
        .bindPopup('Pickup Location Here!').openPopup();

      var mark2 = L.marker([this.location.to_lat, this.location.to_long], { icon: customIcon1 }).addTo(this.map)
        .bindPopup('Delivery Location Here!').openPopup();

      if (this.routingControl == null) {
        this.routingControl = L.Routing.control({
          waypoints: [
            L.latLng(this.location.to_lat, this.location.to_long),
            L.latLng(this.location.from_lat, this.location.from_long)
          ],
          router: L.Routing.graphHopper('7652a8a1-3fa4-4e32-be0d-82c191c3538a', {
            vehicle: 'car'
          }),
          numberOfRoutes: 3, // Set to the desired number of alternative routes
          lineOptions: {
            styles: [
              { color: 'blue', opacity: 0.5, weight: 9 },
              { color: 'white', opacity: 0.8, weight: 6 },
              { color: 'red', opacity: 1, weight: 2 }
            ]
          }
        }).addTo(this.map);
      }

    },
    updateLocation() {
      const updateDriverLocation = () => {
        axios.get('/driver-location-data/' + this.driver_id)
          .then(res => {
            this.location = res.data;
            var customIcon3 = L.icon({
              iconUrl: markImage,
              iconSize: [70, 40],
              iconAnchor: [15, 54],
              popupAnchor: [5, -58]
            });
            if (this.marker) {
              this.map.removeLayer(this.marker);
            }
            this.marker = L.marker([this.location.driver_lat, this.location.driver_long], { icon: customIcon3 }).addTo(this.map)
              .bindPopup('Driver Location Here!').openPopup();

            if (this.location.is_delivered < 3) {
              setTimeout(updateDriverLocation, 3000); // 3 seconds delay
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
      this.initializeMap();
    }).catch(error => {
      console.error('Error fetching location:', error);
    });
    this.updateLocation();
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