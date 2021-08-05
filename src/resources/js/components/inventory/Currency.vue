<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.inventory.currency"
            :search="search"
            :loading="isLoading"
            :loading-text="loadingMsg"
            :no-data-text="loadingMsg"
        >
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="$emit('view', item.item)"
                    color="primary"
                >
                    mdi-file-find-outline
                </v-icon>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>
export default {
    name: "InventoryCurrency",
    props: [
        'campaignId',
        'search'
    ],
    data: () => ({
        headers: [
            {text: 'Item', value: 'item'},
            {text: 'Quantity', value: 'quantity'},
            {text: 'Actions', value: 'actions', sortable: false },
        ],
    }),
    watch: {
        campaignId: function(campaignId) {
            this.$store.dispatch('getInventoryCurrency', campaignId);
        }
    },
    computed: {
        isLoading(){
            if (
                this.campaignId &&
                this.$store.state.axiosPending &&
                this.$store.state.inventory.currency.length < 1
            ){
                return true
            }

            return false
        },
        loadingMsg(){
            return !this.campaignId ? "Select a Campaign to load data" : "No data found for specified campaign"
        }
    },
    mounted(){
        if(this.campaignId){
            this.$store.dispatch('getInventoryCurrency', this.campaignId);
        }
    }
}
</script>
