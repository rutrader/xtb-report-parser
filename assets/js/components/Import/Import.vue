<template>
	<div>
		<div class="grid p-fluid">
			<div class="col-2">
				<div class="card">
					<router-link :to="{ name: 'import-xtb' }" class="btn">
						<!-- <span>XTB parser</span> -->
						<img src="/images/brands/xtb/xtb-logo.png" class="w-10" />
					</router-link>

				</div>
			</div>
			<div class="col-2">
				<div class="card ribbon-parent">
					<span class="ribbon">Not implemented yet</span>
					<a class="btn disabled" disabled>
						<img src="/images/brands/metatrader/mt4-logo.png" class="w-10" />
					</a>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {

}
</script>
<style lang="css">

/**
* @author Paulie-D 
* @see https://codepen.io/Paulie-D/pen/gpgYvZ
*/
.ribbon-parent {
	overflow: hidden;
	height: auto;
}

.ribbon {
	z-index: 1000;
	margin: 0;
	padding: 0;
	background: red;
	color: white;
	padding: 1em 0;
	position: absolute;
	top: 0;
	right: 0;
	transform: translateX(30%) translateY(0%) rotate(45deg);
	transform-origin: top left;
}

.ribbon:before,
.ribbon:after {
	content: '';
	position: absolute;
	top: 0;
	margin: 0 -1px;
	/* tweak */
	width: 100%;
	height: 100%;
	background: red
}

.ribbon:before {
	right: 100%;
}

.ribbon:after {
	left: 100%;
}
</style>