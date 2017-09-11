<template>
    <div>
        <ul v-for="phone in phones">
            <router-link
                    tag="li"
                    :to="'/phone/' + phone.slug"
                    class="list-group-item"
                    style="cursor: pointer">{{ phone.name }}</router-link>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            phones () {
                return this.$store.getters.loadedPhones
            }
        }
    }
</script>