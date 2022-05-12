<template>
  <div>
    <div v-if="true">
      <div class="grid p-fluid">

        <!--        Overall stats-->
        <div class="col-12 col-lg-3">
          <div class="card mb-0" v-if="hasStats">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Trades</span>
                <div class="text-900 font-medium text-xl">
                  <span v-if="hasStats">
                    {{ this.stats.total_orders }}
                  </span>
                  <span v-else>
                    0
                  </span>
                </div>
              </div>
              <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-sigma text-blue-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">24 new </span>-->
            <!--        <span class="text-500">since last visit</span>-->
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
          </div>
        </div>

        <!--        Buy orders-->
        <div class="col-12 col-lg-3">
          <div class="card mb-0" v-if="hasStats">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Buy orders</span>
                <div class="text-900 font-medium text-xl">
                  <span v-if="hasStats">
                    {{ this.stats.buy_orders }}
                  </span>
                  <span v-else>
                    0
                  </span>
                </div>
              </div>
              <div class="flex align-items-center justify-content-center bg-orange-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-arrow-trend-up text-green-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">%52+ </span>-->
            <!--        <span class="text-500">since last week</span>-->
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
          </div>
        </div>

        <!--        Sell orders-->
        <div class="col-12 col-lg-3">
          <div class="card mb-0" v-if="hasStats">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Sell orders</span>
                <div class="text-900 font-medium text-xl">
                  <span v-if="hasStats">
                    {{ this.stats.sell_orders }}
                  </span>
                  <span v-else>
                    0
                  </span>
                </div>
              </div>
              <div class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-arrow-trend-down text-orange-500 text-xl"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">520  </span>-->
            <!--        <span class="text-500">newly registered</span>-->
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
          </div>
        </div>

        <!--        Gross P/L-->
        <div class="col-12 col-lg-3">
          <div class="card mb-0" v-if="hasStats">
            <div class="flex justify-content-between mb-3">
              <div>
                <span class="block text-500 font-medium mb-3">Gross P/L</span>
                <div class="text-900 font-medium text-xl">
                  <span v-if="hasStats">
                    {{ parseFloat(this.stats.pl).toFixed(2) }}
                  </span>
                  <span v-else>
                    0
                  </span>
                </div>
              </div>
              <div class="flex align-items-center justify-content-center bg-teal-100 border-round"
                   style="width:2.5rem;height:2.5rem">
                <i class="fa-light fa-sack-dollar text-blue-500"></i>
              </div>
            </div>
            <!--        <span class="text-green-500 font-medium">85 </span>-->
            <!--        <span class="text-500">responded</span>-->
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
          </div>
        </div>


        <div class="col-12 col-lg-6">
          <div class="card" v-if="hasStats">
            <h5>Overall trades performance</h5>
            <Chart type="line" :data="lineData" :options="lineOptions" />
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
          </div>
        </div>

        <div class="col-12 col-lg-6">

          <div class="card flex flex-column align-items-center" v-if="hasStats">
            <h5 class="align-self-start">Profit to Loss</h5>
            <Chart ref="pieChart" type="pie" :data="getAccuracy()" :options="pieOptions" style="width: 50%" />
          </div>
          <div v-else>
            <Skeleton shape="rectangle" height="8rem" />
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
import * as Utils from "../Utils";

export default {
  name: "Dashboard",
  methods: {

    getOverallStats() {
      let self = this;

      this.axios.get('/api/stats').then((response) => {
        self.stats = response.data;
      })
    },

    getPerformanceOverall() {
      let self = this;

      self.axios.get('/api/performance/overall').then((response) => {
        self.lineData.datasets[0].data = response.data.map(res => res.net_profit);
        self.lineData.labels = response.data.map(res => res.date);
      })

      return self.lineData;
    },

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

    getAccuracy() {
      this.pieData.datasets[0].data = [this.stats.profit_orders / this.stats.total_orders * 100, this.stats.loss_orders / this.stats.total_orders * 100]

      return this.pieData;
    },

  },

  computed: {
    hasStats() {
      return this.stats.total_orders > 0
    }
  },

  mounted() {
    this.getOverallStats();
    this.getPerformanceOverall();
  },

  data() {
    return {
      dates: [],
      results: [],
      stats: [],
      profits: 0,
      loses: 0,
      total: 0,
      months: Utils.months({count: 12}),
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
            min: -1000,
            max: 1000
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
      items: [
        {label: 'Add New', icon: 'pi pi-fw pi-plus'},
        {label: 'Remove', icon: 'pi pi-fw pi-minus'}
      ],
      pieOptions: null,
    }
  }
}
</script>

<style scoped>

</style>
