<template>
    <v-sheet
      elevation="12"
      max-width="100vw"
      rounded="lg"
      width="100%"
      class="pa-10 text-center mx-auto"
    >
      <v-icon
        class="mb-5"
        color="success"
        icon="mdi-check-circle"
        size="112"
      ></v-icon>
      <h2 class="text-h8 mb-2">GREAT!</h2>
      <h2 class="text-h6 mb-6 text-medium-emphasis">The system has a total of  <b class="text-h3 text-blue">{{ data.totalCustomer }}</b> registered customer(s).</h2>
  
      <v-divider class="mb-4"></v-divider>
      <v-row class="justify-center">
        <v-col cols="12" md="3">
            <v-sheet
                border="md"
                class="text-body-2 mx-auto pa-3"
                max-width="550"
                color="blue-darken-4"
            >
            <v-row class="ma-1 justify-center text-medium-emphasis">
                    <v-icon size="x-large" color="mb-2">
                        mdi-card-account-details-outline
                    </v-icon>
                    <h2 class="text-h6 mx-2"><b>DRIVERS</b></h2>
            </v-row>
            <v-divider></v-divider>
            <v-row class="mt-2 justify-center text-medium-emphasis text-h7"> 
                Total Drivers: <b class="text-orange mx-2">{{ data.totalDriver }}</b>
            </v-row>
            <v-divider vertical></v-divider>
            <v-row class="ma-1 justify-center text-medium-emphasis text-small row-total pa-1">
                Available: <b class="text-green mx-1">{{ data.availableDriver }}</b>
                <v-divider vertical class="mx-1"></v-divider>
                Delivering: <b class="text-yellow mx-1">{{ data.unavailableDriver }}</b>
            </v-row>
            </v-sheet>
        </v-col>
        <v-col cols="12" md="4">
            <v-sheet
                border="md"
                class="text-body-2 mx-auto pa-3"
                max-width="550"
                color="pink-darken-4"
            >
            <v-row class="ma-1 justify-center text-medium-emphasis">
                    <v-icon size="x-large" color="mb-2">
                        mdi-credit-card-marker
                    </v-icon>
                    <h2 class="text-h6 mx-2"><b>DELIVERIES</b></h2>
            </v-row>
            <v-divider></v-divider>
            <v-row class="mt-2 justify-center text-medium-emphasis text-h7"> 
                Total Deliveries: <b class="text-yellow mx-2">{{ data.totalDelivery }}</b>
            </v-row>
            <v-divider vertical></v-divider>
            <v-row class="ma-1 justify-center text-medium-emphasis text-small row-total pa-1">
                Delivered: <b class="text-blue mx-1">{{ data.deliveredDelivery }}</b>
                <v-divider vertical class="mx-1"></v-divider>
                Delivering: <b class="text-yellow mx-1">{{ data.deliveringDelivery }}</b>
                <v-divider vertical class="mx-1"></v-divider>
                Pending: <b class="text-green mx-1">{{ data.pendingDelivery }}</b>
            </v-row>
            </v-sheet>
            
        </v-col>
        <v-col cols="12" md="3">
            <v-sheet
                border="md"
                class="text-body-2 mx-auto pa-3"
                max-width="550"
                color="deep-purple-darken-4"
            >
            <v-row class="ma-1 justify-center text-medium-emphasis">
                    <v-icon size="x-large" color="mb-2">
                        mdi-truck-delivery
                    </v-icon>
                    <h2 class="text-h6 mx-2"><b>VEHICLES</b></h2>
            </v-row>
            <v-divider></v-divider>
            <v-row class="mt-2 justify-center text-medium-emphasis text-h7"> 
                Total Vehicles: <b class="text-orange mx-2">{{ data.totalVehicle }}</b>
            </v-row>
            <v-divider vertical></v-divider>
            <v-row class="ma-1 justify-center text-medium-emphasis text-small row-total pa-1">
                Available: <b class="text-green mx-1">{{ data.availableVehicle }}</b>
                <v-divider vertical class="mx-1"></v-divider>
                Delivering: <b class="text-yellow mx-1">{{ data.unavailableVehicle }}</b>
            </v-row>
            </v-sheet>
            
        </v-col>
        
      </v-row>

    </v-sheet>
</template>
<script>

export default{
   data(){
       return {
            data:{}
       };
   },
   methods: {
      getData(){
        axios.get('/load-dashboard').then(
            res=>{
                this.data = res.data;
            }
        )
      },
      initData(){
        this.getData();
      },
    },
    mounted(){
        this.initData();
    }
}
</script>
<style scoped>
.text-small{
    font-size: 13px;
}
.row-total{
    background-color: rgb(0, 0, 0);
    opacity: 0.7;
}

</style>