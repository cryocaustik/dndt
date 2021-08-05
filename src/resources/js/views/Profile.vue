<template>
  <v-container fluid id="profile">
    <v-row justify="center">
      <v-col :md="6">

        <v-card class="mb-1">
          <v-list-item two-line>
            <template>
              <v-hover v-slot="{ hover }">
                <v-list-item-avatar tile size="80" color="grey">
                  <v-img :src="avatar">
                    <v-expand-transition>
                      <div v-if="hover" class="red darken d-flex transition-fast-in-fast-out mx-auto text--white" id="avatar-change">change</div>
                    </v-expand-transition>
                  </v-img>
                </v-list-item-avatar>
              </v-hover>
            </template>
            <v-list-item-content>
              <v-list-item-title class="text-h4 mb-1">
                {{ user.name ? user.name : user.username }}
              </v-list-item-title>
              <v-list-item-subtitle>
                joined: {{ user.created_at.toLocaleString() }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        
        <v-card class="mb-1">
          <v-list-item two-line>
            <v-list-item-avatar tile size="60" color="grey">
              <v-icon>mdi-email</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title class="text-h6 mb-1">
                Email
              </v-list-item-title>
              <v-list-item-subtitle>
                {{ user.email }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>
        
        <v-card class="mb-1" v-for="acct in user.accounts" :key="acct.id">
          <v-list-item two-line>
            <v-list-item-avatar tile size="60" color="grey">
              <v-icon>mdi-{{ acct.provider_name }}</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title class="text-h6 mb-1">
                {{ acct.provider_name }}
              </v-list-item-title>
              <v-list-item-subtitle>
                {{ user.email }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-card>

      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: "Profile",
  data: () => ({ }),
  computed: {
    user() {
      return this.$store.getters.user;
    },
    avatar(){
      let user = this.user;
      if (user.avatar || user.avatar_url){
        return user.avatar ?? user.avatar_url;
      }
      
      return `https://ui-avatars.com/api/?name=${user.username}`
    }
  },
  methods: {},
};
</script>

<style>
#avatar-change{
  align-items: center;
  cursor: pointer;
  bottom: 0;
  justify-content: center;
  opacity: .5;
  position: absolute;
  width: 100%;
  height: 100%;
}
</style>