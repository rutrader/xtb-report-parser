<template>
  <Dropdown v-model="$i18n.locale" :options="locales" optionLabel="name" :placeholder="$t('change-locale')"
            @change="changeLocale">
    <template #value="locale">
      <div class="" v-if="locale.value">
        <img :alt="locale.value" :src="'/images/flagicons/' + locale.value.toUpperCase() + '.png'"/>
      </div>
      <span v-else>
                  {{ locale.placeholder }}
                </span>
    </template>
    <template #option="locale">
      <div class="">
        <img class="mr-2" :alt="locale.option.code"
             :src="'/images/flagicons/' + locale.option.code.toUpperCase() + '.png'"/>
        <span>{{ locale.option.name }}</span>
      </div>
    </template>
    <template #indicator>
      <i class="fa-light fa-chevron-down"></i>
    </template>
  </Dropdown>
</template>

<script>
import {getSupportedLocales} from "../i18n/supported-locales"

export default {
  name: "LocaleSwitcher",
  data() {
    return {
      locales: getSupportedLocales()
    }
  },
  methods: {
    changeLocale(event) {
      this.$i18n.locale = event.value.code;
      let route = this.$router.currentRoute.value.name;
      this.$router.push({name: route, params: {locale: event.value.code}})
    },
  }
}
</script>

<style scoped>

</style>