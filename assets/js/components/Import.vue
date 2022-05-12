<template>
  <div class="grid p-fluid">
    <Toast />

    <div class="col-12 lg-col-12">

      <div class="card">

        <h5>Import history from XTB</h5>

        <transition-group name="p-message" tag="div">
          <Message v-for="msg of messages" :severity="msg.severity" :key="msg.id">{{msg.content}}</Message>
        </transition-group>

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
  data() {
    return {
      messages: [],
    }
  },
  methods: {
    onFileUploadError(event) {
      this.showMessage('error', 'Error', JSON.parse(event.xhr.responseText).message, 3000);
    },

    onUpload(event) {
      this.showMessage('info', 'Success', JSON.parse(event.xhr.responseText).message, 3000);

      this.$router.push('/')
    },

    showMessage(type, summary, details, timeout = 3000) {
      this.$toast.add({
        severity:type,
        summary: summary,
        detail:details,
        life: timeout});
    }
  }
}
</script>

<style scoped>

</style>
