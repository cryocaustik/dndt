<template>
    <v-container fill-height>
        <v-card class="mx-auto" min-width="400px">
            <v-img
                src="img/logo-login-red.png"
                max-width="400px"
                class="ma-2"
            ></v-img>

            <v-divider></v-divider>
            <v-form @submit.prevent="login">
                <v-card-text>
                        <v-text-field
                            v-model="username"
                            label="username"
                            required
                        ></v-text-field>

                        <v-text-field
                            v-model="password"
                            label="password"
                            type="password"
                            required
                        ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-col>
                        <v-btn
                            type="submit"
                            @click="login"
                            block
                            large
                            color="red darken-4"
                        >
                            Login
                        </v-btn>
                        <v-row align="center" class="my-2">
                            <v-divider></v-divider>
                            <span class="mx-2 text-secondary">or</span>
                            <v-divider></v-divider>
                        </v-row>
                        <v-btn
                            href="/oauth/discord"
                            block
                            large
                            color="#7289DA"
                        >
                            <v-icon class="mr-2">mdi-discord</v-icon>
                            Discord
                        </v-btn>
                        <v-row align="center" class="my-2">
                            <v-divider></v-divider>
                            <span class="mx-2 text-secondary">New Here?</span>
                            <v-divider></v-divider>
                        </v-row>
                        <v-btn
                            :to="{ name: 'register' }"
                            block
                            large
                            link
                        >
                            Register
                        </v-btn>
                    </v-col>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-container>
</template>
<script>
export default {
    name: "Login",
    data: () => ({
        username: "",
        password: "",
    }),
    methods: {
        login(){
            if(!this.username || !this.password){
                window.Vue.$vToastify.error('username and password required');
            }
            let credentials = {
                username: this.username,
                password: this.password
            }
            this.$store.dispatch('login', credentials)
        }
    }
}
</script>
