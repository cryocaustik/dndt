<template>
    <v-app>
        <NavBar />
        <NavDrawer />

        <v-main>
            <Notification />
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import NavBar from "./components/includes/NavBar";
import NavDrawer from "./components/includes/NavDrawer";
import Notification from "./components/includes/Notification";

export default {
    name: "App",
    components: {
        NavBar,
        NavDrawer,
        Notification
    },
    mounted() {
        this.$store.commit('setAuthUser', window.authUser);
    }
};
</script>
