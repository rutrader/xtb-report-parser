<template>
  <div class="grid p-fluid" v-if="!showLoader">
    <Toast />

    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="barData[key]">
        <h5>{{ month }}</h5>
        <Chart :ref="`barChart`+(key)" type="bar" :data="barData[key]" :options="barOptions" v-if="barData[key]" />
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="stackedData[key]">
        <h5>{{ month }}</h5>
        <Chart :ref="`stackedChart`+(key)" type="bar" :data="stackedData[key]" :options="stackedOptions" v-if="stackedData[key]" />
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="counterData[key]">
        <h5>{{ month }}</h5>
        <Chart type="bar" :data="counterData[key]" v-if="counterData[key]" />
      </div>
    </div>

  </div>
  <div v-else>
    <Skeleton shape="rectangle" height="8rem" />
  </div>
</template>

<script>
import * as Utils from "../../Utils";

export default {
  name: "PerformanceDaily",
  data() {
    return {
      showLoader: true,
      months: Utils.months({count: 12}),
      barData: [],
      barOptions: null,
      stackedData: [],
      stackedOptions: {
        scales: {
          x: {
            stacked: true,
          },
          y: {
            stacked: true,
          }
        }
      },
      counterData: [],
    }
  },
  methods: {
    getStats() {
      let self = this;

      this.axios.get('/api/performance/daily').then((response) => {
        Object.keys(response.data).map((res) => {
          self.barData[res-1] = {
            labels: response.data[res].map(time => time.trade_day),
            datasets: [
              {
                label: 'Performance by ' + self.months[res-1] + ' in CZK',
                backgroundColor: response.data[res].map(profit => profit.profit >= 0 ? '#12b000' : '#f20033'),
                data: response.data[res].map(profit => profit.profit),
              },
            ]
          };

          self.stackedData[res-1] = {
            // type: 'bar',
            labels: response.data[res].map(time => time.trade_day),
            datasets: [
              {
                bar: 'bar',
                label: 'Winners',
                backgroundColor: ['#12b000'],
                data: response.data[res].map(counter => counter.winners),
              },
              {
                bar: 'bar',
                label: 'Losers',
                backgroundColor: ['#f20033'],
                data: response.data[res].map(counter => counter.losers),
              }
            ]
          };

          self.counterData[res-1] = {
            labels: response.data[res].map(time => time.trade_day),
            datasets: [
              {
                label: 'Trade counts in ' + self.months[res-1],
                backgroundColor: '#409fdc',
                data: response.data[res].map(profit => profit.trade_counter)
              }
            ]
          }
        })

        self.showLoader = false;
      })
      .catch(function(error) {
        if(error.response) {
          if (error.response.status === 403 || error.response.status === 401 ) {

            self.showMessage('error', 'Access denied', 'Your session was expired');

            /*self.$toast.add({
              severity: 'error',
              summary: 'You are not authorized',
              detail: JSON.parse(event.xhr.responseText).message,
              life: 3000
            });*/

            window.location = '/login';
            // self.$router.push({ name: 'dashboard' })
          }
        } else {
          console.error(error.message)
        }
      })
    },

    showMessage(type, summary, details, timeout = 3000) {
      this.$toast.add({
        severity:type,
        summary: summary,
        detail:details,
        life: timeout});
    }
  },
  mounted() {
    this.getStats();
    // this.$toast.add({severity:'success', summary: 'Success Message', detail:'Order submitted', life: 3000});
  }
}
</script>

<style scoped>

</style>
