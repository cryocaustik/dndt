<template>
    <v-card v-if="showFilters">
        <v-card-title>Filters</v-card-title>
        <v-card-text>
            <v-row>
                <v-col>
                    <v-text-field
                        v-model="filters.item"
                        label="Item"
                    ></v-text-field>
                </v-col>
                <v-col>
                    <v-text-field
                        v-model="filters.source"
                        label="Source"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        v-model="filters.quantity"
                        label="Quantity"
                        type="number"
                    ></v-text-field>
                </v-col>
                <v-col>
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :return-value.sync="filters.occurred_on"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="filters.occurred_on"
                                label="Occurred On"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="filters.occurred_on"
                            no-title
                            scrollable
                        >
                            <v-spacer></v-spacer>
                            <v-btn
                                text
                                color="primary"
                                @click="menu = false"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                text
                                color="primary"
                                @click="$refs.menu.save(filters.occurred_on)"
                            >
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        v-model="filters.held_by"
                        label="Held By"
                    ></v-text-field>
                </v-col>
                <v-col>
                    <v-text-field
                        v-model="filters.notes"
                        label="Notes"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
<script>
export default {
    name: "ItemFilter",
    props: {
        showFilters: { default: false },
        filters: {
            default: {
                item: "",
                notes: "",
                source: "",
                quantity: "",
                occurred_on: "",
                held_by: "",
                claimed: "",
                claimed_by: "",
                in_bag_of_holding: "",
            }
        }
    },
    data: () => ({
        menu: false
        // filters: {
        //     item: "",
        //     notes: "",
        //     quantity: "",
        //     occurred_on: "",
        //     held_by: "",
        //     claimed: "",
        //     claimed_by: "",
        //     in_bag_of_holding: "",
        // }
    })
}
</script>
