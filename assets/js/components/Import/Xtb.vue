<template>
	<div class="grid p-fluid">
		<Toast />

		<div class="col-12 lg-col-12">
			<Message severity="info" :sticky="true">It will override existed records</Message>
		</div>

		<div class="col-12 lg-col-12">

			<div class="card">

				<h5>Import history from XTB</h5>

				<transition-group name="p-message" tag="div">
					<Message v-for="msg of messages" :severity="msg.severity" :key="msg.id">{{ msg.content }}
					</Message>
				</transition-group>
				<div class="mb-3">
					<label for="formFile" class="form-label">Default file input example</label>
					<input class="form-control" type="file" id="formFile" @change="onSelect">
				</div>

			</div>
		</div>
		<div class="col-12 lg-col-12" v-if="headers">
			<div class="card">
				<table class="table">
					<!-- <thead>
						<tr>
							<th v-for="(field, key) in fields" v-bind:key="key">{{ field }}</th>
						</tr>
					</thead> -->
					<tbody>
						<tr v-for="(field, fieldKey) in fields" v-bind:key="fieldKey">
							<td class="fw-bold">{{ field }}</td>
							<td>
								<select class="form-select" :class="[mapped[fieldKey] < 0 ? 'border-danger' : '']" v-on:change="onMapFields($event.target, $event.target.value, fieldKey)"
									v-if="headers" v-model="mapped[fieldKey]">
									<option>Select field</option>
									<option v-for="(header, key) in headers" v-bind:key="key" :value="key"
										:disabled="mapped[key] > -1">{{ header }}</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="col-12 lg-col-12">
			<div class="card d-flex flex-row">
				<button :disabled="!file" class="btn btn-success" v-on:click="submitReport()">Submit</button>
			</div>
		</div>

	</div>
</template>

<script>

export default {
	name: "Xtb",
	data() {
		return {
			file: null,
			error: null,
			messages: [],
			headers: null,
			fields: {
				'symbol': 'Symbol',
				'position': 'Position',
				'orderType': 'Order type',
				'lots': 'Size',
				'openedAt': 'Open time',
				'openPrice': 'Open price',
				'closedAt': 'Close time',
				'closePrice': 'Close price',
				'profit': 'Profit',
				'netProfit': 'Net profit',
				'comment': 'Comment',
			},
			mapped: null,
		}
	},

	created() {
		this.mapped = Object.keys(this.fields).reduce((acc, field) => ({ ...acc, [field]: -1 }), {});
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

			self.file = event.target.files[0];

			reader.readAsText(self.file);

			reader.addEventListener("load", () => {
				self.headers = reader.result.split('\n').shift().replace(/(\r\n|\n|\r)/gm, "").split(';');

				Object.keys(self.headers).forEach(function (i, k) {

					let field = Object.keys(self.fields).find(fieldKey => self.fields[fieldKey] === self.headers[k]);;

					if (field && self.mapped[field]) {
						self.mapped[field] = k;
					}
				})
			}, false);
		},

		submitReport() {
			let
				self = this,
				formData = new FormData;

			formData.append('report', this.file);
			formData.append('fields', JSON.stringify(this.mapped));

			this.axios.post('/import', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then(function (response) {
				self.showMessage('info', 'Success', response.data && response.data.message ? response.data.message : '', 3000)

				this.$router.push('/')

			}).catch(function (error) {
				console.log(error);

				if (error.response.data.error) {
					self.error = error.response.data.error;
				} else {
					self.error = 'Unknown error';
				}

				this.file = null;

				self.showMessage('error', 'Error', self.error, 3000);
			})
		},

		onBeforeSend(event) {
			event.formData.set('fields', JSON.stringify(this.mapped));
		},

		onMapFields(target, csvKey, field) {
			console.log(target);

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
