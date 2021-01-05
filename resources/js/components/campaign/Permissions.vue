<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.campaign.permissions"
            :search="search"
            :loading="isLoading"
            sort-by="occurred_on"
            sort-desc
        >
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
            :editDialog="editDialog"
            :campaign="selectedCampaign"
            v-on:close="editDialog = false; selectedCampaign = {}"
        />

        <Confirm
            :message="deleteConfirmMsg"
            :toggle="confirmDialog"
            v-on:confirm="deleteCampaign"
        />
    </v-card>
</template>
<script>
import Edit from "./Edit";
import Confirm from "../includes/Confirm";

export default {
    name: "CampaignPermissions",
    props: [
        'search'
    ],
    data: () => ({
        selectedPermission: {},
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
    computed: {
        isLoading(){
            if(!this.campaignId){
                return false
            } else if (this.campaignId && this.$store.state.axiosPending && this.$store.state.campaign.permissions.length < 1){
                return true
            }
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
        deleteConfirm(permission){
            this.selectedItem = item
            this.deleteConfirmMsg = {
                title: 'Delete Permission?',
                message: `Are you sure you want to delete this permission?`
            }
            this.confirmDialog = true;
        },
        deleteCampaign(resp){
            this.confirmDialog = false
            if(resp){
                this.$store.dispatch('deleteCampaign', this.selectedItem)
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
