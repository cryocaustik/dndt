<template>
    <v-dialog
        v-model="editDialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>
                    Edit Item
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn
                        dark
                        text
                        @click="$emit('close')"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        text
                        color="success"
                        @click="save"
                    >
                        Save
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-text-field
                        v-model="campaign.name"
                        label="Name"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="campaign.dm"
                        label="Dungeon Master"
                        required
                    ></v-text-field>

                    <v-text-field
                        :value="owner"
                        label="Owner (this can only be modified in Campaign Permissions)"
                        disabled
                    ></v-text-field>

                    <v-checkbox
                        v-model="campaign.active"
                        label="Active"
                    ></v-checkbox>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "Edit",
    props: {
        editDialog: {
            default: false
        },
        campaign: {
            default: () => ({
                name: "",
                dm: "",
                active: false,
                owner: {
                    name: "",
                    username: ""
                }
            })
        }
    },
    data: () => ({
        showDatePicker: false
    }),
    computed: {
        owner(){
            if(!this.campaign || !this.campaign.owner){
                return ""
            }
            return this.campaign.owner.name ? this.campaign.owner.name : this.campaign.owner.username
        }
    },
    methods: {
        save(){
            let campaign = this.campaign;
            this.$store.dispatch('updateCampaign', campaign)
            this.$emit('close');
        }
    }
}
</script>
