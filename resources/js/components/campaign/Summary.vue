<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.campaign.list"
            :search="search"
        >
            <template v-slot:item.owner="{ item }">
                <slot v-if="item.owner && item.owner.name">{{ item.owner.name }}</slot>
                <slot v-else-if="item.owner && item.owner.username">{{ item.owner.username }}</slot>
            </template>

            <template v-slot:item.active="{ item }">
                <v-icon v-if="item.active" small color="success">mdi-check</v-icon>
                <v-icon v-else color="secondary" small>mdi-close</v-icon>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            tile
                            icon
                            @click.stop="editCampaign(item)"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                small
                                color="orange"
                            >
                                mdi-pencil
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Edit Campaign</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            tile
                            icon
                            :to="{ name: 'permission' }"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                small
                                color="blue"
                            >
                                mdi-account-cog
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Edit Permissions</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            tile
                            icon
                            @click.stop="deleteConfirm(item)"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                small
                                color="red"
                            >
                                mdi-delete
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Delete Campaign</span>
                </v-tooltip>
            </template>
        </v-data-table>

        <Edit
            :editDialog="editDialog"
            :campaign="selectedCampaign"
            v-on:close="editDialog = false"
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
    name: "Summary",
    props: [
        'search'
    ],
    data: () => ({
        editDialog: false,
        selectedCampaign: {},
        headers: [
            {text: 'Name', value: 'name'},
            {text: 'DM', value: 'dm'},
            {text: 'Owner', value: 'owner'},
            {text: 'Active', value: 'active'},
            {text: 'Actions', value: 'actions', sortable: false },
        ],
        confirmDialog: false,
        deleteConfirmMsg: {
            title: 'Delete Campaign?',
            message: [
                `Are you sure you want to delete this campaign?`,
                `This will also delete any permissions and ALL related inventories, notes, etc., for EVERYONE.`,
            ]
        },
    }),
    computed: {
        owner(){

        }
    },
    methods: {
        editCampaign(campaign){
            this.selectedCampaign = campaign
            this.editDialog = true
        },
        deleteConfirm(campaign){
            this.selectedCampaign = campaign
            this.confirmDialog = true
        },
        deleteCampaign(resp){
            this.confirmDialog = false
            if(resp){
                this.$store.dispatch('deleteCampaign', this.selectedCampaign)
            }
            this.selectedCampaign = {}
        }
    },
    components: {
        Edit,
        Confirm
    },
    mounted(){
        if(!this.$store.state.campaigns){
            this.$store.dispatch('getCampaigns');
        }
    }
}
</script>
