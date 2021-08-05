<template>
    <v-container fluid>
        <v-toolbar>
            <v-toolbar-title class="mr-2">Inventory</v-toolbar-title>

            <v-toolbar-items>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs}">
                        <v-btn
                            tile
                            icon
                            color="success"
                            @click="addNewDialog = !addNewDialog"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </template>
                    <span>Add</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs}">
                        <v-btn
                            tile
                            icon
                            @click="showFilters = !showFilters"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon>mdi-filter</v-icon>
                        </v-btn>
                    </template>
                    <span>Advanced Filters</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            tile
                            icon
                            color="blue"
                            @click="refresh"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Refresh</span>
                </v-tooltip>

            </v-toolbar-items>

            <v-spacer></v-spacer>

            <v-toolbar-items>
                <v-menu offset-y tile>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            icon
                            tile
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item
                            link
                            :disabled="!selectedCampaign"
                            @click="importDialog = !importDialog"
                        >
                            <v-list-item-title>
                                <v-icon>mdi-import</v-icon>
                                Import
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item
                            link
                            :disabled="!selectedCampaign"
                            @click="exportDialog = !exportDialog"
                        >
                            <v-list-item-title>
                                <v-icon>mdi-export</v-icon>
                                Export
                            </v-list-item-title>
                        </v-list-item>

                    </v-list>
                </v-menu>
            </v-toolbar-items>
        </v-toolbar>

        <v-toolbar class="mb-2" height="fit-content">
            <v-row class="pb-2">
                <v-col class="col-12 col-md-6">
                    <v-select
                        :items="campaignSelect"
                        v-model="selectedCampaign"
                        hide-details
                        label="campaign"
                    ></v-select>
                </v-col>
                <v-col class="col-12 col-md-6">
                    <v-text-field
                        v-model="search"
                        prepend-inner-icon="mdi-magnify"
                        clearable
                        hide-details
                        placeholder="search"
                    ></v-text-field>
                </v-col>
            </v-row>
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

        <Export
            :toggle="exportDialog"
            :selectedCampaign="selectedCampaign"
            :campaignSelect="campaignSelect"
            v-on:close="exportDialog = false"
        />

        <Import
            :toggle="importDialog"
            :selectedCampaign="selectedCampaign"
            :campaignSelect="campaignSelect"
            v-on:close="importDialog = false"
        />
    </v-container>
</template>
<script>
import ItemFilter from "../components/inventory/ItemFilter";
import Summary from "../components/inventory/Summary";
import Log from "../components/inventory/Log";
import Currency from "../components/inventory/Currency";
import AddNew from "../components/inventory/AddNew";
import Export from "../components/inventory/Export";
import Import from "../components/inventory/Import";

export default {
    name: "Inventory",
    data: () => ({
        tab: null,
        selectedCampaign: null,
        search: null,
        showFilters: false,
        addNewDialog: false,
        exportDialog: false,
        importDialog: false,
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
        },
        mobileScreen(){
            return this.$vuetify.breakpoint.mdAndDown
        }
    },
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
        if(this.campaignSelect.length < 1){
            this.$store.dispatch('getCampaigns');
            setTimeout(() => {
                this.selectedCampaign = this.$store.state.campaign.list.map(c => c.id)[0];
            }, 1500);
        } else {
            this.selectedCampaign = this.$store.state.campaign.list.map(c => c.id)[0];
        }
    },
    components: {
        ItemFilter,
        Summary,
        Log,
        Currency,
        AddNew,
        Export,
        Import
    }
}
</script>
