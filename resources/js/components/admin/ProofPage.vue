<template>
      <!-- <v-img :src="`/storage/proofs/${proof.proof_path}`" alt="profile"  contain/> -->
      <v-img :src="'{{ asset(`/storage/proofs/${proof.proof_path}`) }}'" alt="" contain/>


  </template>
  
<script>

  export default {
    data() {
      return {
        data_id: null,
        proof: [],
        map: null,
      };
    },
    methods: {
        getProof(){
            axios.get('/get-proof/'+this.data_id).then(
                res=>{
                    this.proof = res.data
                    console.log(this.proof)
                }
            )
        }
      
    },
    mounted() {
      const urlParts = window.location.href.split("/");
      this.data_id = urlParts[urlParts.length - 1];
      this.getProof();
      
     
    }
  };
  </script>
  
  <style scoped>
 .image{
  height: 100vh;
 }
  </style>
  