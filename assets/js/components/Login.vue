<template>
  <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">

      <div class="col-12 xl:col-6">

        <div class="h-full w-full m-0 py-7 px-4">
          <div class="w-full md:w-10 mx-auto">

            <div class="text-center mb-5 alert alert-danger d-flex align-items-center" role="alert" v-if="error">
              <i class="fa-solid fa-square-exclamation me-2 flex-shrink-0"></i>
              <div>{{ error }}</div>
            </div>

            <form action="/json/login" method="post" v-on:submit.prevent="doLogin">
              <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

              <div class="mb-3">
                <label for="username">Email:</label>
                <input type="email" id="username" name="username" class="form-control" v-model="username" />
              </div>

              <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" v-model="password" />
              </div>

              <button class="w-100 btn btn-lg btn-primary" type="button" v-on:click="doLogin">Sign in</button>

            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      username: '',
      password: '',
      error: null
    }
  },
  mounted() {
    // this.doLogin()
  },
  methods: {
    doLogin: function() {
      let self = this;

      this.axios.post('/json/login', {
        email: self.username,
        password: self.password
      })
      .then(response => {
        self.$emit('user-authenticated', response.headers.location);
        self.email = '';
        self.password = '';
      })
      .catch(error => {
        if(error.response.data.error) {
          this.error = error.response.data.error;
        } else {
          this.error = 'Unknown error';
        }
      })
      .finally(() => {
        this.isLoading = false;
      })
    }
  }
}
</script>

<style scoped>

</style>
