<template>
  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg:col-6">
      <div class="card" v-for="(month, key) in this.months" v-show="barData[key]">
        <h5>{{ month }}</h5>
        <Chart :ref="`barChart`+(key)" type="bar" :data="barData[key]" :options="barOptions" />
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "ByMarkets",
  data() {
    return {
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
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
    }
  },
  methods: {
    getStats() {
      let self = this;

      this.axios.get('/api/time-stats/by-days').then((response) => {
        Object.keys(response.data).map((res) => {
          self.barData[res - 1] = {
            // labels: response.data[res].map(date => self.month[date.month]),
            datasets: [
              {
                label: self.months[res-1],
                // backgroundColor: response.data[res].map(profit => profit.profit >= 0 ? '#12b000' : '#f20033'),
                // data: response.data[res].map(counter => counter.count),
                data: [1,2,3,4]
              },
            ]
          }
        })
      });
    }
  },

  mounted() {
    this.getStats()
  }
}
</script>

<style scoped>

</style>
