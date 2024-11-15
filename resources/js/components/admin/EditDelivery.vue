<template>
    <v-app>
        <v-container>
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
                                        v-model="fields.customer_id" variant="solo" color="third"
                                        label="Select Customer" :rules="[rules.required]"
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
                                    <v-text-field v-model="fields.time" label="Pickup Time" variant="solo"
                                        color="primary" type="time" suffix="PST" :rules="[rules.required]"
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
                                            <v-select item-value="provCode" item-title="provDesc"
                                                :items="from_provinces" v-model="fields.from_province"
                                                @update:modelValue="loadFromCities" variant="solo" color="primary"
                                                label="Province" required :error-messages="errors.from_province
                                                    ? errors.from_province[0]
                                                    : ''
                                                    " :rules="[rules.required]"></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="3">
                                            <v-select item-value="citymunCode" item-title="citymunDesc"
                                                :items="from_cities" v-model="fields.from_city"
                                                @update:modelValue="loadFromBarangays" variant="solo" color="primary"
                                                label="City" :error-messages="errors.from_city
                                                    ? errors.from_city[0]
                                                    : ''
                                                    " :rules="[rules.required]"></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="3">
                                            <v-select item-value="brgyCode" item-title="brgyDesc"
                                                :items="from_barangays" v-model="fields.from_barangay" variant="solo"
                                                color="primary" label="Barangay" :rules="[rules.required]"
                                                :error-messages="errors.from_barangay
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
                                            <v-text-field v-model="fields.fromLat" variant="solo" color="third"
                                                label="Latitude" :rules="[rules.required]" :error-messages="errors.fromLat
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
                                            <v-btn prepend-icon="mdi-map-marker-plus" elevation="3"
                                                color="blue-darken-4" height="70%" @click="setPickup">Set Pickup</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>

                                <v-col cols="12" sm="12" md="6">
                                    <v-row class="row2">
                                        <v-col cols="12" sm="6" md="3">
                                            <v-select item-value="provCode" item-title="provCode" :items="to_provinces"
                                                v-model="fields.to_province" @update:modelValue="loadToCities"
                                                variant="solo" color="primary" label="Province" required
                                                :error-messages="errors.to_province ? errors.to_province[0] : ''"
                                                :rules="[rules.required]">
                                            </v-select>
                                        </v-col>

                                        <v-col cols="12" sm="6" md="3">
                                            <v-select item-value="citymunCode" item-title="citymunDesc"
                                                :items="to_cities" v-model="fields.to_city"
                                                @update:modelValue="loadToBarangays" variant="solo" color="primary"
                                                label="City" :error-messages="errors.to_city ? errors.to_city[0] : ''"
                                                :rules="[rules.required]"></v-select>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="3">
                                            <v-select item-value="brgyCode" item-title="brgyDesc" :items="to_barangays"
                                                v-model="fields.to_barangay" variant="solo" color="primary"
                                                label="Barangay" :rules="[rules.required]"
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
                                    </v-row>
                                </v-col>
                                <v-col cols="12" sm="12" md="12">
                                    <v-card theme="light">
                                        <input type="text" v-model="search" @keypress.enter="searchLocation"
                                            placeholder="Search location">
                                        <div id="map" style="height: 80vh; width: 100%;"></div>
                                    </v-card>
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
                        <v-card-text>{{ errors }}</v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" class="text-white" block variant="tonal" elevation="4"
                                @click="errorDialog = false">Okay!</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
import { onMounted, ref } from 'vue';
import axios from 'axios';

