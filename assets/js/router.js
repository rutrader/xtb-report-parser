import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';

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
				path: '',
				name: 'dashboard',
				component: () => import('./components/Dashboard')
			},
			{
				path: '/performance/monthly',
				name: 'performance-monthly',
				component: () => import('./components/Performance/Monthly')
			},
			{
				path: '/performance/daily',
				name: 'performance-daily',
				component: () => import('./components/Performance/Daily')
			},
			{
				path: '/performance/hourly',
				name: 'performance-hourly',
				component: () => import('./components/Performance/Hourly')
			},
			{
				path: '/allocation/markets',
				name: 'allocation-by-markets',
				component: () => import('./components/Allocation/Markets')
			},
			{
				path: '/import',
				name: 'import',
				component: () => import('./components/Import')
			},
			{
				path: '/settings',
				name: 'settings',
				component: () => import('./components/Settings')
			}
		]
	}
]


const router = createRouter({
	history: createWebHashHistory(),
	routes,
});

export default router;

