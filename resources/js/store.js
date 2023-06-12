import Vue from "vue";
import Vuex from "vuex";
import router from "./router";
import Vuetify from "./plugins/vuetify"

Vue.use(Vuex);
const apiBase = `${window.Domain}/api/v1`;

export default new Vuex.Store({
    state: {
        user: null,
        axiosPending: false,
        minimizedApp: false,
        navDrawer: {
            permanentDrawer: true,
            miniDrawer: false,
        },
        inviteUrl: `${window.Domain}/invite`,
        api: {
            auth: {
                login: `${apiBase}/auth/login`,
                logout: `${apiBase}/auth/logout`,
            },
            campaign: {
                list: `${apiBase}/campaign`,
                store: `${apiBase}/campaign/store`,
                update: `${apiBase}/campaign/update`,
                delete: `${apiBase}/campaign/delete`,
            },
            permission: {
                list: `${apiBase}/permission`,
                update: `${apiBase}/permission/update`,
                invite: `${apiBase}/permission/invite`,
                delete: `${apiBase}/permission/delete`,
            },
            invite: {
                list: `${apiBase}/invite`,
                store: `${apiBase}/invite/store`,
                delete: `${apiBase}/invite/delete`,
                token: `${apiBase}/invite`,
            },
            inventory: {
                log: `${apiBase}/inventory/log`,
                summary: `${apiBase}/inventory/summary`,
                currency: `${apiBase}/inventory/currency`,
                store: `${apiBase}/inventory/store`,
                storeMultiple: `${apiBase}/inventory/store_multiple`,
                update: `${apiBase}/inventory/update`,
                delete: `${apiBase}/inventory/delete`,
                import: `${apiBase}/inventory/import`,
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
            invites: [],
            inviteToken: null,
        },
    },
    mutations: {
        setAuthUser(state, user) {
            state.user = user;
        },
        axiosStatus(state, status){
            state.axiosPending = status;
        },
        miniDrawer(state, toggle){
            state.navDrawer.miniDrawer = toggle
        },
        permanentDrawer(state, toggle){
            state.navDrawer.permanentDrawer = toggle
        },
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
        },
        user(state){
            return state.user
        },
        miniDrawer(state){
            return state.navDrawer.miniDrawer
        },
        permanentDrawer(state){
            return state.navDrawer.permanentDrawer
        },
        inventorySummary(state){
            return state.inventory.summary
        },
        inventoryLog(state){
            return state.inventory.log
        },
        inventoryCurrency(state){
            return state.inventory.currency
        },
    },
    actions: {
        // Ayth
        login(context, credentials){
            let url = context.state.api.auth.login
            axios
                .post(url, credentials)
                .then(resp => {
                    location.href = "/"
                })
                .catch(err => {
                    switch (err.response.status){
                        case 422:
                            window.Vue.$vToastify.error(`Invalid Username or Password`, `Login Failed`);
                            return
                        default:
                            window.Vue.$vToastify.error(`Login failed`, err.response.status);
                            console.log(`login failed`)
                            console.log(err)
                    }

                    // window.Vue.$vToastify.error(`Login failed`, err.response.status);
                    // console.log(`login failed`)
                    // console.log(err)
                })
        },
        logout(context){
            let url = context.state.api.auth.logout
            axios
                .post(url)
                .then(() => {
                    context.state.user = null
                    window.authUser = null
                    router.push('/login')
                    // location.href = "/login"
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`logout failed`, err.response.status);
                    console.log(`logout error`)
                    console.log(err)
                })
        },
        register(context, credentials){
            axios
                .post(`${window.Domain}/auth/register`, credentials)
                .then(() => {
                    location.href = "/"
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`registration failed`, err.response.status);
                    console.log(`register error`)
                    console.log(err)
                })
        },

        // Campaign
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
        },
        deleteCampaign(context, campaign){
            axios
                .post(context.state.api.campaign.delete, campaign)
                .then(() => {
                    window.Vue.$vToastify.success(`${campaign.name} has been deleted`, 'Success');
                    context.dispatch('getCampaigns');
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Campaign delete failed for ${campaign.name}`, err.response.status);
                    console.log(`deleteCampaign error: ${JSON.stringify(campaign)}`)
                    console.log(err)
                })
        },

        // Permission
        getPermission(context){
            axios
                .get(context.state.api.permission.list)
                .then(resp => {
                    context.state.campaign.permissions = resp.data
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Permission refresh failed`, err.response.status);
                    console.log(`getPermission error`)
                    console.log(err)
                })
        },
        updatePermission(context, permission){
            axios
                .put(context.state.api.permission.update, permission)
                .then(() => {
                    window.Vue.$vToastify.success(`Permission has been updated`, 'Success');
                    context.dispatch('getPermission')
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Permission edit failed`, err.response.status);
                    console.log(`updatePermission error: ${JSON.stringify(permission)}`)
                    console.log(err)
                })
        },
        deletePermission(context, permission){
            axios
                .post(context.state.api.permission.delete, permission)
                .then(() => {
                    window.Vue.$vToastify.success(`Permission for ${permission.campaign.name} has been deleted`, 'Success');
                    context.dispatch('getPermission')
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Permission delete failed for ${permission.campaign.name}`, err.response.status);
                    console.log(`deletePermission error: ${JSON.stringify(permission)}`)
                    console.log(err)
                })
        },

        // Invite
        getInvites(context){
            let url = context.state.api.invite.list;
            axios
                .get(url)
                .then(resp => {
                    context.state.campaign.invites = resp.data
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Invites refresh failed`, err.response.status);
                    console.log(`getInvites error: ${url}`)
                    console.log(err)
                })
        },
        storeInvite(context, invite){
            let url = context.state.api.invite.store
            axios
                .post(url, invite)
                .then(resp => {
                    context.state.campaign.inviteToken = `${context.state.inviteUrl}/${resp.data.token}`
                    context.dispatch('getInvites')
                    window.Vue.$vToastify.success(`Invite has been created`, 'Success')
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Invite creation failed`, err.response.status);
                    console.log(`storeInvite error: ${JSON.stringify(invite)}`)
                    console.log(err)
                })
        },
        deleteInvite(context, invite){
            let url = context.state.api.invite.delete;
            axios
                .post(url, invite)
                .then(() => {
                    context.dispatch('getInvites')
                    window.Vue.$vToastify.success(`Invite has been deleted`, 'Success')
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Invite delete failed`, err.response.status);
                    console.log(`deleteInvite error: ${JSON.stringify(invite)}`)
                    console.log(err)
                })
        },

        // Inventory
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
                .then(() => {
                    context.commit('axiosStatus', false);
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Inventory Currency Refresh failed`, err.response.status);
                    console.log(`getInventoryCurrency error: ${campaign_id}`)
                    console.log(err)
                })
        },
        getInventory(context, campaign_id){
            context.dispatch('getInventorySummary', campaign_id)
            context.dispatch('getInventoryLog', campaign_id)
            context.dispatch('getInventoryCurrency', campaign_id)
        },
        storeInventory(context, item){
            axios
                .post(context.state.api.inventory.store, item)
                .then(() => {
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
        storeInventoryMultiple(context, items){
            let campaign_id = items[0].campaign_id;
            axios
                .post(context.state.api.inventory.storeMultiple, items)
                .then(() => {
                    window.Vue.$vToastify.success(`Items created successfully!`, 'Success');
                    context.dispatch('getInventorySummary', campaign_id)
                    context.dispatch('getInventoryLog', campaign_id)
                    context.dispatch('getInventoryCurrency', campaign_id)
                })
                .catch(err => {
                    window.Vue.$vToastify.error(`Item create failed`, err.response.status);
                    console.log(`storeInventoryMulti error: ${JSON.stringify(items)}`)
                    console.log(err.response.data)
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
        importInventory(context, payload){
            axios
                .post(
                    context.state.api.inventory.import,
                    payload,
                    {
                        headers: {
                            'accept': 'application/json',
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(resp => {
                    let errors = resp.data.importErrors
                    if(errors.length > 0){
                        let errLen = errors.length
                        let successLen = resp.data.imported
                        console.log(`Import errors:`)
                        console.log(errors)
                        window.Vue.$vToastify.warning(
                            `${successLen} records imported, ${errLen} failed; see browser console (f12) for more info.`,
                            'Success'
                        );
                    } else {
                        window.Vue.$vToastify.success(`File successfully imported`, 'Success');
                    }
                    context.dispatch('getInventory', payload.get('campaign_id'))
                })
                .catch(err => {
                    console.log(`File import failed`)
                    console.log(err)
                    window.Vue.$vToastify.error(`File import failed`, err.response.status);
                })
        }



    }
});
