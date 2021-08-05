<template>
    <v-container fluid v-if="hasMessages || hasMessages">
        <v-alert
            v-if="hasMessages"
            v-for="msg in messages"
            :key="messages.indexOf(msg)"
            color="success"
            dark
            dismissible
            v-on:input="clearMessage(msg)"
        >
            <span v-if="typeof(msg) == 'string'">{{ msg }}</span>
            <p v-if="typeof(msg) == 'object'" v-for="m in msg" :key="msg.indexOf(m)">
                {{ m }}
            </p>
        </v-alert>
        <v-alert
            v-if="hasErrors"
            v-for="err in errors"
            :key="errors.indexOf(err)"
            color="danger"
            dark
            dismissible
            v-on:input="clearError(err)"
        >
            <span v-if="typeof(err) == 'string'">{{ err }}</span>
            <p v-if="typeof(err) == 'object'" v-for="e in err" :key="err.indexOf(m)">
                {{ e }}
            </p>
        </v-alert>
    </v-container>
</template>
<script>
export default {
    name: "Notification",
    data: () => ({}),
    computed: {
        messages(){
            return this.$store.state.messages
        },
        errors(){
            return this.$store.state.errors
        },
        hasMessages(){
            return this.messages.length > 0
        },
        hasErrors(){
            return this.errors.length > 0
        }
    },
    methods: {
        clearMessage(msg){
            this.$store.state.messages.pop(msg);
        },
        clearError(err){
            this.$store.state.errors.pop(err);
        },
    }
}
</script>
