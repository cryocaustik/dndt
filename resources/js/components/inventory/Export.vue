<template>
    <v-dialog
        v-model="toggle"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>Inventory Export</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn
                        dark
                        text
                        @click="$emit('close')"
                    >
                        Close
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-row>
                    <v-col class="col-md-4">
                        <v-select
                            :items="campaignSelect"
                            v-model="selectedCampaign"
                            label="Campaign"
                        ></v-select>

                        <v-select
                            :items="exportType"
                            v-model="selectedExportType"
                            label="Export Type"
                        ></v-select>
                    </v-col>
                    <v-col class="col-md-8">
                        <v-card max-height="70vh" style="overflow: hidden">
                            <v-card-title>Export CSV</v-card-title>
                            <v-card-text>
                                <pre style="user-select: all">{{ exportCSV }}</pre>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import { parse } from "json2csv"

export default {
    name: "InventoryExport",
    props: {
        toggle: {
            type: Boolean,
            default: false
        },
        selectedCampaign: { default: null },
        campaignSelect: { default: [] },
    },
    data: () => ({
        selectedExportType: "summary",
        exportType: [
            { text: "Summary", value: "summary" },
            { text: "Log", value: "log" },
            { text: "Currency", value: "currency" },
        ],
    }),
    computed: {
        exportCSV(){
            switch (this.selectedExportType){
                case "summary":
                    let summary = this.$store.getters.inventorySummary
                    return summary.length > 0 ? parse(summary) : "no data found"

                case "log":
                    let log = this.$store.getters.inventoryLog
                    if(log.length > 0){
                        this.removeCampaign(log)
                        return parse(log)
                    }

                    return "no data found"

                case "currency":
                    let currency = this.$store.getters.inventoryCurrency
                    if(currency.length > 0){
                        this.removeCampaign(currency)
                        return parse(currency)
                    }

                    return "no data found"

                default:
                    return "select an export type"
            }
        }
    },
    methods: {
        removeCampaign(origData){
            let data = [...origData]
            data.forEach(i => {
                delete i.campaign
            })

            return data
        },
        getExportCSV(){
            if(!this.toggle){
                return
            }

            if(!this.selectedCampaign){
                return "select a campaign"
            }

            switch (this.selectedExportType){
                case "summary":
                    if(!this.$store.getters.inventorySummary.length > 0){
                        this.$store.dispatch("getInventorySummary", this.selectedCampaign)
                    }

                    return

                case "log":
                    if(!this.$store.getters.inventoryLog.length > 0){
                        this.$store.dispatch("getInventoryLog", this.selectedCampaign)
                    }

                    return

                case "currency":
                    if(!this.$store.getters.inventoryCurrency.length > 0){
                        this.$store.dispatch("getInventoryCurrency", this.selectedCampaign)
                    }

                    return

                default:
                    return
            }
        }
    },
    watch: {
        toggle: function(v){
            if (v){
                this.selectedExportType = "summary"
                this.getExportCSV()
            }

            this.selectedExportType = null
        },
        selectedCampaign: function(){
            this.getExportCSV()
        },
        selectedExportType: function(){
            this.getExportCSV()
        },
    },

}
</script>
