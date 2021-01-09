<template>
    <v-navigation-drawer permanent app clipped color="red darken-4">
        <template v-slot:prepend>
            <v-list-item two-line>
                <v-list-item-avatar color="grey darken-4">
                    <span class="white--text headline">
                        {{ avatarUsername }}
                    </span>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{ username }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </template>

        <v-divider class="mt-0"></v-divider>

        <v-list
            dense
            nav
        >
            <v-list-item
                link
                v-for="route in routes"
                :key="route.title"
                :to="{ name: route.route }"
            >
                <v-list-item-icon>
                    <v-icon>{{ route.icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ route.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>

</template>
<script>
export default {
    name: "NavDrawer",
    data() {
        return {
            routes: [
                {
                    title: "Campaigns",
                    route: "campaign",
                    icon: "mdi-campfire"
                },
                {
                    title: "Permissions",
                    route: "permission",
                    icon: "mdi-account-cog-outline"
                },
                {
                    title: "Inventory",
                    route: "inventory",
                    icon: "mdi-bag-personal-outline"
                },
            ]
        }
    },
    computed: {
        username(){
            let user = this.$store.state.user;
            if(!user){ return "" }

            return user.name ? user.name : user.username
        },
        avatarUsername(){
            let username = this.username
            return username ? username.split(" ").map(str => str[0]).join("") : "NA"
        }
    }
}
</script>
