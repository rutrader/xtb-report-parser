<template>
  <div class="grid p-fluid">
    <Toast position="bottom-right" />

    <div class="col-12 col-lg-12">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">{{ $t('settings.trade-history.title') }}</span>
            <div class="text-900 font-medium text-xl">
              {{ $t('settings.trade-history.sub-title') }}
            </div>
          </div>
          <div class="flex align-items-center justify-content-center">
            <Button :disabled="stats.total_orders <= 0" class="p-button-danger"
              :label="$t('settings.trade-history.erase-button')" icon="fa-regular fa-trash-can-xmark" iconPos="right"
              @click="clearRecords" />
          </div>
        </div>
        <span class="text-orange-600 font-italic">
          {{ $t('settings.trade-history.counter', { count: stats.total_orders }) }}
        </span>
      </div>

      <div class="card mt-3">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">
              {{ $t('settings.import.title') }}
            </span>
            <div class="text-900 font-medium text-xl">
              {{ $t('settings.import.sub-title') }}
            </div>
          </div>
          <div class="flex align-items-center justify-content-center">
            <Button class="p-button-success" :label="$t('settings.import.import-button')" icon="fa-regular fa-upload"
              icon-pos="right" @click="$router.push('import')" />
          </div>
        </div>
      </div>
      <div class="card mt-3">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">
              <!-- {{ $t('settings.currency.title') }} -->
            </span>
            <div class="text-900 font-medium text-xl">
              {{ $t('settings.currency.title') }}
            </div>
          </div>
          <div class="flex align-items-center justify-content-center">
            <select v-model="stats.currency" class="form-select" @change="onCurrencySet($event.target.value)">
              <option>Select</option>
              <option value="czk">Czech crown (CZK)</option>
              <option value="eur">Euro (EUR)</option>
              <option value="usd">US dollar (USD)</option>
              <option value="chf">Swiss frank (CHF)</option>
            </select>
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
          self.showMessage('error', 'Success', 'Your history (' + response.data.message + ') was erased')
          self.getOverallStats()
        })
    },

    showMessage(type, summary, details, timeout = 3000) {
      this.$toast.add({
        severity: type,
        summary: summary,
        detail: details,
        life: timeout
      });
    },
    getOverallStats() {
      let self = this;

      this.axios.get('/api/stats').then((response) => {
        self.stats = response.data;
      })
    },
    onCurrencySet(code) {
      let self = this;

      const formData = new FormData();
      formData.append('code', code);

      this.axios.post('/api/settings/set-currency', formData).then(res => {
        self.showMessage('success', 'OK!', 'Your currency was saved');
      }).catch(err => {
        self.showMessage('error', 'Error', 'Something went wrong =()');
      })
    }
  },
  created() {
    this.getOverallStats()
  }
}
</script>

<style scoped>
</style>
