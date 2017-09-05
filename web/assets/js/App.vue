<template>
    <div>
        <ul>
            <li v-for="phone in phones">
                <p>{{ phone.name }}</p>
                <p>{{ phone.brand.name }}</p>
                <img :src="'http://127.0.0.1:8001/uploads/images/phones/' + phone.image">
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                phones: [],
            };
        },
        created() {
            // GET /someUrl
            this.$http.get('http://127.0.0.1:8001/api/phones').then(response => {
                this.phones = response.data[0].items;
                console.log(this.phones);
            })
        }
    }
</script>