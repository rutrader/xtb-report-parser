<template>

  <div class="grid p-fluid" v-if="!showLoader">
    <Toast />

    <div class="col-12 col-lg-6">
      <div class="card">
        <h5>Bar Chart</h5>
        <Chart ref="barChart" type="bar" :data="performanceData" :options="barOptions" />
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card">
        <h5>Winner/Losers</h5>
        <Chart type="bar" :data="winnerLosersData" :options="stackedOptions" />
      </div>
    </div>
  </div>
  <div v-else>
    <Skeleton shape="rectangle" height="8rem" />
  </div>

</template>

<script>
import * as Utils from '../../Utils'

export default {
  name: "PerformanceMonthly",
  data() {
    return {
      showLoader: true,
      months: Utils.months({count: 12}),
      pieData: [],
      pieOptions: {
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          }
        }
      },
      performanceData: {
        labels: [],
        datasets: [
          {
            label: 'Performance by month',
            backgroundColor: [],
            data: []
          },
        ]
      },
      barOptions: null,
      winnerLosersData: {
        labels: [],
        datasets: [],
      },

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
      winners: [],
      losers: [],
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
            labels: ['Winners (' + winners.toFixed(1) + ')', 'Losers (' + losers.toFixed(1) + ')'],
            datasets: [
              {
                backgroundColor: ['#12b000', '#f20033'],
                data: [winners, losers],
              },
            ]
          };
        });
      })

    },

    getMonthlyData() {
      let self = this;

      this.axios.get('/api/performance/monthly').then((response) => {

        response.data.map(res => {
          let profit = parseFloat(res.profit).toFixed(2);
          self.performanceData.datasets[0].data[parseInt(res.month) - 1] = profit;
          self.performanceData.datasets[0].backgroundColor.push(profit >= 0 ? '#12b000' : '#f20033')

          self.winners[parseInt(res.month) - 1] = res.winners;
          self.losers[parseInt(res.month) - 1] = res.losers;
        })

        self.getWinnerLosersData();

        self.showLoader = false;
      })

      return self.performanceData;
    },

    getWinnerLosersData() {
      let self = this;

      self.winnerLosersData.datasets.push(
          {
            label: 'Winners',
            data: self.winners,
            backgroundColor: ['#12b000'],
          },
          {
            label: 'Losers',
            data: self.losers,
            backgroundColor: ['#f20033'],
          }
      )

      return self.winnerLosersData;
    }
  },
  mounted() {
    this.performanceData.datasets[0].data = new Array(this.months.length).fill(0)
    this.performanceData.labels = this.winnerLosersData.labels = this.months;
    this.winners = new Array(this.months.length).fill(0);
    this.losers = new Array(this.months.length).fill(0)

    this.getMonthlyData();
  }
}
</script>

<style scoped>

</style>
