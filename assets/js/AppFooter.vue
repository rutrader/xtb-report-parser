<template>
	<div class="layout-footer">
    <span class="fw-bolder">&copy; {{ new Date().getFullYear() }}</span>
	</div>
</template>

<script>
	export default {
		name: "AppFooter",
    data() {
		  return {

      }
    },
		methods: {
			footerImage() {
				return this.$appState.darkTheme ? 'images/logo-white.svg' : 'images/logo-dark.svg';
			}
		},
		computed: {
			darkTheme() {
				return this.$appState.darkTheme;
			}
		}
	}
</script>