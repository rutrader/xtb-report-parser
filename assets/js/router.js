import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';

const routes = [
	{
		path: '/',
		name: 'app',
		component: App,
		children: [
			{
				path: '',
				name: 'dashboard',
				component: () => import('./components/Dashboard')
			},
			{
				path: '/time-stats/by-hours',
				name: 'time-stats-by-hours',
				component: () => import('./components/TimeStats/ByHours')
			},
			{
				path: '/time-stats/by-days',
				name: 'time-stats-by-days',
				component: () => import('./components/TimeStats/ByDays')
			},
			{
				path: '/time-stats/by-months',
				name: 'time-stats-by-months',
				component: () => import('./components/TimeStats/ByMonths')
			},
			{
				path: '/stats/by-markets',
				name: 'stats-by-markets',
				component: () => import('./components/CommonStats/ByMarkets')
			}
		]
	}
]


const router = createRouter({
	history: createWebHashHistory(),
	routes,
});

export default router;

