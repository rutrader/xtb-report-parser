<template>
  <div class="grid p-fluid">
    <div class="col-12 col-lg-6 mx-auto pt-8">

      <div class="text-center mb-5 alert alert-danger d-flex align-items-center" role="alert" v-if="error">
        <i class="fa-solid fa-square-exclamation me-2 flex-shrink-0"></i>
        <div>{{ $t(error) }}</div>
      </div>


      <div class="card rounded-0">
        <div class="card-header bg-transparent d-flex justify-content-between">
          <h1 class="h3 mb-3 fw-normal">{{ $t('login.title') }}</h1>

          <div>
            <LocaleSwitcher />

            <!--            <a class="btn btn-light" @click="toggle">
                          <i class="fa-light fa-language fa-xl"></i>
                        </a>
                        <Menu ref="menu" :model="$i18n.availableLocales" :popup="true">
                          <template #item="{item}">
                            <img :src="`/images/flagicons/` + item.toUpperCase() + `.png`" />
                          </template>
                        </Menu>-->
          </div>

        </div>

        <div class="card-body">

          <form action="/login" method="post" v-on:submit.prevent="doLogin">
            <div class="mb-3">
              <label for="username">Email:</label>
              <input type="email" id="username" name="username" class="form-control" v-model="username"/>
            </div>

            <div class="mb-3">
              <label for="password">{{ $t('form.label.password') }}:</label>
              <input type="password" id="password" name="password" class="form-control" v-model="password"/>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ $t('form.label.signin') }}</button>

          </form>
        </div>

      </div>
    </div>
  </div>

  <!--  <div class="flex align-items-center justify-content-center overflow-hidden">

      <div class="grid justify-content-center p-2 p-lg-0" style="min-width:80%">

        <div class="col-12 col-lg-6">

          <div class="h-full w-full m-0 py-7 px-4">
            <div class="w-full md:w-10 mx-auto">

              <div class="text-center mb-5 alert alert-danger d-flex align-items-center" role="alert" v-if="error">
                <i class="fa-solid fa-square-exclamation me-2 flex-shrink-0"></i>
                <div>{{ $t(error) }}</div>
              </div>

              <div class="locale-changer">
                <select v-model="$i18n.locale">
                  <option v-for="locale in $i18n.availableLocales" :key="`locale-${locale}`" :value="locale">{{ locale }}</option>
                </select>
              </div>

              <form action="/json/login" method="post" v-on:submit.prevent="doLogin">
                <h1 class="h3 mb-3 fw-normal">{{ $t('login.title') }}</h1>

                <div class="mb-3">
                  <label for="username">Email:</label>
                  <input type="email" id="username" name="username" class="form-control" v-model="username" />
                </div>

                <div class="mb-3">
                  <label for="password">{{ $t('form.label.password') }}:</label>
                  <input type="password" id="password" name="password" class="form-control" v-model="password" />
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">{{ $t('form.label.signin') }}</button>

              </form>
            </div>
          </div>
        </div>

      </div>
    </div>-->
</template>

<script>
import LocaleSwitcher from "./LocaleSwitcher";

export default {
  name: "Login",
  components: {LocaleSwitcher},
  data() {
    return {
      username: '',
      password: '',
      error: null,
      locales: this.$i18n.availableLocales.map(locale => {
        return {name: locale, code: locale}
      }),
      items: [
        {
          label: 'Update',
          icon: 'fa-thin fa-refresh',

        },
      ]
    }
  },
  methods: {
    doLogin: function () {
      let self = this;

      this.axios.post('/login', {
        email: self.username,
        password: self.password
      })
          .then(response => {
            self.$emit('user-authenticated', response.headers.location);
            self.email = '';
            self.password = '';
          })
          .catch(error => {
            if (error.response.data.error) {
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
