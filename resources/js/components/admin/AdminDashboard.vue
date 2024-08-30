<template>
    <v-app class="bg-transparent">
        <v-navigation-drawer v-model="drawer" :rail="rail" floating @click="rail = false" elevation="8" min-width="70" >
            <v-list-item nav>
                    <v-img :src="`/storage/logos/logo.png`" height="150" contain></v-img>
                    <template v-slot:append>
                        <v-btn icon @click.stop="rail = !rail" variant="text">
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                    </template>
                </v-list-item>
                <v-divider class="mb-3"></v-divider>

                <v-list>
                    <v-list density="comfortable" nav>
                        <v-list-item @click="tab = 'ONE'"
                            :class="['pa-2', 'text-caption', tab === 'ONE' ? 'bg-light-blue-darken-3' : 'bg-dark']"
                            prepend-icon="mdi-view-dashboard" rounded="xl">Dashboard</v-list-item>
                    </v-list>
                    <v-list density="comfortable" nav>
                        <v-list-item @click="tab = 'TWO'"
                            :class="['pa-2', 'text-caption', tab === 'TWO' ? 'bg-light-blue-darken-3' : 'bg-dark']"
                            prepend-icon="mdi-face-man-shimmer" rounded="xl">Customers</v-list-item>
                    </v-list>
                    <v-list-group>
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props" prepend-icon="mdi-account-group"
                                class="text-caption">Utilities</v-list-item>
                        </template>

                        <v-list-item @click="tab = 'THREE'" prepend-icon="mdi-steering" rounded="xl"
                            :class="['pa-2', 'text-caption', tab === 'THREE' ? 'bg-light-blue-darken-3' : 'bg-dark']">Drivers</v-list-item>
                        <v-list-item @click="tab = 'FOUR'" prepend-icon="mdi-truck-fast" rounded="xl"
                            :class="['pa-2', 'text-caption', tab === 'FOUR' ? 'bg-light-blue-darken-3' : 'bg-dark']">Vehicles</v-list-item>
                    </v-list-group>

                    <v-list-group>
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props" prepend-icon="mdi-list-box"
                                class="text-caption">Delivery</v-list-item>
                        </template>

                        <v-list-item prepend-icon="mdi-map-marker-plus" rounded="xl" href="/set-pickup"
                            :class="['pa-2', 'text-caption', tab === 'FIVE' ? 'bg-light-blue-darken-3' : 'bg-dark']">Add Delivery</v-list-item>
                        <v-list-item @click="tab = 'SIX'" prepend-icon="mdi-archive-marker" rounded="xl"
                            :class="['pa-2', 'text-caption', tab === 'SIX' ? 'bg-light-blue-darken-3' : 'bg-dark']">Deliveries</v-list-item>
                    </v-list-group>
                </v-list>
        </v-navigation-drawer>

        <v-app-bar color="transparent">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <h3 class="title">Admin</h3>
            <v-spacer></v-spacer>
            <v-btn class="logout bg-blue" @click="dialog = true" prepend-icon="mdi-logout" elevation="2" rounded="xl">
                Sign Out</v-btn>
        </v-app-bar>

        <v-main>
            <v-window v-model="tab">
                <v-window-item value="ONE">
                    <v-card class="pa-4" color="transparent" min-height="90vh">
                        <dashboard-fields v-if="tab === 'ONE'" ref="DashboardFields"></dashboard-fields>
                    </v-card>
                </v-window-item>

                <v-window-item value="TWO">
                    <v-card color="transparent" class="pa-4" min-height="90vh">
                        <customer-fields v-if="tab === 'TWO'" ref="CustomerFields"></customer-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="THREE">
                    <v-card color="transparent" class="pa-4" min-height="90vh">
                        <driver-fields v-if="tab === 'THREE'" ref="DriverFields"></driver-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="FOUR">
                    <v-card color="transparent" class="pa-4" min-height="90vh">
                        <vehicle-fields v-if="tab === 'FOUR'" ref="VehicleFields"></vehicle-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="SIX">
                    <v-card color="transparent" class="pa-4" min-height="90vh">
                        <delivery-fields v-if="tab === 'SIX'" ref="DeliveryFields"></delivery-fields>
                    </v-card>
                </v-window-item>
            </v-window>
        </v-main>



        <v-dialog v-model="dialog" width="auto">
            <v-card>
                <v-card-text>
                    Are you you want to logout?
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="white" variant="outlined" class="logout" @click="dialog = false">Close</v-btn>
                    <v-btn color="red" variant="outlined" class="logout" href="/logout">confirm</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>


    </v-app>
</template>
<script>

export default {
    data() {
        return {
            tab: null,
            drawer: true,
            rail: false,
            order: 0,
            dialog: false,
            user: []
        };
    },
    methods: {
        getUser() {
            axios.get('/load-driver-profile').then(
                res => {
                    this.user = res.data;
                })
        },
        initializeComponent(tab) {
            // Access the component instance using $refs and call the initialization method
            switch (tab) {
                case 'ONE':
                    this.$nextTick(() => {
                        this.$refs.DashboardFields.initData();
                    }); // Assuming initData is the initialization method
                    break;
                case 'TWO':
                    this.$nextTick(() => {
                        this.$refs.CustomerFields.initData();
                    }); // Assuming initData is the initialization method
                    break;
                case 'THREE':
                    this.$nextTick(() => {
                        this.$refs.DriverFields.initData();
                    });// Assuming initData is the initialization method
                    break;
                case 'FOUR':
                    this.$nextTick(() => {
                        this.$refs.VehicleFields.initData();
                    }); // Assuming initData is the initialization method
                    break;
                    // case 'FIVE':
                    // this.$nextTick(() => {
                    // this.$refs.SetPickup.initData();
                    // }); // Assuming initData is the initialization method
                    break;
                case 'SIX':
                    this.$nextTick(() => {
                        this.$refs.DeliveryFields.initData();
                    });// Assuming initData is the initialization method
                    break;
                default:
                    break;
            }
        }

    },
    watch: {
        tab(newTab, oldTab) {
            this.initializeComponent(newTab);
        }
    },
    mounted() {
        this.getUser();
    }
}
</script>
<style scoped>
.bottom-nav {
    background-color: #E6A4B4;
}

.logout {
    background-color: #1a1a1a;
}

.tabs {
    width: 100%;
    padding: 10px;
    margin-top: 2vh;
}

.text-small {
    font-size: 11px;
}
</style>
