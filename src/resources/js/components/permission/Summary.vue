<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="this.$store.state.campaign.permissions"
            :search="search"
            sort-by="campaign"
        >
            <template v-slot:item.user="{ item }">
                <slot v-if="item.user.name">{{ item.user.name }}</slot>
                <slot v-else>{{ item.user.username }}</slot>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click.stop="editPermission(item)"
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
            :permission="selectedPermission"
            v-on:close="editDialog = false; selectedPermission = {}"
        />

        <Confirm
            :message="deleteConfirmMsg"
            :toggle="confirmDialog"
            v-on:confirm="deletePermission"
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
        selectedPermission: {},
        editDialog: false,
        confirmDialog: false,
        deleteConfirmMsg: {},
        headers: [
            {text: 'Campaign', value: 'campaign.name'},
            {text: 'User', value: 'user'},
            {text: 'Permission', value: 'permission'},
            {text: 'Actions', value: 'actions', sortable: false},
        ],
    }),
    methods: {
        editPermission(permission){
            this.selectedPermission = permission
            this.editDialog = true
        },
        deleteConfirm(permission){
            this.selectedPermission = permission
            this.deleteConfirmMsg = {
                title: 'Delete Permission?',
                message: `Are you sure you want to delete this permission?`
            }
            this.confirmDialog = true;
        },
        deletePermission(resp){
            this.confirmDialog = false
            if(resp){
                this.$store.dispatch('deletePermission', this.selectedPermission)
            }
            this.selectedPermission = {};
        }
    },
    mounted() {
        this.$store.dispatch('getPermission')
    },
    components: {
        Edit,
        Confirm
    }
}
</script>
