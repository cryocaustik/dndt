<template>
    <v-container fluid>
        <v-toolbar class="mb-2">
            <v-toolbar-title class="mr-2">Inventory</v-toolbar-title>
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
                color="grey"
                @click="showFilters = !showFilters"
            >
                <v-icon>mdi-filter</v-icon>
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
            <v-col cols="auto">
                <v-select
                    :items="campaignSelect"
                    v-model="selectedCampaign"
                    hide-details
                    label="campaign"
                ></v-select>
            </v-col>
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

        <ItemFilter :showFilters="showFilters" :filters="filters" class="mb-4"/>

        <v-tabs
            color="primary"
            v-model="tab"
        >
            <v-tab key="summary" style="width: 50%;">Summary</v-tab>
            <v-tab key="log" style="width: 50%;">Log</v-tab>
            <v-tab key="currency" style="width: 50%;">Currency</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item key="summary">
                <Summary
                    :campaignId="selectedCampaign"
                    :search="search"
                    v-on:view="view"
                />
            </v-tab-item>
            <v-tab-item key="log">
                <Log
                    :campaignId="selectedCampaign"
                    :campaignSelect="campaignSelect"
                    :search="search"
                />
            </v-tab-item>
            <v-tab-item key="currency">
                <Currency
                    :campaignId="selectedCampaign"
                    :search="search"
                />
            </v-tab-item>
        </v-tabs-items>
        <AddNew
            :campaignSelect="campaignSelect"
            :selectedCampaign="selectedCampaign"
            :addNewDialog="addNewDialog"
            v-on:close="addNewDialog = false"
        />
    </v-container>
</template>
<script>
import ItemFilter from "../components/inventory/ItemFilter";
import Summary from "../components/inventory/Summary";
import Log from "../components/inventory/Log";
import Currency from "../components/inventory/Currency";
import AddNew from "../components/inventory/AddNew";

export default {
    name: "Inventory",
    data: () => ({
        tab: null,
        selectedCampaign: null,
        search: null,
        showFilters: false,
        addNewDialog: false,
        filters: {
            item: "",
            description: "",
            quantity: "",
            acquired_at: "",
            held_by: "",
            claimed: "",
            claimed_by: "",
            in_bag_of_holding: "",
        }
    }),
    computed: {
        campaignSelect(){
            let campaigns = this.$store.state.campaign.list;
            return campaigns ? campaigns.map(c => ({value: c.id, text: c.name})) : [];
        }
    },
    watch: {},
    methods: {
        refresh(){
            let tab = this.tab;
            if(tab === 0){
                this.$store.dispatch('getInventorySummary', this.selectedCampaign)
            } else if (tab === 1){
                this.$store.dispatch('getInventoryLog', this.selectedCampaign)
            } else if (tab === 2){
                this.$store.dispatch('getInventoryCurrency', this.selectedCampaign)
            } else {
                console.log('not sure what to refresh... ')
            }
        },
        view(item){
            this.search = item
            this.tab = 1
        }
    },
    mounted(){
        this.$store.dispatch('getCampaigns');
        this.selectedCampaign = this.$store.state.campaign.list.map(c => c.id)[0];
    },
    components: {
        ItemFilter,
        Summary,
        Log,
        Currency,
        AddNew
    }
}
</script>
