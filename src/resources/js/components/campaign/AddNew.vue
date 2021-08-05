<template>
    <v-dialog
        v-model="addNewDialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>
                    Add New Campaign
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
    name: "CampaignAddNew",
    props: [
        'addNewDialog'
    ],
    data: () => ({
        campaign: {
            name: "",
            dm: "",
            active: false,
        },
        defaultCampaign: {
            name: "",
            dm: "",
            active: false,
        }
    }),
    methods: {
        save(){
            let campaign = this.campaign
            this.$store.dispatch('storeCampaign', campaign)
            this.campaign = this.defaultCampaign
            this.$emit('close');
        }
    }
}
</script>
