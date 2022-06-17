<template>
	<div class="grid p-fluid">
		<div class="col-12 col-lg-4" v-for="(month, key) in this.months" v-show="stats[key]">
			<div class="card" v-if="stats[key]">
				<h5>{{ month }}</h5>

				<DataTable :value="this.getStatsForDataTable(key)" :paginator="true" :rows="10" removableSort responsiveLayout="stack" breakpoint="960px" class="p-datatable-sm">
					<Column field="symbol" header="Symbol" :sortable="true">
						<template #header>
							<i class="mr-3 fa-light fa-arrow-down-arrow-up"></i>
						</template>
					</Column>
					<Column field="winners" header="Winners"></Column>
					<Column field="losers" header="Losers"></Column>
					<Column field="profit" header="Profit" :sortable="true">
						<template #header>
							<i class="mr-3 fa-light fa-arrow-down-arrow-up"></i>
						</template>
					</Column>
				</DataTable>
				<!-- <table class="table">
					<thead>
						<tr>
							<th>Symbol</th>
							<th>Winners</th>
							<th>Losers</th>
							<th>Prrofit</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(stat, symbol) in this.stats[key]">
							<td>
								<span class="fw-bold">{{ symbol }}</span>
							</td>
							<td>
								<span>{{ stat.winners }}</span>
							</td>
							<td>
								<span>{{ stat.losers }}</span>
							</td>
							<td>
								<span>{{ parseFloat(stat.profit).toFixed(2) }}</span>
							</td>
						</tr>
					</tbody>
				</table> -->
			</div>
		</div>
	</div>
</template>
<script>

import * as Utils from "../../Utils";

export default {
	name: 'Assets',
	data() {
		return {
			showLoader: true,
			months: Utils.months({ count: 12 }),
			stats: [],
		}
	},

	methods: {
		getStats() {
			let self = this;

			this.axios.get('/api/allocations/assets').then((response) => {
				self.stats = response.data;

				// Object.keys(response.data).map((res, key) => {

				// })
			})
		},
		getStatsForDataTable(month) {
			if (!this.stats[month]) {
				return [];
			}
			return Object.keys(this.stats[month]).map(symbol => {
				let stats = this.stats[month][symbol];

				return {
					symbol: symbol.toUpperCase(),
					profit: parseFloat(stats.profit).toFixed(2),
					winners: stats.winners,
					losers: stats.losers
				}
			});

			return [];
		}
	},
	created() {
		this.getStats()
	},
}
</script>
<style lang="scss">
// .pi {
// 	display: inline-block !important;

// 	&::before {
// 		display: none;
// 		text-rendering: auto;
// 		-webkit-font-smoothing: antialiased;
// 	}
// }

// .pi-sort-alt::before {
// 	content: "\f883";
// 	font: var(--fa-font-light);
// }


.p-datatable {
	.p-sortable-column {

		&.p-highlight,
		&.p-highlight:hover {
			color: #3b82f6;
		}
	}
}
</style>