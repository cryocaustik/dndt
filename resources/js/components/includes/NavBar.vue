<template>
    <v-app-bar app clipped-left dark color="red darken-3">
        <v-app-bar-nav-icon></v-app-bar-nav-icon>

        <v-toolbar-title @click="$router.push('/')" style="cursor: pointer;">DnDT</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn v-if="this.$store.getters.isLoggedIn" text href="/auth/logout">
            <span class="mr-2">Logout</span>
        </v-btn>
        <v-btn v-else text href="/auth/login">
            <span class="mr-2">Login</span>
        </v-btn>
    </v-app-bar>
</template>
<script>
export default {
    name: "NavBar",
    data(){
        return {
            routes: []
        }
    },
    mounted() {
        console.log(`logged in: `);
        console.log(this.$store.getters.isLoggedIn)
    }
}
</script>
