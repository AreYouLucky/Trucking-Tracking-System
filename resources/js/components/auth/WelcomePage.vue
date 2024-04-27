<template>
    <v-app>
        <v-layout class="rounded rounded-md">
            <v-app-bar  color="primary" class="pa-4" density="compact">
                <img src="../../../img/logo.png" width="90px">
                <h3 class="title">LARA's TRUCKING SERVICES</h3>
            </v-app-bar>
            <v-main class="mt-10 color">
                <v-container class="align-center justify-center">
                    <v-form @submit.prevent="submitForm">
                        <v-card class="mx-auto pa-15" elevation="8" max-width="400" rounded="lg" cols="12" md="7" color="primary">
                            <v-row >
                                <v-col class="d-flex justify-center align-center mb-5">
                                    <v-avatar color="third" size="80" icon="mdi-account-circle">

                                    </v-avatar>
                                </v-col>
                            </v-row>

                            <v-text-field
                                v-model ="fields.username"
                                class="mb-2"
                                label="Username"
                                :rules="[rules.required, rules.min]"
                                hint="Enter your password to access this website"
                                prepend-inner-icon="mdi-email-outline"
                                variant="outlined"
                                :error-messages="err.username ? err.username[0] : ''"
                                required

                            ></v-text-field>

                                <v-text-field
                                    v-model ="fields.password"
                                    label="Password"
                                    :rules="[rules.required]"
                                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                                    :type="visible ? 'text' : 'password'"
                                    placeholder="Enter your password"
                                    prepend-inner-icon="mdi-lock-outline"
                                    variant="outlined"
                                    @click:append-inner="visible = !visible"
                                    :error-messages="err.password ? err.password[0] : ''"
                                    required
                                ></v-text-field>

                                <v-btn
                                    block
                                    :loading="loading"
                                    class="my-3"
                                    color="secondary"
                                    size="large"
                                    type="submit"
                                >
                                    Log In
                                </v-btn>
                        </v-card>
                    </v-form>

                </v-container>
            </v-main>

            <v-dialog
                v-model="errorDialog"
                width="auto"
                >
                <v-card  color="primary">
                    <v-card-text>
                    {{ errors }}
                    </v-card-text>
                    <v-card-actions>
                    <v-btn color="primary" class="text-white" block variant="tonal" elevation="4" @click="errorDialog = false">Okay!</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
     </v-layout>
    </v-app>
  </template>
<script>
export default{
   data(){
       return {
           visible: false,
           rules: {
            required: value => !!value || 'Required.',
            min: v => v.length >= 4 || 'Minimum 4 characters',
            },
            loading: false,
            fields:{},
            errorDialog: false,
            err: {}

       };
   },
   methods: {
      submitForm() {
        this.loading = true
        setTimeout(() => (this.loading = false), 2000)
        axios.post('/login',this.fields)
        .then(res=>{
            if(res.data.role === 'CUSTOMER'){
                window.location = '/customer-dashboard';
            }
            else if(res.data.role === 'ADMIN'){
                window.location ='/admin-dashboard';
            }
            else if(res.data.role === 'DRIVER'){
                window.location = '/driver-dashboard';
            }
        }).catch(error=>{
            this.errors = error.response.data.errors.logs;
            this.err = error.response.data;
            if(error.response.data.errors.logs){
                this.errorDialog = true;
            }

        })
      },
    },
}
</script>
<style>
    .color{
        background-color: aliceblue;
    }
    .title{
        color: aliceblue;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }
    body{
       background-color:#FFF8E3;
    }

</style>
