<template>
    <v-app class="bg-transparent" color="transparent">
        <v-navigation-drawer v-model="drawer" color="black">
            <v-card class="align-center justify center" height="100vh">
                <v-row class="justify-center">
                    <img src="../../../img/logo.png" width="200px">
                </v-row>
                <v-row class="justify-center">
                    <h1>{{ user.lname }},{{ user.fname }} <br> {{ user.mname }} </h1>
                </v-row>
                <v-row class="justify-center text-small">
                    {{ user.license_id }} | {{ user.contact_no }}
                </v-row>
                <v-row> >
                    <v-btn value="ONE" class="tabselected justify-center">
                        <v-icon start>
                            mdi-map-marker-plus
                        </v-icon>
                        CURRENT DELIVERY
                    </v-btn>
                    <v-btn value="TWO" class="tab justify-center" href="/driver-history">
                        <v-icon start>
                            mdi-credit-card-marker
                        </v-icon>
                        DELIVERY HISTORY
                    </v-btn>
                </v-row>


            </v-card>

        </v-navigation-drawer>

        <v-app-bar color="transparent">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <h3 class="title">Driver</h3>
            <v-spacer></v-spacer>
            <v-btn class="logout" @click="dialog = true" prepend-icon="mdi-logout" variant="flat" color="white" rounded="xl" elevation="2"> Sign Out</v-btn>
        </v-app-bar>

        <v-main>
            <v-card class="pa-4" color="transparent" min-height="90vh">
                <current-delivery></current-delivery>
            </v-card>
        </v-main>



        <v-dialog v-model="dialog" width="auto">
            <v-card color="secondary">
                <v-card-text>
                    Are you you want to logout?
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" class="logout" @click="dialog = false">Close</v-btn>
                    <v-btn color="primary" class="logout" href="/logout">confirm</v-btn>
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
            dialog: false,
            drawer: '',
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


    },
    mounted() {
        this.getUser();
    },
}
</script>
<style scoped>
.bottom-nav {
    background-color: #E6A4B4;
}

.logout {
    background-color: #1a1a1a;
}

.tab {
    background-color: #1a1a1a;
    width: 100%;
}

.tabselected {
    background-color: #252525;
    width: 100%;
    color: aqua;
}

.tabs {
    width: 100%;
    margin-top: 2vh;
}

.text-small {
    font-size: 12px;
}
</style>