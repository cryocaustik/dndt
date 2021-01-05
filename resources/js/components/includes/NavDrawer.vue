<template>
    <v-navigation-drawer permanent app clipped color="red darken-4">
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">
                        DnDT
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        Dungeons and Dragons Tracker
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

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
                    title: "Inventory",
                    route: "inventory",
                    icon: "mdi-bag-personal-outline"
                },
            ]
        }
    }
}
</script>
