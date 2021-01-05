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
                color="primary"
                @click="refresh"
            >
                <v-icon>mdi-refresh</v-icon>
            </v-btn>

            <v-spacer></v-spacer>
            <v-col class="col-lg-4 col-sm-6">
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

        <v-tabs
            color="red"
            v-model="tab"
        >
            <v-tab key="summary" style="width: 50%;">Summary</v-tab>
            <v-tab key="permissions" style="width: 50%;">Permissions</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">

            <v-tab-item key="summary">
                <Summary
                    :search="search"
                    v-on:view="view"
                />
            </v-tab-item>

            <v-tab-item key="log">
<!--                <Log-->
<!--                    :campaignId="selectedCampaign"-->
<!--                    :campaignSelect="campaignSelect"-->
<!--                    :search="search"-->
<!--                />-->
            </v-tab-item>

        </v-tabs-items>

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
            console.log("refresh")
        },
        view(campaign){
            this.search = campaign
            this.tab = 1
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
