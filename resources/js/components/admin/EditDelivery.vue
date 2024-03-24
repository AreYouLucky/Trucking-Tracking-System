<template>
    <v-app>
        <v-layout class="rounded rounded-md">
            <v-app-bar color="primary" class="pa-4" density="compact">
                <img src="../../../img/logo.png" width="90px">
                <h3 class="title">LARA's TRUCKING SERVICES</h3>
            </v-app-bar>
            <v-main class="mt-5 color">
                <v-card color="secondary" class="pa-5">
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="6" md="4">
                                <v-select item-value="customer_id" item-title="username" :items="customers"
                                    v-model="fields.customer_id" variant="solo" color="third" label="Select Customer"
                                    :rules="[rules.required]"
                                    :error-messages="errors.customer_id ? errors.customer_id[0] : ''"></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-select item-value="driver_id" item-title="username" :items="drivers"
                                    v-model="fields.driver_id" variant="solo" color="third" label="Select Driver"
                                    :rules="[rules.required]"
                                    :error-messages="errors.driver_id ? errors.driver_id[0] : ''"></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-select item-value="vehicle_id" item-title="name" :items="vehicles"
                                    v-model="fields.vehicle_id" variant="solo" color="third" label="Select Vehicle"
                                    :rules="[rules.required]"
                                    :error-messages="errors.vehicle_id ? errors.vehicle_id[0] : ''"></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" md="3">
                                <v-text-field v-model="fields.reciever" variant="solo" color="primary"
                                    label="Reciever's Full Name" required :rules="[rules.required]"
                                    :error-messages="errors.reciever ? errors.reciever[0] : ''"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="3">
                                <v-text-field v-model="fields.reciever_no" variant="solo" color="primary"
                                    label="Reciever's Contact" required :rules="[rules.required, rules.phone]"
                                    :error-messages="errors.reciever_no ? errors.reciever_no[0] : ''"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="3">
                                <v-text-field type="date" v-model="fields.date" variant="solo" color="primary"
                                    label="Pickup Date" required :rules="[rules.required]"
                                    :error-messages="errors.date ? errors.date[0] : ''">

                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="3">
                                <v-text-field v-model="fields.time" label="Pickup Time" variant="solo" color="primary"
                                    type="time" suffix="PST" :rules="[rules.required]"
                                    :error-messages="errors.time ? errors.time[0] : ''"></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="6" md="3">
                                <v-text-field v-model="fields.delivery_weight" variant="solo" color="primary"
                                    label="Delivery Weight" required :rules="[rules.required]"
                                    :error-messages="errors.delivery_weight ? errors.delivery_weight[0] : ''"
                                    suffix="KG">
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="5">
                                <v-text-field v-model="fields.delivery_info" variant="solo" color="primary"
                                    label="Delivery Information" required :rules="[rules.required]"
                                    :error-messages="errors.delivery_info ? errors.delivery_info[0] : ''">
                                </v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" sm="12" md="6">
                            <v-row class="row1">
                                <v-col cols="12" sm="6" md="3">
                                    <v-select item-value="provCode" item-title="provDesc" :items="from_provinces"
                                        v-model="fields.from_province" @update:modelValue="loadFromCities"
                                        variant="solo" color="primary" label="Province" required :error-messages="errors.from_province
                        ? errors.from_province[0]
                        : ''
                        " :rules="[rules.required]"></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="3">
                                    <v-select item-value="citymunCode" item-title="citymunDesc" :items="from_cities"
                                        v-model="fields.from_city" @update:modelValue="loadFromBarangays" variant="solo"
                                        color="primary" label="City" :error-messages="errors.from_city
                        ? errors.from_city[0]
                        : ''
                        " :rules="[rules.required]"></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="3">
                                    <v-select item-value="brgyCode" item-title="brgyDesc" :items="from_barangays"
                                        v-model="fields.from_barangay" variant="solo" color="primary" label="Barangay"
                                        :rules="[rules.required]" :error-messages="errors.from_barangay
                        ? errors.from_barangay[0]
                        : ''
                        "></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field v-model="fields.from_street" variant="solo" color="primary"
                                        label="Street" :rules="[rules.required]" :error-messages="errors.from_street
                        ? errors.from_street[0]
                        : ''
                        "></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">
                                    <v-text-field v-model="fields.fromLat" variant="solo" color="third" label="Latitude"
                                        :rules="[rules.required]" :error-messages="errors.fromLat
                        ? errors.fromLat[0]
                        : ''
                        "></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">
                                    <v-text-field v-model="fields.fromLng" variant="solo" color="third"
                                        label="Longitude" :rules="[rules.required]" :error-messages="errors.fromLng
                        ? errors.fromLng[0]
                        : ''
                        "></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">
                                    <v-btn prepend-icon="mdi-map-marker-plus" elevation="3" color="blue-darken-4"
                                        height="70%" @click="setPickup">Set Pickup</v-btn>
                                </v-col>
                                <v-col cols="12" sm="12" md="12">
                                    <v-card theme="light">
                                        <div id="map1" style="height: 60vh"></div>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-col>

                                    <v-col cols="12" sm="12" md="6">
                                        <v-row class="row2">
                                            <v-col cols="12" sm="6" md="3">
                                                <v-select item-value="provCode" item-title="provCode"
                                                    :items="to_provinces" v-model="fields.to_province"
                                                    @update:modelValue="loadToCities" variant="solo" color="primary"
                                                    label="Province" required
                                                    :error-messages="errors.to_province ? errors.to_province[0] : ''"
                                                    :rules="[rules.required]">
                                                </v-select>
                                            </v-col>

                                            <v-col cols="12" sm="6" md="3">
                                                <v-select item-value="citymunCode" item-title="citymunDesc"
                                                    :items="to_cities" v-model="fields.to_city"
                                                    @update:modelValue="loadToBarangays" variant="solo" color="primary"
                                                    label="City"
                                                    :error-messages="errors.to_city ? errors.to_city[0] : ''"
                                                    :rules="[rules.required]"></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="3">
                                                <v-select item-value="brgyCode" item-title="brgyDesc"
                                                    :items="to_barangays" v-model="fields.to_barangay" variant="solo"
                                                    color="primary" label="Barangay" :rules="[rules.required]"
                                                    :error-messages="errors.to_barangay ? errors.to_barangay[0] : ''"></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="6" md="3">
                                                <v-text-field v-model="fields.to_street" variant="solo" color="primary"
                                                    label="Street" :rules="[rules.required]"
                                                    :error-messages="errors.to_street ? errors.to_street[0] : ''"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4" md="4">
                                                <v-text-field v-model="fields.toLat" variant="solo" color="third"
                                                    label="Latitude" :rules="[rules.required]"
                                                    :error-messages="errors.toLat ? errors.toLat[0] : ''"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4" md="4">
                                                <v-text-field v-model="fields.toLng" variant="solo" color="third"
                                                    label="Longitude" :rules="[rules.required]"
                                                    :error-messages="errors.toLng ? errors.toLng[0] : ''"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4" md="4">
                                                <v-btn prepend-icon="mdi-map-marker-plus" elevation="3" color="success"
                                                    height="70%" @click="setDestination">Update Destination</v-btn>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-card theme="light">
                                                    <div id="map2" style="height: 60vh;"></div>
                                                </v-card>

                                            </v-col>
                                        </v-row>
                                    </v-col>

                                </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn @click="update" variant="outlined" size="large" prepend-icon="mdi-check-circle"
                            color="black">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-main>

            <v-dialog v-model="errorDialog" width="auto">
                <v-card color="primary">
                    <v-card-text>
                        {{ errors }}
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="primary" class="text-white" block variant="tonal" elevation="4"
                            @click="errorDialog = false">Okay!</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </v-app>
