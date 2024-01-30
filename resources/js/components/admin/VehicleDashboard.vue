<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" color="black">
            <v-card class="align-center justify center" height="100vh">
                <v-row class="justify-center my-2"> 
                    <img src="../../../img/logo.png" width="200px">

                </v-row>
                <v-row class="justify-center">
                    <v-btn color="black"  prepend-icon="mdi-view-dashboard" block class="logout" href="/admin-dashboard">Dashboard</v-btn>
                    <v-btn color="black"  prepend-icon="mdi-face-man-shimmer" block class="logout" href="/customers-dashboard">Customers</v-btn>
                    <v-btn color="black"  prepend-icon="mdi-steering" block class="logout" href="/drivers-dashboard">Drivers</v-btn>    
                    <v-btn color="grey-darken-3"  prepend-icon="mdi-truck-fast" block class="logout" href="/vehicles-dashboard">Vehicles</v-btn>    
                    <v-btn color="black"  prepend-icon="mdi-map-marker-plus" block class="logout" href="/pickups-dashboard">Setup Location</v-btn> 
                    <v-btn color="black"  prepend-icon="mdi-archive-marker" block class="logout" href="/deliveries-dashboard">Deliveries</v-btn>            
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
        <v-card class="pa-4" color="blue" min-height="90vh">
            <vehicle-fields></vehicle-fields>
        </v-card>                                   
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
           
       };
   },
   methods: {
      
    },
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


</style>