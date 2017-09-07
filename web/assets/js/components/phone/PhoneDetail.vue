<template>
    <div>
        <h3>Phone Details</h3>
        <p>{{ phone.name }}</p>
        <p>{{ phone.brand.name }}</p>
        <img :src="'http://127.0.0.1:8001/uploads/images/phones/'+phone.image">
    </div>
</template>

<script>
    export default {
        data() {
            return {
                phone: {},
            };
        },
        mounted() {
            // GET /someUrl
            this.$http.get('http://127.0.0.1:8001/api/phones/' + this.$route.params.slug).then(response => {
                this.phone = response.data;
            })
        }
    }
</script>