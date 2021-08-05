<template>
    <v-container fill-height>
        <v-card class="mx-auto" min-width="400px">
            <v-img
                src="img/logo-register-white.png"
                max-width="400px"
                class="ma-2"
            ></v-img>

            <v-divider></v-divider>
            <v-card-text>
                <v-form>
                    <v-text-field
                        v-model="username"
                        label="username"
                        :rules="usernameRules"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="email"
                        label="email"
                        type="email"
                        :rules="emailRules"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="password"
                        label="password"
                        type="password"
                        :rules="passwordRules"
                        ref="password"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="passwordConfirm"
                        label="password confirmation"
                        type="password"
                        :error="passwordConfirmRules"
                        :error-messages="passwordConfirmError"
                        required
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-col>
                    <v-btn
                        @click="register"
                        block
                        large
                        color="red darken-4"
                    >
                        Register
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
                        <span class="mx-2 text-secondary">Have an account?</span>
                        <v-divider></v-divider>
                    </v-row>
                    <v-btn
                        :to="{ name: 'login' }"
                        block
                        large
                        link
                    >
                        Login
                    </v-btn>
                </v-col>
            </v-card-actions>
        </v-card>
    </v-container>
</template>
<script>
export default {
    name: "Register",
    data: () => ({
        valid: true,
        username: "",
        usernameRules: [
            v => !!v || 'Username is required',
            v => (v && v.length >= 5) || 'Name must be at least 5 characters',
        ],
        email: "",
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
        password: "",
        passwordConfirm: "",
        passwordRules: [
            v => (v && v.length >= 8)  || 'Password must be at least 8 characters'
        ]
    }),
    computed: {
        passwordConfirmRules(){
            if(this.passwordConfirm.length === 0){
                return false
            } else if(this.password && this.password !== this.passwordConfirm){
                return true
            }
            return false
        },
        passwordConfirmError(){
            let msg = "Password must be at least 8 characters"
            return this.passwordConfirmRules ? msg : ""
        }
    },
    methods: {
        register(){
            if(!this.username || !this.email || !this.password){
                window.Vue.$vToastify.error('username and password required');
            }
            let credentials = {
                username: this.username,
                email: this.email,
                password: this.password,
                password_confirmation: this.passwordConfirm
            }
            this.$store.dispatch('register', credentials)
        }
    }
}
</script>
