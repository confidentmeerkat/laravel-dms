<template>
  <common-base-frame>
    <template #main>
      <v-main>
        <v-container>
          <common-icon-head-line
            :icon="'mdi-web'"
            :headline-text="'DNS'"
          ></common-icon-head-line>
          <div class="py-5"></div>
          <common-greeting-message
            :type="greetingType"
            :message="greeting"
          ></common-greeting-message>
          <v-progress-linear
            v-show="pageLoading"
            color="yellow darken-2"
            indeterminate
            rounded
            height="6"
          ></v-progress-linear>
          <div v-for="domain in dns" :key="domain.id" class="mb-4">
            <v-card>
              <v-card-title>{{ domain.name }}</v-card-title>
              <v-btn
                v-if="canStore"
                class="ma-2"
                color="primary"
                small
                @click="openNewDialog(domain)"
              >
                <v-icon dark left> mdi-plus-circle </v-icon>New
              </v-btn>
              <dns-list-table :subdomains="domain.subdomains"></dns-list-table>
            </v-card>
          </div>
          <dns-new-dialog
            :is-open="isOpenNewDialog"
            @close="closeNewDialog"
          ></dns-new-dialog>
        </v-container>
      </v-main>
    </template>
  </common-base-frame>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'

export default {
  name: 'DnsPage',
  data() {
    return {
      subdomain: {},
      isOpenNewDialog: false,
    }
  },
  computed: {
    ...mapGetters('dns', [
      'dns',
      'canStore',
      'pageLoading',
      'greeting',
      'greetingType',
    ]),
  },
  created() {
    this.fetchDns()
    this.fetchDomains()
    this.fetchDnsRecordTypes()
    this.initRole()
  },
  methods: {
    ...mapMutations('dns', ['domainId']),
    ...mapActions('dns', ['fetchDns', 'fetchDnsRecordTypes', 'initRole']),
    ...mapActions('domain', ['fetchDomains']),
    openNewDialog(domain) {
      this.domainId(domain.id)
      this.isOpenNewDialog = true
    },
    closeNewDialog() {
      this.isOpenNewDialog = false
    },
  },
}
</script>