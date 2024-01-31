<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" color="black">
            <v-card class="align-center justify center" height="100vh">
                <v-row class="justify-center"> 
                    <img src="../../../img/logo.png" width="200px">
                </v-row>
                <v-row>
                    <v-tabs v-model="tab" direction="vertical" color="primary" class="tabs"
            >
                    <v-tab value="ONE" class="tab justify-center">
                    <v-icon start>
                        mdi-view-dashboard
                    </v-icon>
                        Dashboard
                    </v-tab>
                    <v-tab value="TWO" class="tab justify-center">
                    <v-icon start>
                        mdi-face-man-shimmer
                    </v-icon>
                        customers
                    </v-tab>
                    <v-tab value="THREE" class="tab justify-center">
                    <v-icon start>
                        mdi-steering
                    </v-icon>
                        Drivers
                    </v-tab>
                    <v-tab value="FOUR" class="tab justify-center">
                    <v-icon start>
                        mdi-truck-fast
                    </v-icon>
                        Vehicles
                    </v-tab>
                    <v-tab value="FIVE" class="tab justify-center">
                    <v-icon start>
                        mdi-map-marker-plus
                    </v-icon>
                        Add Delivery
                    </v-tab>
                    <v-tab value="SIX" class="tab justify-center">
                    <v-icon start>
                        mdi-archive-marker
                    </v-icon>
                        Deliveries
                    </v-tab>
                </v-tabs>
                    
                </v-row>
                 
           
            </v-card>
            
        </v-navigation-drawer>

    <v-app-bar color="primary">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <h3 class="title">Admin</h3>
                <v-spacer></v-spacer>         
                <v-btn
                class="logout"  
                @click="dialog=true" 
                prepend-icon="mdi-logout"
                elevation = "2"
                > Sign Out</v-btn>
    </v-app-bar>

    <v-main>
            
            <v-window v-model="tab">
                <v-window-item value="ONE">
                    <v-card class="pa-4" color="blue" min-height="90vh">
                        <dashboard-fields v-if="tab === 'ONE'" ref="DashboardFields"></dashboard-fields>
                    </v-card>                      
                </v-window-item>

                <v-window-item value="TWO">
                    <v-card color="blue" class="pa-4" min-height="90vh">
                        <customer-fields v-if="tab === 'TWO'" ref="CustomerFields"></customer-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="THREE">
                    <v-card color="blue" class="pa-4" min-height="90vh">
                        <driver-fields v-if="tab === 'THREE'" ref="DriverFields"></driver-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="FOUR">
                    <v-card color="blue" class="pa-4" min-height="90vh">
                        <vehicle-fields v-if="tab === 'FOUR'" ref="VehicleFields"></vehicle-fields>
                    </v-card>
                </v-window-item>
                <v-window-item value="FIVE">
                    <v-card color="blue" class="pa-4" min-height="90vh">
                        <set-pickup v-if="tab === 'FIVE'" ref="SetPickup"></set-pickup>
                    </v-card>
                </v-window-item>
                <v-window-item value="SIX">
                    <v-card color="blue" class="pa-4" min-height="90vh">
                        <delivery-fields v-if="tab === 'SIX'" ref="DeliveryFields"></delivery-fields>
                    </v-card>
                </v-window-item>
            </v-window>
    </v-main>
            


        <v-dialog
            v-model="dialog"
            width="auto"
            >
            <v-card color="secondary">
                <v-card-text>
                    Are you you want to logout?
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" class="logout"  @click="dialog = false">Close</v-btn>
                    <v-btn color="primary"  class="logout" href="/logout">confirm</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>


    </v-app>
  </template>
<script>

export default{
   data(){
       return {
        tab: null,
        dialog: false,
        drawer :'',
        user: []   
       };
   },
   methods: {
    getUser(){
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
                case 'FIVE':
                this.$nextTick(() => {
                this.$refs.SetPickup.initData();
                }); // Assuming initData is the initialization method
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
    mounted(){
        this.getUser();
    }
}
</script>
<style scoped>
    .bottom-nav{
        background-color: #E6A4B4 ;
    }
    .logout{
        background-color: #1a1a1a;
    }
    .tab{
        background-color: #1a1a1a;
    }
    .tabs{
        width: 100%;
        margin-top: 2vh;
    }
    .text-small{
        font-size: 11px;
    }


</style>