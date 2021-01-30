<template>
    <v-dialog
        v-model="addNewDialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>
                    Create New Invite
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn
                        dark
                        text
                        @click="close"
                        v-if="!token"
                    >
                        Cancel
                    </v-btn>

                    <v-btn
                        text
                        color="success"
                        @click="save"
                        v-if="!token"
                    >
                        Create
                    </v-btn>
                    <v-btn
                        text
                        color="success"
                        @click="close"
                        v-else
                    >
                        Close
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-select
                        :items="campaignSelect"
                        v-model="invite.campaign_id"
                        label="Campaign"
                        :disabled="createFieldsDisabled"
                        required
                    ></v-select>

                    <v-select
                        :items="permissionSelect"
                        v-model="invite.permission"
                        label="Permission"
                        :disabled="createFieldsDisabled"
                        required
                    ></v-select>

                    <v-select
                        :items="expiresSelect"
                        v-model="invite.expires_at"
                        label="Expires in"
                        :disabled="createFieldsDisabled"
                        required
                    ></v-select>

                    <v-text-field
                        :value="token"
                        label="Invite URL"
                        :disabled="tokenDisabled"
                    ></v-text-field>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "InviteAddNew",
    props: [
        'addNewDialog'
    ],
    data: () => ({
        invite: {
            campaign_id: "",
            permission: "",
            expires_at: ""
        },
        defaultInvite: {
            campaign_id: "",
            permission: "",
            expires_at: ""
        },
        permissionSelect: [
            { text: "Owner", value: "owner", disabled: true },
            { text: "Administrator", value: "administrator" },
            { text: "Contributor", value: "contributor" },
            { text: "Reader", value: "reader" },
        ],
        expiresSelect: [
            { text: '5 minute', value: '5m' },
            { text: '30 minutes', value: '30m' },
            { text: '1 hour', value: '1h' },
            { text: '1 day', value: '1d' },
            { text: '7 days', value: '7d' },
            { text: '30d', value: '30d' },
        ]
    }),
    computed: {
        campaignSelect(){
            let campaigns = this.$store.state.campaign.list;
            return campaigns ? campaigns.map(c => ({value: c.id, text: c.name})) : [];
        },
        token(){
            let token = this.$store.state.campaign.inviteToken
            return token ? token : null
        },
        tokenDisabled(){
            return this.token ? false : true
        },
        createFieldsDisabled(){
            return this.token ? true : false
        }
    },
    methods: {
        save(){
            let invite = this.invite;
            this.$store.dispatch('storeInvite', invite)
        },
        close(){
            this.invite = this.defaultInvite
            this.$store.state.campaign.inviteToken = ""
            this.$emit('close')
        }
    },
    mounted() {
        if(!this.$store.state.campaign.list){
            this.$store.dispatch('getCampaigns')
        }
    }
}
</script>
