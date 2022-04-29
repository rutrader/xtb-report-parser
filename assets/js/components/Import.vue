<template>
  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg-col-12">

      <div class="card">

        <h5>Import history from XTB</h5>

        <Message severity="info" :sticky="true">It will override existed records</Message>

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
</template>

<script>
export default {
  name: "Import",
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

  }
}
</script>

<style scoped>

</style>