export default {
    data() {
        return {
            rules: {
                required: value => !!value || 'Required.',
                phone(value) {
                    if (/^(09\d{9}|\\+639\d{9})$/i.test(value)) return true;
                    return 'Must be a valid phone number.';
                },
            },
            map: null,
            marker: null,
            fields: {
                fromLat: null,
                fromLng: null,
                toLat: null,
                toLng: null,
            },
            errors: {},
            customers: [],
            drivers: [],
            vehicles: [],
            clickLat: null,
            clickLng: null,
            errorDialog: false,
            search: '',
            to_provinces: [],
            to_barangays: [],
            to_cities: [],
            from_provinces: [],
            from_barangays: [],
            from_cities: [],
        };
    },
    methods: {
        loadToProvinces() {
            axios.get("/load-provinces").then(res => {
                this.to_provinces = res.data;
            });
        },
        loadToCities() {
            axios.get("/load-cities?prov=" + this.fields.to_province).then(res => {
                this.to_cities = res.data;
            });
        },
        loadToBarangays() {
            axios.get("/load-barangays?prov=" + this.fields.to_province + "&city_code=" + this.fields.to_city).then(res => {
                this.to_barangays = res.data;
            });
        },
        loadFromProvinces() {
            axios.get("/load-provinces").then(res => {
                this.from_provinces = res.data;
            });
        },
        loadFromCities() {
            axios.get("/load-cities?prov=" + this.fields.from_province).then(res => {
                this.from_cities = res.data;
            });
        },
        loadFromBarangays() {
            axios.get("/load-barangays?prov=" + this.fields.from_province + "&city_code=" + this.fields.from_city).then(res => {
                this.from_barangays = res.data;
            });
        },
        loadDelivery() {
            axios.get('/get-delivery/' + this.data_id).then(res => {
                this.fields = res.data;
                this.fields.reciever = res.data.reciever_name;
                this.fields.fromLat = res.data.from_lat;
                this.fields.fromLng = res.data.from_long;
                this.fields.toLat = res.data.to_lat;
                this.fields.toLng = res.data.to_long;

                this.loadFromProvinces();
                this.loadFromBarangays();
                this.loadFromCities();
                this.loadToProvinces();
                this.loadToBarangays();
                this.loadToCities();
                

            });
        },
        loadCustomers() {
            axios.get('/load-customers').then(res => {
                this.customers = res.data;
            });
        },
        loadVehicles() {
            axios.get('/load-vehicles').then(res => {
                this.vehicles = res.data;
            });
        },
        loadDrivers() {
            axios.get('/load-drivers').then(res => {
                this.drivers = res.data;
            });
        },
        initMap() {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBd-kQ7xyVmRuUL3Dk-UiByLAny4HVE46I&libraries=places`;
            script.async = true;
            script.defer = true;
            script.onload = this.loadMap;
            document.head.appendChild(script);
        },
        loadMap() {
            this.map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 8.0727842, lng: 123.7416101},
                zoom: 8,
            });

            this.map.addListener('click', (event) => {
                this.addMarker(event.latLng);
                this.logCoordinates(event.latLng);
            });
        },
        addMarker(location) {
            if (this.marker) {
                this.marker.setMap(null);
            }
            this.marker = new google.maps.Marker({
                position: location,
                map: this.map,
            });
        },
        searchLocation() {
            const service = new google.maps.places.PlacesService(this.map);
            service.textSearch({ query: this.search }, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    const location = results[0].geometry.location;
                    this.map.setCenter(location);
                    this.addMarker(location);
                }
            });
        },
        logCoordinates(location) {
            this.clickLat = location.lat();
            this.clickLng = location.lng();
            console.log('Latitude:', this.clickLat);
            console.log('Longitude:', this.clickLng);
        },
        setPickup() {
            this.fields.fromLat = this.clickLat;
            this.fields.fromLng = this.clickLng;
        },
        setDestination() {
            this.fields.toLat = this.clickLat;
            this.fields.toLng = this.clickLng;
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
            axios.post('/update-delivery', this.fields).then(res => {
                window.location = '/admin-dashboard';
            }).catch(err => {
                this.errors = err.response.data.errors;
                this.errorDialog = true;
            });
        }
    },
    mounted() {
        const urlParts = window.location.href.split("/");
        this.data_id = urlParts[urlParts.length - 1];
        this.loadDelivery();
        this.initData();
        this.initMap();
    }
};
</script>

<style scoped>
#map {
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
</style>