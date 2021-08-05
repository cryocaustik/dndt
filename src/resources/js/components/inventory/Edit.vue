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
                    Edit Item
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
                    <v-row>
                        <v-col>
                            <v-select
                                :items="campaignSelect"
                                v-model="item.campaign_id"
                                label="Campaign"
                                required
                            ></v-select>

                            <v-text-field
                                v-model="item.item"
                                label="Item"
                                required
                            ></v-text-field>

                            <v-text-field
                                v-model="item.source"
                                label="Source"
                                required
                            ></v-text-field>

                            <v-textarea
                                v-model="item.notes"
                                label="Notes"
                                rows="2"
                            ></v-textarea>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="item.quantity"
                                label="Quantity"
                                type="number"
                                required
                            ></v-text-field>

                            <v-menu
                                ref="showDatePicker"
                                v-model="showDatePicker"
                                :close-on-content-click="false"
                                :return-value.sync="item.occurred_on"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="item.occurred_on"
                                        label="Acquired On"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="item.occurred_on"
                                    no-title
                                    scrollable
                                >
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="showDatePicker = false"
                                    >
                                        Cancel
                                    </v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.showDatePicker.save(item.occurred_on)"
                                    >
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>

                            <v-text-field
                                v-model="item.held_by"
                                label="Held By"
                            ></v-text-field>

                            <v-checkbox
                                v-model="item.in_bag_of_holding"
                                label="In Bag of Holding"
                            ></v-checkbox>

                            <v-row>
                                <v-checkbox
                                    v-model="item.claimed"
                                    hide-details
                                    class="shrink ml-3"
                                ></v-checkbox>
                                <v-text-field
                                    v-model="item.claimed_by"
                                    :disabled="!item.claimed"
                                    label="Claimed By"
                                ></v-text-field>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "Edit",
    props: {
        editDialog: { default: false },
        selectedCampaign: { default: null },
        campaignSelect: { default: [] },
        item: {
            default: () => ({
                campaign_id: null,
                item: "",
                source: "",
                notes: "",
                quantity: 1,
                occurred_on: new Date().toISOString().substr(0, 10),
                held_by: "",
                claimed: false,
                claimed_by: "",
                in_bag_of_holding: false,
            })
        }
    },
    data: () => ({
        showDatePicker: false
    }),
    methods: {
        save(){
            let item = this.item;
            this.$store.dispatch('updateItem', item)
            this.$emit('close');
        }
    }
}
</script>
