<template>

  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg:col-3" v-for="(month, key) in this.months" v-show="pieData[key]">
      <div class="card">
        <h5>{{ month }}</h5>
        <Chart type="pie" :data="pieData[key]" :options="pieOptions" />
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "ByMonths",
  data() {
    return {
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      pieData: [],
      pieOptions: {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        }
      }
    }
  },
  methods: {
    getStats() {
      let self = this;

      this.axios.get('/api/time-stats/by-months').then((response) => {
        Object.keys(response.data).map((res) => {

          let result  = response.data[res],
              winners = result[0].winners / result[0].trade_counter * 100,
              losers  = result[0].losers / result[0].trade_counter * 100;

          self.pieData[res - 1] = {
            labels: ['Winners ('+ winners.toFixed(1) +')', 'Losers (' + losers.toFixed(1) + ')'],
            datasets: [
              {
                backgroundColor: ['#12b000', '#f20033'],
                data: [winners, losers],
              },
            ]
          };

          console.log(result[0].winners / result[0].trade_counter * 100, result[0].losers / result[0].trade_counter * 100);
        });
      })

    },
  },
  mounted() {
    this.getStats()
  }
}
</script>

<style scoped>

</style>
