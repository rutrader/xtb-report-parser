<template>
  <div>
    <div v-if="this.stats.total_orders > 0">
      <div class="grid p-fluid">

        <div class="col-12 lg:col-6 xl:col-3">
          <div class="card mb-0">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Trades</span>
                <div class="text-900 font-medium text-xl">{{ this.stats.total_orders }}</div>
              </div>
              <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-sigma text-blue-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">24 new </span>-->
            <!--        <span class="text-500">since last visit</span>-->
          </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3" >
          <div class="card mb-0">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Buy orders</span>
                <div class="text-900 font-medium text-xl">{{ this.stats.buy_orders }}</div>
              </div>
              <div class="flex align-items-center justify-content-center bg-orange-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-arrow-trend-up text-green-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">%52+ </span>-->
            <!--        <span class="text-500">since last week</span>-->
          </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
          <div class="card mb-0">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Sell orders</span>
                <div class="text-900 font-medium text-xl">{{ this.stats.sell_orders }}</div>
              </div>
              <div class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-arrow-trend-down text-orange-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">520  </span>-->
            <!--        <span class="text-500">newly registered</span>-->
          </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
          <div class="card mb-0">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">P/L</span>
                <div class="text-900 font-medium text-xl">{{ parseFloat(this.stats.pl).toFixed(2) }}</div>
              </div>
              <div class="flex align-items-center justify-content-center bg-teal-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-sack-dollar text-blue-500"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">85 </span>-->
            <!--        <span class="text-500">responded</span>-->
          </div>
        </div>


        <div class="col-12 xl:col-6">
          <div class="card">
            <h5>Cumulative P&L</h5>
            <Chart ref="lineChart" type="line" :data="getCumulative()" :options="lineOptions" />
          </div>
        </div>

        <div class="col-12 xl:col-6">

          <div class="card flex flex-column align-items-center">
            <h5 class="align-self-start">Profit to Loss</h5>
            <Chart ref="pieChart" type="pie" :data="getAccuracy()" :options="pieOptions" style="width: 50%" />
          </div>
        </div>

        <div class="col-12 lg:col-12">
          <div class="card">
            <h5>Bar Chart</h5>
            <Chart ref="barChart" type="bar" :data="barData" :options="barOptions" />
          </div>
        </div>
      </div>
    </div>
    <div v-else>

      <Toast />

      <div class="col-12 lg-col-12">

        <div class="card">

          <h5>Advanced</h5>

          <FileUpload name="report"
                      url="/import"
                      @upload="onUpload"
                      @error="onFileUploadError"
                      :multiple="false"
                      accept=".csv"
                      :maxFileSize="1000000" />
        </div>
      </div>


    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  methods: {

    onFileUploadError(event) {
      this.$toast.add({
        severity: 'error',
        summary: 'Error',
        detail: JSON.parse(event.xhr.responseText).message,
        life: 3000
      });
    },

    onUpload(event) {
      this.$toast.add({
        severity: 'info',
        summary: 'Success',
        detail: JSON.parse(event.xhr.responseText).message,
        life: 3000
      });
    },

    getCumulative() {
      let self = this;

      self.axios.get('/api/profit/cumulative').then((response) => {
        self.lineData.datasets[0].data = response.data.map(res => res.net_profit);
        self.lineData.labels = response.data.map(res => res.date);
      })

      return self.lineData;
    },

    getAccuracy() {
      this.pieData.datasets[0].data = [this.stats.profit_orders / this.stats.total_orders * 100, this.stats.loss_orders / this.stats.total_orders * 100]

      return this.pieData;
    },

    getOverAll() {
      let self = this;

      this.axios.get('/api/trades/overall').then((response) => {

        self.stats = response.data;

        // self.$refs.pieChart.data.datasets[0].data = [self.stats.profit_orders / self.stats.total_orders * 100, self.stats.loss_orders / self.stats.total_orders * 100];
      })
    },

    getProfitAndLossByMonth() {
      let self = this;

      this.axios.get('/api/profit/month').then((response) => {
        self.results = new Array(self.months.length);
        self.results.fill(0);

        response.data.map(res => {
          self.results[res.date - 1] = res.net_profit;
        })

        // self.dates = response.data.map(res => res.date);
        // self.results = response.data.map(res => res.net_profit);

        self.$refs.barChart.data.datasets[0].data = self.results
        self.$refs.barChart.data.labels = self.months;

        for (let i = 0; i <= self.results.length; i++) {
          self.$refs.barChart.data.datasets[0].backgroundColor.push(self.results[i] >= 0 ? '#12b000' : '#f20033')
        }
      })
    }
  },

  mounted() {
    this.getOverAll();
    // this.getProfitAndLossByDay();
    // this.getProfitAndLossByMonth();
  },

  data() {
    return {
      dates: [],
      results: [],
      stats: [],
      profits: 0,
      loses: 0,
      total: 0,
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      lineData: {
        labels: [],
        datasets: [
          {
            label: 'Net profit',
            data: [],
            fill: false,
            backgroundColor: '#2f4860',
            borderColor: '#2f4860',
            tension: 0.1
          },
        ]
      },

      lineOptions: {
        scales: {
          y: {
            // min: -1000,
            // max: 1000
          }
        }
      },


      pieData: {
        labels: ['Прибыльные сделки', 'Убыточные сделки'],
        datasets: [
          {
            data: [],
            backgroundColor: [
              "#12b000",
              "#f20033",
              // "#FFCE56"
            ],
            hoverBackgroundColor: [
              "#12b000",
              "#f20033",
              // "#FFCE56"
            ]
          }
        ]
      },
      barData: {
        labels: [],
        datasets: [
          {
            label: 'Performance by month',
            backgroundColor: [],
            data: []
          },
          /*{
            label: 'My Second dataset',
            backgroundColor: '#00bb7e',
            data: [28, 48, 40, 19, 86, 27, 90]
          }*/
        ]
      },
      items: [
        {label: 'Add New', icon: 'pi pi-fw pi-plus'},
        {label: 'Remove', icon: 'pi pi-fw pi-minus'}
      ],
      barOptions: null,
      pieOptions: null,
    }
  }
}
</script>

<style scoped>

</style>
