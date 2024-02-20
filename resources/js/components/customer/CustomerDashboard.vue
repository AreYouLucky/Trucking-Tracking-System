<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" color="black">
            <v-card class="align-center justify center" height="100vh">
                <v-row class="justify-center"> 
                    <img src="../../../img/logo.png" width="200px">
                </v-row>
                <v-row class="justify-center">
                    <h1>{{ user.lname }} {{ user.fname }} {{ user.mname }}</h1>
                </v-row>
                <v-row class="justify-center text-small">
                    {{ user.email }} | {{ user.contact_no }}
                </v-row>
                <v-row>
                    <v-tabs v-model="tab" direction="vertical" color="primary" class="tabs"
            >
                    <v-tab value="ONE" class="tab justify-center">
                    <v-icon start>
                        mdi-hand-coin
                    </v-icon>
                        Dashboard
                    </v-tab>
                </v-tabs>
                    
                </v-row>
                 
           
            </v-card>
            
        </v-navigation-drawer>

    <v-app-bar color="primary">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <h3 class="title">Customer</h3>
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
                        <customer-deliveries></customer-deliveries>
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
        axios.get('/load-customer-profile').then(
            res => {
              this.user = res.data;
            })
        },
    },
    mounted(){
        this.getUser();
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
    .tabs{
        width: 100%;
        margin-top: 2vh;
    }
    .text-small{
        font-size: 12px;
    }


</style>