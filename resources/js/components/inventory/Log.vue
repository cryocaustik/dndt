<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.inventory.log"
            :search="search"
            :loading="isLoading"
            :loading-text="loadingMsg"
            :no-data-text="loadingMsg"
            :single-expand='true'
            :expanded.sync="expanded"
            show-expand
            sort-by="occurred_on"
            sort-desc
        >
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    Notes: {{ item.notes }}
                </td>
            </template>
            <template v-slot:item.claimed="{ item }">
                <v-icon v-if="item.claimed" small color="success">mdi-check</v-icon>
                <v-icon v-else color="secondary" small>mdi-close</v-icon>
            </template>
            <template v-slot:item.in_bag_of_holding="{ item }">
                <v-icon v-if="item.in_bag_of_holding" small color="success">mdi-check</v-icon>
                <v-icon v-else color="secondary" small>mdi-close</v-icon>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click.stop="editItem(item)"
                    color="orange"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    color="red"
                    @click.stop="deleteConfirm(item)"
                >
                    mdi-delete
                </v-icon>
            </template>

        </v-data-table>

        <Edit
            :campaignSelect="campaignSelect"
            :editDialog="editDialog"
            :item="selectedItem"
            v-on:close="editDialog = false; selectedItem = {}"
        />

        <Confirm
            :message="deleteConfirmMsg"
            :toggle="confirmDialog"
            v-on:confirm="deleteItem"
        />
    </v-card>
</template>
<script>
import Edit from "./Edit";
import Confirm from "../includes/Confirm";

export default {
    name: "InventoryLog",
    props: [
        'campaignId',
        'campaignSelect',
        'search'
    ],
    data: () => ({
        expanded: [],
        selectedItem: {},
        editDialog: false,
        confirmDialog: false,
        deleteConfirmMsg: {},
        headers: [
            {text: 'Item', value: 'item', filterable: true},
            {text: 'Quantity', value: 'quantity'},
            {text: 'Source', value: 'source'},
            {text: 'Occurred On', value: 'occurred_on'},
            {text: 'Held By', value: 'held_by'},
            {text: 'Claimed', value: 'claimed'},
            {text: 'Claimed By', value: 'claimed_by'},
            {text: 'In Bag of Holding', value: 'in_bag_of_holding'},
            // {text: 'Notes', value: 'notes'},
            {text: 'Actions', value: 'actions', sortable: false},
        ],
    }),
    watch: {
        campaignId: function(campaignId) {
            this.$store.dispatch('getInventoryLog', campaignId);
        }
    },
    computed: {
        isLoading(){
            if(!this.campaignId){
                return false
            } else if (this.campaignId && this.$store.state.axiosPending && this.$store.state.inventory.log.length < 1){
                return true
            }
        },
        loadingMsg(){
            return !this.campaignId ? "Select a Campaign to load data" : "No data found for specified campaign"
        }
    },
    methods: {
        columnValueList(val){
            return this.$store.state.inventory.log.map(i => i[val])
        },
        editItem(item){
            this.selectedItem = item
            this.editDialog = true
        },
        deleteConfirm(item){
            this.selectedItem = item
            this.deleteConfirmMsg = {
                title: 'Delete Item?',
                message: `Are you sure you want to delete ${item.item}?`
            }
            this.confirmDialog = true;
        },
        deleteItem(resp){
            this.confirmDialog = false
            console.log(`deleteItem: ${resp}`)
            if(resp){
                this.$store.dispatch('deleteItem', this.selectedItem)
            }
            this.selectedItem = {};
        }
    },
    mounted() {
        if(this.campaignId){
            this.$store.dispatch('getInventoryLog', this.campaignId)
        }
    },
    components: {
        Edit,
        Confirm
    }
}
</script>
