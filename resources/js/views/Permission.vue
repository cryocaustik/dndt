<template>
    <v-container fluid>
        <v-toolbar class="mb-2">
            <v-toolbar-title class="mr-2">Campaign Permissions</v-toolbar-title>
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
                color="primary"
                @click="refresh"
            >
                <v-icon>mdi-refresh</v-icon>
            </v-btn>

            <v-spacer></v-spacer>
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

        <v-tabs
            v-model="tab"
            color="red"
        >
            <v-tab key="permissions" style="width: 50%">
                Permissions
            </v-tab>
            <v-tab key="invites" style="width: 50%">
                Invites
            </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item key="permissions">
                <Summary
                    :search="search"
                />
            </v-tab-item>
            <v-tab-item key="invites">
                <Invite
                    :search="search"
                />
            </v-tab-item>
        </v-tabs-items>

        <AddNew
            :addNewDialog="addNewDialog"
            v-on:close="addNewDialog = false"
        />
    </v-container>
</template>
<script>
import Summary from "../components/permission/Summary";
import Invite from "../components/invite/Invite";
import AddNew from "../components/invite/AddNew";

export default {
    name: "CampaignPermission",
    data: () => ({
        tab: null,
        addNewDialog: false,
        search: "",
        selectedCampaign: {}
    }),
    methods: {
        refresh(){
            if(this.tab === 0){
                this.$store.dispatch('getPermission')
            } else if(this.tab === 1){
                this.$store.dispatch('getInvites')
            } else {
                console.log(`not sure which tab to refresh; refreshing all`)
                this.$store.dispatch('getPermission')
                this.$store.dispatch('getInvites')
            }
        }
    },
    components: {
        Summary,
        Invite,
        AddNew
    },
    mounted(){
        if(!this.$store.state.campaigns){
            this.$store.dispatch('getCampaigns');
        }
    }
}
</script>
