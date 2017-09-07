<template>
    <div>
        <ul v-for="phone in phones">
            <router-link
                    tag="li"
                    :to="'/'+phone.id"
                    class="list-group-item"
                    style="cursor: pointer">{{ phone.name }}</router-link>
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
            })
        }
    }
</script>