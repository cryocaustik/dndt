<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.inventory.summary"
            :search="search"
            :loading="isLoading"
            :loading-text="loadingMsg"
            :no-data-text="loadingMsg"
            sort-by="item"
        >
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="$emit('view', item.item)"
                    color="blue"
                >
                    mdi-file-find-outline
                </v-icon>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>
export default {
    name: "InventorySummary",
    props: [
        'campaignId',
        'search'
    ],
    data(){
        return {
            headers: [
                {text: 'Item', value: 'item'},
                {text: 'Held By', value: 'held_by'},
                {text: 'Quantity', value: 'quantity'},
                {text: 'Actions', value: 'actions', sortable: false },
            ],
        }
    },
    watch: {
        campaignId: function(campaignId) {
            this.$store.dispatch('getInventorySummary', campaignId);
        }
    },
    computed: {
        isLoading(){
            if(!this.campaignId){
                return false
            } else if (this.campaignId && this.$store.state.axiosPending && this.$store.state.inventory.summary.length < 1){
                return true
            }
        },
        loadingMsg(){
            return !this.campaignId ? "Select a Campaign to load data" : "No data found for specified campaign"
        }
    },
    mounted(){
        if(this.campaignId){
            this.$store.dispatch('getInventorySummary', this.campaignId);
        }
    }
}
</script>
