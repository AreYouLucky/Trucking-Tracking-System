<template>
    <v-sheet elevation="12" max-width="100vw" rounded="xl" width="100%" class="pa-15 text-center mx-auto bg-transparent">
        <h2 class="text-h6 mb-6 ">The system has a total of <b class="text-h3 text-black">{{
            data.totalCustomer }}</b> registered customer(s).</h2>

        <v-divider class="mb-4"></v-divider>
        <v-row class="justify-center">
            <v-col cols="12" md="3">
                <v-sheet border="md" class="text-body-2 mx-auto pa-3" max-width="550" color="blue-darken-1"  rounded="xl">
                    <v-row class="ma-1 justify-center">
                        <v-icon size="x-large" color="mb-2">
                            mdi-card-account-details-outline
                        </v-icon>
                        <h2 class="text-h6 mx-2"><b>DRIVERS</b></h2>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row class="mt-2 justify-center  text-h7">
                        Total Drivers: <b class="text-orange mx-2">{{ data.totalDriver }}</b>
                    </v-row>
                    <v-divider vertical></v-divider>
                    <v-row class="ma-1 justify-center  text-small row-total pa-1">
                        Available: <b class="text-green mx-1">{{ data.availableDriver }}</b>
                        <v-divider vertical class="mx-1"></v-divider>
                        Delivering: <b class="text-yellow mx-1">{{ data.unavailableDriver }}</b>
                    </v-row>
                </v-sheet>
            </v-col>
            <v-col cols="12" md="4">
                <v-sheet border="md" class="text-body-2 mx-auto pa-3" max-width="550" color="white"  rounded="xl">
                    <v-row class="ma-1 justify-center ">
                        <v-icon size="x-large" color="black">
                            mdi-credit-card-marker
                        </v-icon>
                        <h2 class="text-h6 mx-2 text-black"><b>DELIVERIES</b></h2>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row class="mt-2 justify-center text-h7">
                        Total Deliveries: <b class="text-yellow mx-2">{{ data.totalDelivery }}</b>
                    </v-row>
                    <v-divider vertical></v-divider>
                    <v-row class="ma-1 justify-center text-small row-total pa-1 text-white">
                        Delivered: <b class="text-blue mx-1">{{ data.deliveredDelivery }}</b>
                        <v-divider vertical class="mx-1"></v-divider>
                        Delivering: <b class="text-yellow mx-1">{{ data.deliveringDelivery }}</b>
                        <v-divider vertical class="mx-1"></v-divider>
                        Pending: <b class="text-green mx-1">{{ data.pendingDelivery }}</b>
                    </v-row>
                </v-sheet>

            </v-col>
            <v-col cols="12" md="3">
                <v-sheet border="md" class="text-body-2 mx-auto pa-3" max-width="550" color="blue-darken-1"  rounded="xl">
                    <v-row class="ma-1 justify-center ">
                        <v-icon size="x-large" color="mb-2">
                            mdi-truck-delivery
                        </v-icon>
                        <h2 class="text-h6 mx-2"><b>VEHICLES</b></h2>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row class="mt-2 justify-center  text-h7">
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
        <v-row class="mt-5">
            <v-data-table :headers="headers" :items="deliveries" :search="search" :loading="loading"
                v-model:sort-by="sortBy" items-per-page="5" class="data-table rounded-xl">

                <template v-slot:top>
                    <v-toolbar color="blue">
                        <v-spacer></v-spacer>
                        <h2 class="text-h6 mx-2"><b>Recent Deliveries</b></h2>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn icon="mdi-eye-arrow-right" variant="text" color="primary"
                        :href="`/view-delivery/${item.delivery_id}`" target="_blank"></v-btn>
                    <v-btn icon="mdi-file-chart-check" variant="text" color="yellow" :href="`/proof/${item.delivery_id}`"
                        target="_blank" v-if="item.is_delivered >= 3"></v-btn>
                    <v-btn icon="mdi-sign-direction" variant="text" color="red" :href="`/route/${item.delivery_id}`"
                        target="_blank" v-if="item.is_delivered >= 3"></v-btn>
                </template>

                <template v-slot:item.status="{ item }">
                    <v-btn variant="tonal" color="success" prepend-icon="mdi-check-circle"
                        v-if="item.is_delivered === 0">Pending</v-btn>
                    <v-btn variant="tonal" color="yellow" prepend-icon="mdi-truct-fast"
                        v-if="item.is_delivered === 1 || item.is_delivered === 2">Delivering</v-btn>
                    <v-btn variant="tonal" color="orange" prepend-icon="mdi-ticket-confirmation"
                        v-if="item.is_delivered === 3" @click="popConfirm(item.delivery_id)">Waiting Confirmation</v-btn>
                    <v-btn variant="tonal" color="secondary" prepend-icon="mdi-truck-check"
                        v-if="item.is_delivered === 4">Delivered</v-btn>
                </template>
            </v-data-table>

            <!-- Dialogs -->

            <v-dialog v-model="confirmDialog" width="auto" persistent>
                <v-card>
                    <v-card-text>
                        Is the item successfully delivered?
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="info" variant="outlined" @click="confirmDialog = false">Close</v-btn>
                        <v-btn color="red" variant="outlined" @click="confirmDelivery">Yes</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-snackbar v-model="confirmSnack" timeout="3000" color="red">
                item Successfully Delivered!
                <template v-slot:actions>
                    <v-btn color="info" variant="text" @click="confirmSnack = false"></v-btn>
                </template>
            </v-snackbar>
        </v-row>

    </v-sheet>
