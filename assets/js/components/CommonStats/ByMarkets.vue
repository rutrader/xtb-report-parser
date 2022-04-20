<template>
  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg:col-12">
      <div class="card">
        <!--        <h5>Market types</h5>-->
        <Chart type="bar" :data="barData" :options="barOptions" />
      </div>
      <!--      <div class="card">

            </div>-->
    </div>
<!--    <div class="col-12 lg:col-6">
      <div class="card">
        <Chart type="bar" :data="stackedData" :options="stackedOptions" />
      </div>
    </div>-->
  </div>

</template>

<script>
export default {
  name: "ByMarkets",
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

      this.axios.get('/api/market-stats').then((response) => {

        Object.keys(response.data).map((res, key) => {

          self.barData.datasets.push({
            data: new Array(self.months.length).fill(0),
            label: res.toUpperCase(),
            backgroundColor: colors[key]
          })

          /*self.stackedData.datasets.push({
            data: new Array(self.months.length).fill(2),
            label: res.toUpperCase(),
            backgroundColor: []
          })*/

          Object.keys(response.data[res]).map((month) => {
            self.barData.datasets[key].data[month - 1] = response.data[res][month].market_counter;
            self.barData.datasets[key].backgroundColor = colors[key];

            // self.stackedData.datasets[key].data[month - 1] = response.data[res][month].winners;
            // self.stackedData.datasets[key].data[month - 1] = response.data[res][month].losers;
            // self.stackedData.datasets[key].backgroundColor = colors[key];

            /*self.stackedData.datasets[key] = [
                {
                  label: 'Winners',
                  data: [1, 2],
                  backgroundColor: colors[key],
                  stack : 'Stacked ' + res
                },
                {
                  label: 'losers',
                  data: [3, 4],
                  backgroundColor: colors[key-1],
                  stack : 'Stacked ' + res
                },
            ]*/
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

        Object.keys(stacked).map((month, key) => {

          self.stackedData.datasets.push({
              data: new Array(12).fill(Math.floor(Math.random()*100)),
              label: key === 0 ? 'Winners' : 'Losers',
              stack: 'stacked-' + key,
            });

          Object.keys(stacked[month]).forEach((market, index) => {
            /*self.stackedData.datasets.push({
              data: new Array(12).fill(Math.floor(Math.random()*100)),
              label: key === 0 ? 'Winners' : 'Losers',
              stack: 'stacked-' + key,
            });*/

            self.stackedData.datasets[key].data[index] = stacked[month][market].winners;
            // self.stackedData.datasets[key].data[key] = stacked[month][market].;

            /*self.stackedData.datasets[key].data = Object.keys(stacked[month]).map(
                (market) => key === 0 ? stacked[month][market].winners : stacked[month][market].losers
            );*/

            self.stackedData.datasets[key].type = 'bar';
            self.stackedData.datasets[key].backgroundColor = colors[key];
            // self.stackedData.datasets[key].stack = 'stack-' + key;


            /*self.stackedData.datasets.push(
                {
                  data: Object.keys(stacked[month]).map((market) => stacked[month][market].winners),
                  stack: key,
                  backgroundColor: colors[key]
                },
                {
                  data: Object.keys(stacked[month]).map((market) => stacked[month][market].losers),
                  stack: key,
                  backgroundColor: colors[key]
                }
            )*/
          })

          /*self.stackedData.datasets.push(
              {
                data: Object.keys(stacked[month]).map((market) => stacked[month][market].winners),
                backgroundColor: colors[key]
              },

          )*/
        })

        console.log(stacked);
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
