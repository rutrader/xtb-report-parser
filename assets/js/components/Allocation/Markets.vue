<template>
  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg:col-12">
      <div class="card">
        <!--        <h5>Market types</h5>-->
        <Chart type="bar" :data="barData" :options="barOptions" />
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "Markets",
  data() {
    return {
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      barData: {
        labels: [],
        datasets: [],
      },
      barOptions: null,
      stackedData: {
        labels: [],
        datasets: [],
      },
      // stackedData: [],
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
      let self   = this,
          colors = [
            '#f20033',
            '#12b000',
            '#1B82E8',
          ];

      let stacked = [];

      this.barData.labels = this.stackedData.labels = this.months;

      this.axios.get('/api/allocations/markets').then((response) => {

        Object.keys(response.data).map((res, key) => {

          self.barData.datasets.push({
            data: new Array(self.months.length).fill(0),
            label: res.toUpperCase(),
            backgroundColor: colors[key]
          })

          Object.keys(response.data[res]).map((month) => {
            self.barData.datasets[key].data[month - 1] = response.data[res][month].market_counter;
            self.barData.datasets[key].backgroundColor = colors[key];
          })

        })

        let winners = [],
            stacked = {};

        Object.keys(response.data).map((market, key) => {

          Object.keys(response.data[market]).map((month) => {

            if (!stacked[month]) {
              stacked[month] = {};
              stacked[month][market] = [];
            }

            stacked[month][market] = {
              winners: response.data[market][month].winners,
              losers: response.data[market][month].losers
            }

          })

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
