import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
const apiBase = `${window.Domain}/api/v1`;

export default new Vuex.Store({
    state: {
        user: null,
        axiosPending: false,
        messages: [],
        errors: [],
        api: {
            campaign: {
                list: `${apiBase}/campaign`,
                store: `${apiBase}/campaign/store`,
                update: `${apiBase}/campaign/update`,
            },
            inventory: {
                log: `${apiBase}/inventory/log`,
                summary: `${apiBase}/inventory/summary`,
                currency: `${apiBase}/inventory/currency`,
                store: `${apiBase}/inventory/store`,
                update: `${apiBase}/inventory/update`,
                delete: `${apiBase}/inventory/delete`,
            },
        },
        inventory: {
            log: [],
            summary: [],
            currency: []
        },
        campaign: {
            list: [],
            permissions: [],
        }
    },
    mutations: {
        setAuthUser(state, user) {
            state.user = user;
        },
        axiosStatus(state, status){
            state.axiosPending = status;
        }
    },
    getters: {
        isLoggedIn(state) {
            return state.user !== null;
        },
        getMessages(state){
            return state.messages
        },
        getErrors(state){
            return state.errors
        }
    },
    actions: {
        getCampaigns(context){
            context.commit('axiosStatus', true);
            let url = context.state.api.campaign.list;
            axios
                .get(url)
                .then(resp => {
                    context.state.campaign.list = resp.data;
                })
                .then(() => {
                    context.commit('axiosStatus', false);
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Get Campaigns failed`, err.response.status);
                    console.log(`getCampaigns error: ${url}`)
                    console.log(err)
                })
        },
        getInventorySummary(context, campaign_id){
            context.commit('axiosStatus', true);
            let url = context.state.api.inventory.summary + `/${campaign_id}`;
            axios
                .get(url)
                .then(resp => {
                    context.state.inventory.summary = resp.data;
                })
                .then(() => {
                    context.commit('axiosStatus', false);
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Inventory Summary Refresh failed`, err.response.status);
                    console.log(`getInventorySummary error: ${campaign_id}`)
                    console.log(err)
                })
        },
        getInventoryLog(context, campaign_id) {
            context.commit('axiosStatus', true);
            let url = context.state.api.inventory.log + `/${campaign_id}`;
            axios
                .get(url)
                .then(resp => {
                    context.state.inventory.log = resp.data;
                })
                .then(() => {
                    context.commit('axiosStatus', false);
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Inventory Log Refresh failed`, err.response.status);
                    console.log(`getInventoryLog error: ${campaign_id}`)
                    console.log(err)
                })
        },
        getInventoryCurrency(context, campaign_id){
            context.commit('axiosStatus', true);
            let url = context.state.api.inventory.currency + `/${campaign_id}`;
            axios
                .get(url)
                .then(resp => {
                    context.state.inventory.currency = resp.data;
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Inventory Currency Refresh failed`, err.response.status);
                    console.log(`getInventoryCurrency error: ${campaign_id}`)
                    console.log(err)
                })
        },
        storeInventory(context, item){
            axios
                .post(context.state.api.inventory.store, item)
                .then(resp => {
                    window.Vue.$vToastify.success(`Item created successfully!`, 'Success');
                    context.dispatch('getInventorySummary', item.campaign_id)
                    context.dispatch('getInventoryLog', item.campaign_id)
                    context.dispatch('getInventoryCurrency', item.campaign_id)
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Item create failed for ${item.item}`, err.response.status);
                    console.log(`storeInventory error: ${JSON.stringify(item)}`)
                    console.log(err)
                })
        },
        updateItem(context, item){
            axios
                .put(context.state.api.inventory.update, item)
                .then(() => {
                    window.Vue.$vToastify.success(`${item.item} has been updated`, 'Success');
                    context.dispatch('getInventorySummary', item.campaign_id)
                    context.dispatch('getInventoryLog', item.campaign_id)
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Item edit failed ${item.item}`, err.response.status);
                    console.log(`updateItem error: ${JSON.stringify(item)}`)
                    console.log(err)
                })
        },
        deleteItem(context, item){
            axios
                .post(context.state.api.inventory.delete, item)
                .then(() => {
                    window.Vue.$vToastify.success(`${item.id}-${item.item} has been deleted`, 'Success');
                    context.dispatch('getInventorySummary', item.campaign_id)
                    context.dispatch('getInventoryLog', item.campaign_id)
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Item delete failed for ${item.item}`, err.response.status);
                    console.log(`deleteItem error: ${JSON.stringify(item)}`)
                    console.log(err)
                })
        },

        storeCampaign(context, campaign){
            axios
                .post(context.state.api.campaign.store, campaign)
                .then(() => {
                    window.Vue.$vToastify.success(`${campaign.name} has been created`, 'Success');
                    context.dispatch('getCampaigns');
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Campaign create failed for ${campaign.name}`, err.response.status);
                    console.log(`storeCampaign error: ${JSON.stringify(campaign)}`)
                    console.log(err)
                })
        },

        updateCampaign(context, campaign){
            axios
                .put(context.state.api.campaign.update, campaign)
                .then(() => {
                    window.Vue.$vToastify.success(`${campaign.name} has been updated`, 'Success');
                    context.dispatch('getCampaigns');
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Campaign update failed for ${campaign.name}`, err.response.status);
                    console.log(`updateCampaign error: ${JSON.stringify(campaign)}`)
                    console.log(err)
                })
        }
    }
});
