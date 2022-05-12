<template>
  <div class="grid p-fluid">
    <Toast position="bottom-right" />

    <div class="col-12 lg:col-12">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">Trades history</span>
            <div class="text-900 font-medium text-xl">
              Erase your trade history
            </div>
          </div>
          <div class="flex align-items-center justify-content-center" >
            <Button :disabled="stats.total_orders <= 0" class="p-button-danger" label="Submit" icon="fa-regular fa-trash-can-xmark" iconPos="right" @click="clearRecords" />
          </div>
        </div>
        <span class="text-orange-600 font-italic">
          You have <span class="font-bold">{{ stats.total_orders }}</span> trades
        </span>
      </div>

      <div class="card mt-3">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">Import</span>
            <div class="text-900 font-medium text-xl">
              Import/Re-upload your trading report
            </div>
          </div>
          <div class="flex align-items-center justify-content-center" >
            <Button class="p-button-success" label="Import" icon="fa-regular fa-upload" icon-pos="right" @click="$router.push('import')" />
          </div>
        </div>
      </div>
    </div>

  </div>

</template>

<script>
export default {
  name: "Settings",
  data() {
    return {
      stats: [],
    }
  },
  methods: {
    clearRecords() {
      let self = this;

      this.axios.get('/api/settings/clear-history')
          .then(function (response) {
            self.showMessage('error', 'Success', 'Your history (' + response.data.message +') was erased')
            self.getOverallStats()
          })
    },

    showMessage(type, summary, details, timeout = 3000) {
      this.$toast.add({
        severity:type,
        summary: summary,
        detail:details,
        life: timeout});
    },
    getOverallStats() {
      let self = this;

      this.axios.get('/api/stats').then((response) => {
        self.stats = response.data;
      })
    },
  },
  mounted() {
    this.getOverallStats()
  }
}
</script>

<style scoped>

</style>
