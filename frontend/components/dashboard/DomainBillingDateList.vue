<template>
  <div>
    <div v-if="loading" style="text-align: center">
      <v-sheet color="grey lighten-4" class="pa-3">
        <v-progress-circular
          indeterminate
          color="#2EBFAF"
        ></v-progress-circular>
      </v-sheet>
    </div>
    <div v-else>
      <v-card class="pa-5">
        <v-card-title class="light-blue--text text--darken-2">
          <v-avatar class="mr-3 light-blue darken-2">
            <v-icon dark>mdi-cash-multiple</v-icon>
          </v-avatar>
          Billing Soon Dealing List</v-card-title
        >
        <v-simple-table>
          <thead>
            <tr>
              <th class="text-left">Domain Name</th>
              <th class="text-left">Billing Date</th>
              <th class="text-left">Price</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="billing in soonBillings" :key="billing.id">
              <td>{{ billing.domain_name }}</td>
              <td>{{ $dateHyphen(billing.billing_date) }}</td>
              <td>{{ $formattedPriceYen(billing.total) }}</td>
            </tr>
          </tbody>
        </v-simple-table>
      </v-card>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'DomainBillingDateList',
  data() {
    return {
      loading: true,
    }
  },
  computed: {
    ...mapGetters('dashboard/dealings', ['soonBillings']),
  },
  async created() {
    this.loading = true
    await this.fetchSortedBillingsByCount(5)
    this.loading = false
  },
  methods: {
    ...mapActions('dashboard/dealings', ['fetchSortedBillingsByCount']),
  },
}
</script>