</template>

<script>
import { onMounted } from 'vue';
import markImage from './../../../img/mark.png'
import 'leaflet-geosearch/dist/geosearch.css';
import { GeoSearchControl, OpenStreetMapProvider } from 'leaflet-geosearch';
import { useTheme } from 'vuetify'



export default {
    data() {
        return {
            rules: {
                required: value => !!value || 'Required.',
                phone(value) {
                    if (/^(09\d{9}|\\+639\d{9})$/i.test(value)) return true

                    return 'Must be a valid phone number.'
                },
            },
            map1: null,
            map2: null,
            mark: null,
            mark2: null,
            fields: {},
            errors: {},
            customers: [],
            drivers: [],
            vehicles: [],
            click1Lat: null,
            click1Lng: null,
            click2Lat: null,
            click2Lng: null,
            confirmSave: false,
            theme: null,
            errorDialog: false,
            to_provinces: [],
            to_cities: [],
            to_barangays: [],
            from_provinces: [],
            from_cities: [],
            from_barangays: [],
        };
    },
    methods: {
        loadToProvinces: function () {
            axios.get("/load-provinces").then((res) => {
                this.to_provinces = res.data;
            });
        },
        loadToCities: function () {
            axios
                .get("/load-cities?prov=" + this.fields.to_province)
                .then((res) => {
                    this.to_cities = res.data;
                });
        },

        loadToBarangays: function () {
            axios
                .get(
                    "/load-barangays?prov=" +
                    this.fields.to_province +
                    "&city_code=" +
                    this.fields.to_city
                )
                .then((res) => {
                    this.to_barangays = res.data;
                });
        },
        loadFromProvinces: function () {
            axios.get("/load-provinces").then((res) => {
                this.from_provinces = res.data;
            });
        },
        loadFromCities: function () {
            axios
                .get("/load-cities?prov=" + this.fields.from_province)
                .then((res) => {
                    this.from_cities = res.data;
                });
        },

        loadFromBarangays: function () {
            axios
                .get(
                    "/load-barangays?prov=" +
                    this.fields.from_province +
                    "&city_code=" +
                    this.fields.from_city
                )
                .then((res) => {
                    this.from_barangays = res.data;
                });
        },
        loadDelivery() {
            axios.get('/get-delivery/' + this.data_id).then(
                res => {
                    this.fields = res.data;
                    this.fields.reciever = res.data.reciever_name;
                    this.fields.fromLat = res.data.from_lat;
                    this.fields.fromLng = res.data.from_long;
                    this.fields.toLat = res.data.to_lat;
                    this.fields.toLng = res.data.to_long;
                }
            )
        },
        loadCustomers() {
            axios.get('/load-customers').then(
                res => {
                    this.customers = res.data;
                }
            )
        },
        loadVehicles() {
            axios.get('/load-vehicles').then(
                res => {
                    this.vehicles = res.data;
                }
            )
        },
        loadDrivers() {
            axios.get('/load-drivers').then(
                res => {
                    this.drivers = res.data;
                }
            )
        },
        setupGeoSearch(map) {
            const provider = new OpenStreetMapProvider();
            const searchControl = new GeoSearchControl({
                provider,
                style: 'button',
                showMarker: true,
                showPopup: false,
                marker: {
                    icon: new L.Icon.Default(),
                    draggable: false,
                },
            });

            map.addControl(searchControl);
        },
        initializeMaps() {
            this.map1 = L.map('map1').setView([8.0642048, 123.731968], 12);
            this.map2 = L.map('map2').setView([8.0642048, 123.731968], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18,
                minZoom: 5,
            }).addTo(this.map1);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18,
                minZoom: 5,
            }).addTo(this.map2);

            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18,
                minZoom: 5,
            });

            var eri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 18,
                minZoom: 5,
            });
            var osm1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18,
                minZoom: 5,
            });

            var eri1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                maxZoom: 18,
                minZoom: 5,
            });
            var baseMaps = {
                "OpenStreetMap": osm,
                "WorldImagery": eri
            };
            var baseMaps1 = {
                "OpenStreetMap": osm1,
                "WorldImagery": eri1
            };
            var layerControl = L.control.layers(baseMaps).addTo(this.map1);
            var layerControl1 = L.control.layers(baseMaps1).addTo(this.map2);

            // Initialize GeoSearch for each map
            this.setupGeoSearch(this.map1);
            this.setupGeoSearch(this.map2);

            this.map1.on('click', (e) => this.onMapClick(e, this.map1, 'click1'));
            this.map2.on('click', (e) => this.onMapClick(e, this.map2, 'click2'));

        },
        onMapClick(e, map, clickType) {
            const { lat, lng } = e.latlng;
            var customIcon3 = L.icon({
                iconUrl: markImage,
                iconSize: [30, 50],
                iconAnchor: [15, 54],
                popupAnchor: [5, -38]
            });

            // Remove the previous marker if it exists
            if (this[clickType + 'Marker']) {
                this[clickType + 'Marker'].remove();
            }

            // Create and add the new marker to the map
            this[clickType + 'Marker'] = L.marker([lat, lng], { icon: customIcon3 }).addTo(map);

            // Update latitude and longitude values
            this[clickType + 'Lat'] = lat;
            this[clickType + 'Lng'] = lng;
        },
        setPickup() {
            this.fields.fromLat = this.click1Lat
            this.fields.fromLng = this.click1Lng
        },
        setDestination() {
            this.fields.toLat = this.click2Lat
            this.fields.toLng = this.click2Lng
        },
        initData() {
            this.loadCustomers();
            this.loadDrivers();
            this.loadVehicles();
        },
        refreshData() {
            this.loadCustomers();
            this.loadDrivers();
            this.loadVehicles();
            this.fields = {};
        },
        update() {
            axios.post('/update-delivery', this.fields).then(
                res => {
                    window.location = '/admin-dashboard';
                }
            ).catch(err => {
                this.errors = err.response.data.errors
            }

            )
        }
    },
    mounted() {
        const urlParts = window.location.href.split("/");
        this.data_id = urlParts[urlParts.length - 1];
        this.loadDelivery();
        this.initData();
        this.initializeMaps();
    },
    setup() {
        const { theme } = useTheme()
    },
};
</script>

<style scoped>
#map1,
#map2 {
    width: 100%;
}

.row1 {
    background-color: rgb(0, 121, 190);
}

.row2 {
    background-color: rgb(0, 91, 194);
}

* {
    color: black !important;
}

/* Customize the background color of the search field */
</style>
