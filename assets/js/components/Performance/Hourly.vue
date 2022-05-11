<template>
  <div class="grid p-fluid">

    <Toast />

    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="barData[key]">
        <h5>{{ month }}</h5>
        <Chart :ref="`barChart`+(key)" type="bar" :data="barData[key]" :options="barOptions" />
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="stackedData[key]">
        <h5>{{ month }}</h5>
        <Chart :ref="`stackedChart`+(key)" type="bar" :data="stackedData[key]" :options="stackedOptions" />
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card" v-for="(month, key) in this.months" v-show="counterData[key]">
        <h5>{{ month }}</h5>
        <Chart type="bar" :data="counterData[key]" />
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "PerformanceHourly",
  data() {
    return {
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      counterData: [],
      barData: [],
      stackedData: [],
      barOptions: null,
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
    }
  },
  methods: {
    getStats() {
      let self = this;

      this.axios.get('/api/performance/hourly').then((response) => {

        Object.keys(response.data).map((res) => {
          self.barData[res-1] = {
            labels: response.data[res].map(time => time.time_range),
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
            labels: response.data[res].map(time => time.time_range),
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
            labels: response.data[res].map(time => time.time_range),
            datasets: [
              {
                label: 'Trade counter: ' + self.months[res-1],
                backgroundColor: '#409fdc',
                data: response.data[res].map(profit => profit.trade_counter),
              },
            ]
          };

        })
      })
    }
  },
  mounted() {
    this.getStats();
  }
}
</script>

<style scoped>

</style>
