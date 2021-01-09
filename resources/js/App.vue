<template>
    <v-app>
        <NavBar v-if="!minimizedApp"/>
        <NavDrawer v-if="!minimizedApp"/>

        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import NavBar from "./components/includes/NavBar";
import NavDrawer from "./components/includes/NavDrawer";

export default {
    name: "App",
    computed: {
        minimizedApp(){
            return this.$store.state.minimizedApp
        }
    },
    components: {
        NavBar,
        NavDrawer
    },
    mounted() {
        this.$store.commit('setAuthUser', window.authUser);
    }
};
</script>
