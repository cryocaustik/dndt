<template>
    <v-navigation-drawer
        app
        clipped
        color="primary"
        v-model="visible"

        :permanent="permanentDrawer"
        :mini-variant="miniDrawer"
        :expand-on-hover="miniDrawer"
    >
        <template v-slot:prepend>
            <v-list-item class="px-2">
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
    props: [
        "toggle"
    ],
    data: () => ({
        visible: true,
        // permanentDrawer: true,
        // miniDrawer: false,
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
    }),
    watch: {
        toggle: function(){
            this.toggleNavDrawer()
        },
        mobileScreen: function(val){
            this.toggleMobileDrawer(val)
        }
    },
    computed: {
        permanentDrawer(){
            return this.$store.getters.permanentDrawer
        },
        miniDrawer(){
            return this.$store.getters.miniDrawer
        },
        mobileScreen(){
            return this.$vuetify.breakpoint.mdAndDown
        },
        username(){
            let user = this.$store.getters.user
            if(!user){ return "" }

            return user.name ? user.name : user.username
        },
        avatarUsername(){
            let username = this.username
            return username ? username.split(" ").map(str => str[0]).join("") : "NA"
        }
    },
    methods: {
        toggleMobileDrawer(isMobile){
            if(isMobile){
                this.$store.commit("permanentDrawer", false)
                this.$store.commit("miniDrawer", false)
            } else {
                if(!this.$store.getters.permanentDrawer){
                    this.$store.commit("permanentDrawer", true)
                }
            }
        },
        toggleNavDrawer(){
            if(this.mobileScreen){
                if(this.$store.getters.permanentDrawer){
                    this.$store.commit("permanentDrawer", false)
                }
                this.visible = !this.visible
            } else {
                this.$store.commit("miniDrawer", !this.$store.getters.miniDrawer)
            }
        },
    },
    mounted(){
        this.toggleMobileDrawer(this.mobileScreen)
    }
}
</script>
