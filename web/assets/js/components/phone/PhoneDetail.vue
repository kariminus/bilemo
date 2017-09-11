<template>
    <div>
        <h3>Phone Details</h3>
        <p>{{ phone.name }}</p>
        <p>{{ phone.brand.name }}</p>
        <img :src="'http://127.0.0.1:8001/uploads/images/phones/' + phone.image">
    </div>
</template>

<script>
    export default {
        props: ['slug'],
        computed: {
            phone () {
                return this.$store.getters.loadedPhone(this.slug)
            }
        }
    }
</script>