</template>
<script>

export default {
    data() {
        return {
            data: {},
            sortBy: [{ key: 'delivery_id', order: 'desc' }],
            loading: false,
            headers: [

                {
                    title: ' Driver Last Name',
                    align: 'start',
                    sortable: false,
                    key: 'lname',
                },
                {
                    title: 'Customer Username',
                    align: 'start',
                    sortable: false,
                    key: 'username',
                },
                {
                    title: 'Reciever Name',
                    align: 'start',
                    sortable: false,
                    key: 'reciever_name',
                },
                {
                    title: 'Reciever No',
                    align: 'center',
                    sortable: false,
                    key: 'reciever_no',
                },
                {
                    title: 'Pickup Date',
                    align: 'center',
                    sortable: false,
                    key: 'date',
                },
                {
                    title: 'Pickup Time',
                    align: 'center',
                    sortable: false,
                    key: 'time',
                },
                {
                    title: 'Status',
                    align: 'center',
                    sortable: false,
                    key: 'status',
                },
            ],
            visible: false,
            search: '',
            confirmDialog: false,
            row: '',
            confirmSnack: false,
            deliveries: [],
        };
    },
    methods: {
        getData() {
            axios.get('/load-dashboard').then(
                res => {
                    this.data = res.data;
                }
            )
        },
        showDeliveries: function () {
            axios.get('/load-deliveries').then(
                res => {
                    this.loading = true
                    setTimeout(() => {
                        this.loading = false
                    }, 2000)

                    this.deliveries = res.data;
                }
            )
        },
        initData() {
            this.showDeliveries();
        },
        popConfirm(row) {
            this.row = row;
            this.confirmDialog = true;
        },
        confirmDelivery() {
            axios.post('/confirm-delivery/' + this.row).then(res => {
                this.showDeliveries();
                this.confirmDialog = false;
                this.confirmSnack = true
                this.row = ''
            })
        },
        initData() {
            this.showDeliveries();
            this.getData();
        },
    },
    mounted() {
        this.initData();
    }
}
</script>
<style scoped>
.text-small {
    font-size: 13px;
}

.row-total {
    background-color: rgb(0, 0, 0);
    opacity: 0.7;
}
.data-table{

    overflow: hidden;
}
</style>