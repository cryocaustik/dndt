<template>
    <v-dialog
        v-model="toggle"
        persistent
        width="500"
    >
        <v-card>
            <v-card-title>{{ title }}</v-card-title>
            <v-card-text>
                {{ stringMessage }}
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="secondary"
                    autofocus
                    @click.stop="$emit('confirm', false)"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="red darken-4"
                    @click.stop="$emit('confirm', true)"
                >
                    Confirm
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "Alert",
    props: [
        "toggle",
        "message"
    ],
    data: () => ({

    }),
    computed: {
        title(){
            return typeof(this.message) == "string" ? "Confirm" : this.message.title
        },
        stringMessage(){
            return typeof(this.message) == "string" ? this.message : this.message.message
        }
    }
}
</script>
