<template>
    <v-dialog
        v-model="editDialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <v-card>
            <v-toolbar color="red darken-4" class="mb-2">
                <v-toolbar-title>
                    Edit Permission
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn
                        dark
                        text
                        @click="$emit('close')"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        text
                        color="success"
                        @click="save"
                    >
                        Save
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-text-field
                        :value="campaign"
                        label="Campaign"
                        disabled
                    ></v-text-field>

                    <v-text-field
                        :value="user"
                        label="User"
                        disabled
                    ></v-text-field>

                    <v-select
                        :items="permissionSelect"
                        v-model="permission.permission"
                        label="Permission"
                    ></v-select>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "Edit",
    props: {
        editDialog: {
            default: false
        },
        permission: {
            default: () => ({
                campaign: {
                    name: ""
                },
                user: {
                    name: "",
                    username: ""
                },
                permission: ""
            })
        }
    },
    data: () => ({
        permissionSelect: [
            { text: "Owner", value: "owner", disabled: true },
            { text: "Administrator", value: "administrator" },
            { text: "Contributor", value: "contributor" },
            { text: "Reader", value: "reader" },
        ]
    }),
    computed: {
        campaign(){
            if(!this.permission.campaign){
                return ""
            }
            return this.permission.campaign.name
        },
        user(){
            if(!this.permission.user){
                return ""
            }
            return this.permission.user.name ? this.permission.user.name : this.permission.user.username
        }
    },
    methods: {
        save(){
            let permission = this.permission;
            this.$store.dispatch('updatePermission', permission)
            this.$emit('close');
        }
    }
}
</script>
