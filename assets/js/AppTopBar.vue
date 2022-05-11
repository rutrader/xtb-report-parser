<template>
  <div class="layout-topbar">
    <router-link to="/" class="layout-topbar-logo">
      <!--      <img alt="Logo" :src="topbarImage()" />-->
      <span>XTB parser</span>
    </router-link>
    <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle">
      <i class="fa-light fa-bars"></i>
    </button>

    <button class="p-link layout-topbar-menu-button layout-topbar-button"
            v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein',
			leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}">
      <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <!--    <Menu ref="menu" :model="headerMenu" :popup="true" />-->
    <ul class="layout-topbar-menu hidden lg:flex ">
      <li>
        <Button type="button"
                icon="fa-regular fa-upload"
                @click="onUploadClick"
                class="origin-top p-button p-button-icon-only p-button-rounded p-button-text" />
      </li>
      <li>
        <Button type="button"
                icon="fa-regular fa-arrow-right-from-bracket"
                @click="onLogoutClick"
                class="origin-top p-button p-button-icon-only p-button-rounded p-button-text" />
      </li>
    </ul>

    <!--    <ul class="layout-topbar-menu hidden lg:flex origin-top">
          <li>
            <button class="p-link layout-topbar-button">
              <i class="fa-light fa-calendar"></i>
              <span>Events</span>
            </button>
          </li>
          <li>
            <button class="p-link layout-topbar-button">
              <i class="fa-light fa-gear"></i>
              <span>Settings</span>
            </button>
          </li>
          <li>
            <button class="p-link layout-topbar-button">
              <i class="fa-light fa-user"></i>
              <span>Profile</span>
            </button>
          </li>
        </ul>-->
  </div>
</template>

<script>
export default {
  data() {
    return {
      auth: false,

      headerMenu: [
        {
          label: 'Upload',
          icon: 'fa-light fa-upload'
        },
        {
          label: 'Update',
          icon: 'pi pi-refresh'
        },
        {
          label: 'Delete',
          icon: 'pi pi-trash'
        },
        {
          separator: true
        },
        {
          label: 'Home',
          icon: 'pi pi-home'
        },
      ]
    }
  },
  mounted() {
    // this.checkAuth();
  },
  methods: {
    onMenuToggle(event) {
      this.$emit('menu-toggle', event);
    },
    onTopbarMenuToggle(event) {
      this.$emit('topbar-menu-toggle', event);
    },
    topbarImage() {
      return this.$appState.darkTheme ? 'images/logo-white.svg' : 'images/logo-dark.svg';
    },
    onUploadClick(event) {
      // this.$refs.menu.toggle(event);
      this.$router.push('import')
    },
    checkAuth() {
      let self = this;

      this.axios.get('/api/security/check')
          .then(function(response) {
            self.auth = true;
          })
          .catch(function(error) {
            self.auth = false;
          })
    },
    onLogoutClick() {
      window.location = '/auth/logout'
    }
  },
  computed: {
    darkTheme() {
      return this.$appState.darkTheme;
    }
  }
}
</script>
