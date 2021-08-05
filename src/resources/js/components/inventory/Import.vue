<template>
    <v-dialog
        v-model="toggle"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>Inventory Import</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn
                        dark
                        text
                        @click="close"
                    >
                        Close
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-row>
                    <v-col class="col-12 col-md-4">
                        <v-select
                            :items="campaignSelect"
                            v-model="selectedCampaign"
                            label="Campaign"
                        ></v-select>
                        <v-file-input
                            accept=".csv"
                            label="CSV Input"
                            truncate-length="50"
                            v-model="importFile"
                        ></v-file-input>

                        <v-divider clas="mb-2"></v-divider>

                        <v-btn
                            tile
                            block
                            color="primary"
                            :disabled="!importFile"
                            @click="uploadFile"
                        >
                            <v-icon>mdi-import</v-icon>
                            Import
                        </v-btn>

                        <v-card class="mt-2">
                            <v-card-title>Import Data Requirements</v-card-title>
                            <v-card-text>
                                <v-data-table
                                    :headers="importFormatHeaders"
                                    :items="importFormatData"
                                    disable-filtering
                                    disable-sort
                                    disable-pagination
                                    hide-default-footer
                                ></v-data-table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col class="col-12 col-md-8">
                        <v-card max-height="70vh" style="overflow: hidden">
                            <v-card-title>Import Sample</v-card-title>
                            <v-card-text>
                                <pre style="user-select: none">{{ importSample }}</pre>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "InventoryImport",
    props: {
        toggle: {
            type: Boolean,
            default: false
        },
        selectedCampaign: { default: null },
        campaignSelect: { default: [] },
    },
    data: () => ({
        importFile: null,
        importSample: "",
        importFormatHeaders: [
            { text: 'Field Name', value: 'name' },
            { text: 'Field Definition', value: 'definition' },
        ],
        importFormatData: [
            { name: 'occurred_on', definition: 'Date (optional time) item acquired' },
            { name: 'item', definition: 'Item name/description' },
            { name: 'quantity', definition: 'Quantity' },
            { name: 'held_by', definition: 'Name of entity holding the item' },
            { name: 'source', definition: 'Where the item was obtained (e.g. Red Dragon\'s lair)' },
            { name: 'claimed_by', definition: 'Name of entity who claimed the item as personal (which will no longer track in summary)' },
            { name: 'in_bag_of_holding', definition: 'Y/N if the item is being held in the bag of holding (because those can be broken and stolen!)' },
            { name: 'notes', definition: 'Any additional notes about them item or its origin' },
        ]
    }),
    watch: {
        importFile: function(){
            this.previewFile()
        }
    },
    methods: {
        previewFile(){
            if(!this.importFile){
                return this.importSample = "no file selected"
            }

            let vThis = this
            let reader = new FileReader()
            reader.onload = e => {
                let file = e.target.result.slice(0,200)
                vThis.importSample = file.split(/\r?\n/)
            }

            reader.onerror = e => {
                window.Vue.$vToastify.error(`Import Sample Failed`, e.target.error.name);
                console.log(e.target.error)
            }

            reader.readAsText(this.importFile)
        },
        uploadFile(){
            if(!this.importFile){
                window.Vue.$vToastify.error(`No file selected`);
            }

            let file = new FormData()
            file.append('file', this.importFile)
            file.append('campaign_id', this.selectedCampaign)
            this.$store.dispatch('importInventory', file)
            window.Vue.$vToastify.info(`${this.importFile.name} is being imported`, 'Working');
            this.close()
        },
        close(){
            this.importFile = null
            this.importSample = ""
            this.$emit('close')
        }
    }
}
</script>
