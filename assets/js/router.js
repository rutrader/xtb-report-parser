import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';
import { setI18nLanguage, loadLocaleMessages } from './i18n'
import {supportedLocalesInclude} from './i18n/supported-locales'

const routes = [
	{
		path: '/',
		name: 'app',
		component: App,
		children: [
			{
				path: '/:pathMatch(.*)*',
				name: 'NotFound',
				component: () => import('./components/NotFound')
			},
			
			{
				path: '/:locale/',
				name: 'dashboard',
				component: () => import('./components/Dashboard')
			},
			{
				path: '/:locale/performance/monthly',
				name: 'performance-monthly',
				component: () => import('./components/Performance/Monthly')
			},
			{
				path: '/:locale/performance/daily',
				name: 'performance-daily',
				component: () => import('./components/Performance/Daily')
			},
			{
				path: '/:locale/performance/hourly',
				name: 'performance-hourly',
				component: () => import('./components/Performance/Hourly')
			},
			{
				path: '/:locale/allocation/markets',
				name: 'allocation-by-markets',
				component: () => import('./components/Allocation/Markets')
			},
			{
				path: '/:locale/import',
				name: 'import',
				component: () => import('./components/Import')
			},
			{
				path: '/:locale/settings',
				name: 'settings',
				component: () => import('./components/Settings')
			},
			{
				path: '/:locale/login',
				name: 'login',
				component: () => import('./components/Login')
			}
		]
	}
]

export function setUpRouter(i18n) {
	const locale =
		i18n.mode === 'legacy' ? i18n.global.locale : i18n.global.locale.value

	const router = createRouter({
		history: createWebHashHistory(),
		routes,
	});

	// navigation guards
	router.beforeEach(async to => {
		const paramsLocale = to.params.locale

		// use locale if paramsLocale is not in SUPPORT_LOCALES
		if (!supportedLocalesInclude(paramsLocale)) {
			return `/${locale}`
		}

		// load locale messages
		if (!i18n.global.availableLocales.includes(paramsLocale)) {
			await loadLocaleMessages(i18n, paramsLocale)
		}

		// set i18n language
		setI18nLanguage(i18n, paramsLocale)
	})

	return router
}

