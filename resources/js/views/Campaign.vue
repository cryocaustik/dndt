<template>
    <v-container fluid>
        <v-toolbar class="mb-2">
            <v-toolbar-title class="mr-2">Campaigns</v-toolbar-title>
            <v-btn
                tile
                icon
                color="success"
                @click="addNewDialog = !addNewDialog"
            >
                <v-icon>mdi-plus</v-icon>
            </v-btn>
            <v-btn
                tile
                icon
                color="blue"
                @click="refresh"
            >
                <v-icon>mdi-refresh</v-icon>
            </v-btn>

            <v-spacer></v-spacer>

            <v-col class="col-12 col-md-4">
                <v-text-field
                    v-model="search"
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    hide-details
                    placeholder="search"
                    class="ml-2"
                ></v-text-field>
            </v-col>

        </v-toolbar>

        <v-card>
            <Summary
                :search="search"
            />
        </v-card>

        <AddNew
            :addNewDialog="addNewDialog"
            v-on:close="addNewDialog = false"
        />
    </v-container>
</template>
<script>
import Summary from "../components/campaign/Summary";
import AddNew from "../components/campaign/AddNew";

export default {
    name: "Campaign",
    data: () => ({
        tab: null,
        addNewDialog: false,
        search: "",
        selectedCampaign: {}
    }),
    methods: {
        refresh(){
            this.$store.dispatch('getCampaigns');
        }
    },
    components: {
        Summary,
        AddNew
    },
    mounted(){
        if(!this.$store.state.campaigns){
            this.$store.dispatch('getCampaigns');
        }
    }
}
</script>
