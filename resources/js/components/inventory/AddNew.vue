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
                    Add New Items to Inventory
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
                                v-model="selectedCampaign"
                                label="Campaign"
                                required
                            ></v-select>

                            <v-text-field
                                v-model="source"
                                label="Source"
                                required
                            ></v-text-field>

                        </v-col>
                        <v-col>
                            <v-menu
                                ref="showDatePicker"
                                v-model="showDatePicker"
                                :close-on-content-click="false"
                                :return-value.sync="occurred_on"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="occurred_on"
                                        label="Acquired On"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="occurred_on"
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
                                        @click="$refs.showDatePicker.save(occurred_on)"
                                    >
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>

                            <v-textarea
                                v-model="notes"
                                label="Notes"
                                rows="2"
                            ></v-textarea>
                        </v-col>
                    </v-row>

                    <v-card class="elevation-6">
                        <v-toolbar color="red accent-4" class="mb-2">
                            <v-toolbar-title>
                                Items
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn
                                    text
                                    color="success"
                                    @click="items.push({...itemTemplate})"
                                >
                                    <v-icon>mdi-plus</v-icon>
                                    Add Item
                                </v-btn>
                            </v-toolbar-items>
                        </v-toolbar>
                        <v-card-text
                            id="items"
                            style="max-height: 70vh; overflow-y: scroll;"
                        >
                            <v-row
                                v-for="(item, i) in items"
                                :key="i"
                                :id="'item-' + i"
                                :style="i < items.length - 1? 'border-bottom: 1px solid darkred' : ''"
                            >
                                <v-col cols="4">
                                    <v-text-field
                                        label="Item"
                                        v-model="item.item"
                                        @keydown.enter="addItem"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="1">
                                    <v-text-field
                                        v-model="item.quantity"
                                        label="Quantity"
                                        type="number"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field
                                        v-model="item.held_by"
                                        label="Held By"
                                    ></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-checkbox
                                        v-model="item.in_bag_of_holding"
                                        label="In Bag of Holding"
                                    ></v-checkbox>
                                </v-col>
                                <v-col>
                                    <v-row class="my-auto">
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
                                <v-col cols="auto my-auto">
                                    <v-btn
                                        icon
                                        tile
                                        @click="items.splice(i, 1)"
                                    >
                                            <v-icon
                                                small
                                                color="red"
                                            >
                                                mdi-delete
                                            </v-icon>
                                        </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "InventoryAddNew",
    props: {
        addNewDialog: { default: false },
        selectedCampaign: { default: null },
        campaignSelect: { default: [] }
    },
    data: () => ({
        showDatePicker: false,
        source: "",
        notes: "",
        occurred_on: new Date().toISOString().substr(0, 10),
        itemTemplate: {
            item: "",
            quantity: 1,
            held_by: "",
            claimed: false,
            claimed_by: "",
            in_bag_of_holding: false,
        },
        items: [
            {
                item: "",
                quantity: 1,
                held_by: "",
                claimed: false,
                claimed_by: "",
                in_bag_of_holding: false,
            },
        ],
    }),
    computed: {
        mappedItems() {
            let core = {
                campaign_id: this.selectedCampaign,
                notes: this.notes,
                source: this.source,
                occurred_on: this.occurred_on,
            };
            return this.items.map((item) => ({
                ...item,
                ...core,
            }));
        }
    },
    methods: {
        addItem(){
            this.items.push({...this.itemTemplate});
            setTimeout(() => {
                let el = document.querySelector('[id^="item-"]:last-child')
                el.querySelector('input').focus()
            }, 100);
        },
        resetForm(){
            this.items = [this.itemTemplate];
            this.source = "";
            this.notes = "";
            this.occurred_on = new Date().toISOString().substr(0, 10);
        },
        save(){
            const items = this.mappedItems;
            this.$store.dispatch('storeInventoryMultiple', items);
            this.resetForm();
            this.$emit('close');
        }
    },
}
</script>
