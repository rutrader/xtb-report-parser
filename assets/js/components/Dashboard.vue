<template>
  <div class="grid p-fluid">
    <!-- <div class="col-12 lg:col-6 xl:col-3">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">Trades</span>
            <div class="text-900 font-medium text-xl">152</div>
          </div>
          <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
               style="width:2.5rem;height:2.5rem">
            <i class="pi pi-shopping-cart text-blue-500 text-xl"></i>
          </div>
        </div>
        <span class="text-green-500 font-medium">24 new </span>
        <span class="text-500">since last visit</span>
      </div>
    </div>
    <div class="col-12 lg:col-6 xl:col-3">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">Buy</span>
            <div class="text-900 font-medium text-xl">100</div>
          </div>
          <div class="flex align-items-center justify-content-center bg-orange-100 border-round"
               style="width:2.5rem;height:2.5rem">
            <i class="pi pi-map-marker text-orange-500 text-xl"></i>
          </div>
        </div>
        <span class="text-green-500 font-medium">%52+ </span>
        <span class="text-500">since last week</span>
      </div>
    </div>
    <div class="col-12 lg:col-6 xl:col-3">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">Sell</span>
            <div class="text-900 font-medium text-xl">52</div>
          </div>
          <div class="flex align-items-center justify-content-center bg-cyan-100 border-round"
               style="width:2.5rem;height:2.5rem">
            <i class="pi pi-inbox text-cyan-500 text-xl"></i>
          </div>
        </div>
        <span class="text-green-500 font-medium">520  </span>
        <span class="text-500">newly registered</span>
      </div>
    </div>
    <div class="col-12 lg:col-6 xl:col-3">
      <div class="card mb-0">
        <div class="flex justify-content-between mb-3">
          <div>
            <span class="block text-500 font-medium mb-3">P/L</span>
            <div class="text-900 font-medium text-xl">$12</div>
          </div>
          <div class="flex align-items-center justify-content-center bg-purple-100 border-round"
               style="width:2.5rem;height:2.5rem">
            <i class="pi pi-comment text-purple-500 text-xl"></i>
          </div>
        </div>
        <span class="text-green-500 font-medium">85 </span>
        <span class="text-500">responded</span>
      </div>
    </div>

    <div class="col-12 xl:col-6">
      <div class="card">
        <h5>Sales Overview</h5>
        <Chart type="line" :data="lineData" :options="lineOptions" />
      </div>
    </div>

    <div class="col-12 xl:col-6">
      <div class="card flex flex-column align-items-center">
        <h5 class="align-self-start">Pie Chart</h5>
        <Chart type="pie" :data="pieData" :options="pieOptions" style="width: 50%" />
      </div>
    </div>

    <div class="col-12 lg:col-12">
      <div class="card">
        <h5>Bar Chart</h5>
        <Chart type="bar" :data="barData" :options="barOptions" />
      </div>
    </div>
 -->
    <div class="col-12 lg-col-12">

      <div class="card">

        <h5>Advanced</h5>

        <FileUpload name="report" url="/import" @upload="onUpload" :multiple="false" accept=".csv" :maxFileSize="1000000"/>
      </div>
    </div>

    <Toast />
    <Button @click="showSuccess()" label="Success" class="p-button-success mr-2" />

  </div>
</template>

<script>
export default {
  name: "Dashboard",
  methods: {
    onUpload(event) {
      this.$toast.add({severity: 'info', summary: 'Success', detail: JSON.parse(event.xhr.responseText).message, life: 3000});
    },
    showSuccess() {
        this.$toast.add({severity:'success', summary: 'Success Message', detail:'Message Detail', life: 3000});
      },
  },

  data() {
    return {
      products: null,
      lineData: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
          {
            label: 'Revenue',
            data: [500, 501, 499, 485, 495, 502, 512, 512, 512, 512, 512, 512],
            fill: false,
            backgroundColor: '#2f4860',
            borderColor: '#2f4860',
            tension: 0.4
          },
          /*{
            label: 'Sales',
            data: [28, 48, 40, 19, 86, 27, 90],
            fill: false,
            backgroundColor: '#00bb7e',
            borderColor: '#00bb7e',
            tension: 0.4
          }*/
        ]
      },
      pieData: {
        labels: ['Profits', 'Loses'],
        datasets: [
          {
            data: [36, 64],
            backgroundColor: [
              "#FF6384",
              "#36A2EB",
              // "#FFCE56"
            ],
            hoverBackgroundColor: [
              "#FF6384",
              "#36A2EB",
              // "#FFCE56"
            ]
          }
        ]
      },
      barData: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
          {
            label: 'Performance by month',
            backgroundColor: [
              '#00bb7e',
              '#FF6384',
              '#FF6384',
              '#00bb7e',
              '#00bb7e',
              '#00bb7e',
            ],
            data: [1, -2, -14, 10, 7, 10]
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
      lineOptions: null,
    }
  }
}
</script>

<style scoped>

</style>
