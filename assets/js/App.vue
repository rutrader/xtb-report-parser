<template>
    <div :class="containerClass" @click="onWrapperClick" v-if="user">
      <div class="layout-main-container">

        <AppTopBar @menu-toggle="onMenuToggle" />

        <div class="layout-sidebar" @click="onSidebarClick">
          <AppMenu :model="menu" @menuitem-click="onMenuItemClick" />
        </div>

        <div class="layout-main">
          <router-view />
        </div>
        <!--      <AppFooter />-->
      </div>
    </div>
    <div v-else>
      <Login v-on:user-authenticated="onUserAuthenticated" />
    </div>
</template>

<script>

import AppTopBar from "./AppTopBar";
import AppMenu from "./AppMenu";
import Login from './components/Login'

export default {
  name: "App",
  components: {
    'AppTopBar': AppTopBar,
    'AppMenu': AppMenu,
    'Login': Login,
  },
  data() {
    return {
      user: null,
      layoutMode: 'static', //overlay
      staticMenuInactive: false,
      overlayMenuActive: false,
      mobileMenuActive: false,
      menu: [
        {
          label: 'Home',
          items: [{
            label: 'Dashboard', icon: 'fa-thin fa-home', to: '/'
          }]
        },
        {
          label: 'Performance',
          items: [
            {label: 'Monthly', icon: 'fa-thin fa-chart-pie-simple', to: '/performance/monthly'},
            {label: 'Daily', icon: 'fa-thin fa-calendar-day', to: '/performance/daily'},
            {label: 'Hourly', icon: 'fa-thin fa-clock-five', to: '/performance/hourly'},
            // {label: 'By days', icon: 'fa-thin fa-chart-mixed', to: '/time-stats/win-loss'}
          ]
        },
        {
          label: 'Allocation',
          items: [
            {label: 'By market type', icon: 'fa-thin fa-boxes-stacked', to: '/allocation/markets'}
          ]
        },
        {
          label: 'Settings',
          items: [
            {label: 'Settings', icon: 'fa-thin fa-cog', to: '/settings'}
          ]
        }
        /*{
          label: 'Pages', icon: 'pi pi-fw pi-clone',
          items: [
            {label: 'Crud', icon: 'pi pi-fw pi-user-edit', to: '/'},
          ]
        },*/
      ]
    }
  },
  mounted() {
    if(window.user) {
      this.user = window.user;
    }
  },
  methods: {
    onWrapperClick() {
      if (!this.menuClick) {
        this.overlayMenuActive = false;
        this.mobileMenuActive = false;
      }

      this.menuClick = false;
    },
    onMenuToggle() {
      this.menuClick = true;

      if (this.isDesktop()) {
        if (this.layoutMode === 'overlay') {
          if (this.mobileMenuActive === true) {
            this.overlayMenuActive = true;
          }

          this.overlayMenuActive = !this.overlayMenuActive;
          this.mobileMenuActive = false;
        } else if (this.layoutMode === 'static') {
          this.staticMenuInactive = !this.staticMenuInactive;
        }
      } else {
        this.mobileMenuActive = !this.mobileMenuActive;
      }

      event.preventDefault();
    },
    onSidebarClick() {
      this.menuClick = true;
    },
    onMenuItemClick(event) {
      if (event.item && !event.item.items) {
        this.overlayMenuActive = false;
        this.mobileMenuActive = false;
      }
    },
    onLayoutChange(layoutMode) {
      this.layoutMode = layoutMode;
    },
    addClass(element, className) {
      if (element.classList)
        element.classList.add(className);
      else
        element.className += ' ' + className;
    },
    removeClass(element, className) {
      if (element.classList)
        element.classList.remove(className);
      else
        element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
    },
    isDesktop() {
      return window.innerWidth >= 992;
    },
    isSidebarVisible() {
      if (this.isDesktop()) {
        if (this.layoutMode === 'static')
          return !this.staticMenuInactive;
        else if (this.layoutMode === 'overlay')
          return this.overlayMenuActive;
      }

      return true;
    },
    onUserAuthenticated(userUri) {
      this.axios
          .get(userUri)
          .then(response => (this.user = response.data))
    }
  },
  computed: {
    containerClass() {
      return ['layout-wrapper', {
        'layout-overlay': this.layoutMode === 'overlay',
        'layout-static': this.layoutMode === 'static',
        'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
        'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
        'layout-mobile-sidebar-active': this.mobileMenuActive,
        'p-input-filled': this.$primevue.config.inputStyle === 'filled',
        'p-ripple-disabled': this.$primevue.config.ripple === false
      }];
    },
  }
}
</script>

<style lang="scss">
@import '../scss/App.scss';
</style>
