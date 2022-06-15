<template>
	<div class="grid p-fluid">
		<Toast />

		<div class="col-12 lg-col-12">

			<div class="card">

				<h5>Import history from XTB</h5>

				<transition-group name="p-message" tag="div">
					<Message v-for="msg of messages" :severity="msg.severity" :key="msg.id">{{ msg.content }}</Message>
				</transition-group>

				<Message severity="info" :sticky="true">It will override existed records</Message>

				<FileUpload name="report" url="/import" @upload="onUpload" @error="onFileUploadError" @select="onSelect"
					@before-send="onBeforeSend" :multiple="false" accept=".csv" :maxFileSize="1000000" />
			</div>

			<div class="col-12 lg-col-12">
				<div class="card">
					<table class="table">
						<thead>
							<tr>
								<th v-for="(field, key) in fields" v-bind:key="key">{{ field }}</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td v-for="(field, key) in fields" v-bind:key="key">
									<select  v-on:change="onMapFields($event.target.value, key)">
										<option>Select field</option>
										<option 
											v-for="(header, key) in headers.split(';')" 
											v-bind:key="key" 
											:value="key"
											:disabled="mapped[key] > -1"
											>{{ header }}</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>
</template>

<script>

export default {
	name: "Import",
	data() {
		return {
			messages: [],
			headers: 'Symbol;Position;Type;Open time;Open price;Close time;Close price;Profit;Net profit;Lots',
			fields: {
				'symbol' : 'Symbol', 
				'position' : 'Position', 
				'orderType' : 'Order type', 
				'lots' : 'Size', 
				'openedAt': 'Open time', 
				'openPrice' : 'Open price', 
				'closedAt': 'Close time', 
				'closePrice' : 'Close price', 
				'profit' : 'Profit', 
				'netProfit' : 'Net profit', 
				'comment': 'Comment',
			},
			mapped: null,
		}
	},

	created() {
		this.mapped = Object.keys(this.fields).reduce((acc, field) => ({ ...acc, [field] : -1}), {});
	},

	methods: {
		onFileUploadError(event) {
			this.showMessage('error', 'Error', JSON.parse(event.xhr.responseText).message, 3000);
		},

		onUpload(event) {
			this.showMessage('info', 'Success', JSON.parse(event.xhr.responseText).message, 3000);

			this.$router.push('/')
		},

		onSelect(event) {
			let self = this,
				reader = new FileReader;

			reader.readAsText(event.files[0]);

			reader.addEventListener("load", () => {
				self.headers = reader.result.split('\n').shift().replace(/(\r\n|\n|\r)/gm, "");
			}, false);
		},

		onBeforeSend(event) {
			event.formData.set('fields', JSON.stringify(this.mapped));
		},

		onMapFields(csvKey, field) {
			this.mapped[field] = parseInt(csvKey);
		},

		showMessage(type, summary, details, timeout = 3000) {
			this.$toast.add({
				severity: type,
				summary: summary,
				detail: details,
				life: timeout
			});
		}
	}
}
</script>

<style scoped>
</style>
