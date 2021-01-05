<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.campaign.list"
            :search="search"
        >
            <template v-slot:item.owner="{ item }">
                <slot v-if="item.owner.name">{{ item.owner.name }}</slot>
                <slot v-else>{{ item.owner.username }}</slot>
            </template>

            <template v-slot:item.active="{ item }">
                <v-icon v-if="item.active" small color="success">mdi-check</v-icon>
                <v-icon v-else color="secondary" small>mdi-close</v-icon>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click.stop="edit(item)"
                    color="orange"
                >
                    mdi-pencil
                </v-icon>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="$emit('view', item.name)"
                            color="primary"
                            v-bind="attrs"
                            v-on="on"
                        >
                            mdi-account-cog
                        </v-icon>
                    </template>
                    <span>Edit Permissions</span>
                </v-tooltip>
            </template>
        </v-data-table>

        <EditCampaign
            :editDialog="editDialog"
            :campaign="selectedCampaign"
            v-on:close="editDialog = false"
        />
    </v-card>
</template>
<script>
import EditCampaign from "./EditCampaign";

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
    }),
    methods: {
        edit(campaign){
            this.selectedCampaign = campaign
            this.editDialog = true
        }
    },
    components: {
        EditCampaign
    },
    mounted(){
        if(!this.$store.state.campaigns){
            this.$store.dispatch('getCampaigns');
        }
    }
}
</script>
