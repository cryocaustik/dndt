<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.campaign.invites"
            :search="search"
            :no-data-text="noDataText"
            sort-by="campaign"
        >

            <template v-slot:item.expires_at="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <span
                            :class="isExpired(item.expires_at) ? 'red--text' : ''"
                            v-bind="attrs"
                            v-on="on"
                        >
                            {{ humanizeDate(item.expires_at) }}
                        </span>
                    </template>
                    <span>{{ localDate(item.expires_at) }}</span>
                </v-tooltip>
            </template>

            <template v-slot:item.user="{ item }">
                <slot v-if="item.user.name">{{ item.user.name }}</slot>
                <slot v-else>{{ item.user.username }}</slot>
            </template>

            <template v-slot:item.url="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            tile
                            icon
                            @click.stop="copyUrl(item)"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                small
                                color="grey"
                            >
                                mdi-content-copy
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Copy Link</span>
                </v-tooltip>

            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    color="red"
                    @click.stop="deleteConfirm(item)"
                >
                    mdi-delete
                </v-icon>
            </template>

            <v-tooltip bottom>
                <template
                    v-slot:activator="{ on, attrs }"
                    v-slot:item.actions="{ item }"
                >
                    <v-btn
                        tile
                        icon
                        @click="deleteConfirm(item)"
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
                <span>Delete Permission</span>
            </v-tooltip>

        </v-data-table>

        <Confirm
            :message="deleteConfirmMsg"
            :toggle="confirmDialog"
            v-on:confirm="deleteInvite"
        />
    </v-card>
</template>
<script>
import Confirm from "../includes/Confirm";
import moment from "moment"

export default {
    name: "Invites",
    props: [
        'search'
    ],
    data: () => ({
        selectedInvite: {},
        confirmDialog: false,
        deleteConfirmMsg: {
            title: 'Delete Invite?',
            message: `Are you sure you want to delete this invite?`
        },
        noDataText: `No invites found (only showing invites you created or for campaigns where you're an owner or administrator)`,
        headers: [
            {text: 'Campaign', value: 'campaign.name'},
            {text: 'User', value: 'user'},
            {text: 'Permission', value: 'permission'},
            {text: 'Expires', value: 'expires_at'},
            {text: 'URL', value: 'url'},
            {text: 'Actions', value: 'actions', sortable: false},
        ],
    }),
    computed: {
        inviteUrl(){
            return this.$store.state.inviteUrl
        }
    },
    methods: {
        isExpired(dt){
            return moment.utc() > moment.utc(dt)
        },
        humanizeDate(dt){
            return moment.duration(moment.utc(dt).diff(moment.utc())).humanize(true)
        },
        localDate(dt, format = "YYYY-MM-DD hh:mm A"){
            return moment.utc(dt).local().format(format)
        },
        copyUrl(invite){
            let url = `${this.inviteUrl}/${invite.token}`
            navigator.clipboard.writeText(url)
        },
        deleteConfirm(invite){
            this.selectedInvite = invite
            this.confirmDialog = true;
        },
        deleteInvite(resp){
            this.confirmDialog = false
            if(resp){
                this.$store.dispatch('deleteInvite', this.selectedInvite)
            }
            this.selectedInvite = {};
        }
    },
    mounted() {
        this.$store.dispatch('getInvites')
    },
    components: {
        Confirm
    }
}
</script>
