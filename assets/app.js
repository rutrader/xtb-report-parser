import './styles/global.scss';

import 'primevue/resources/primevue.min.css';
import 'primeflex/primeflex.css';
// import 'primeicons/primeicons.css';
import 'prismjs/themes/prism-coy.css';
import '../assets/styles/layout.scss';

import {createApp, reactive} from 'vue'
import { setupI18n } from './js/i18n'
import { setUpRouter } from './js/router'

import AppWrapper from './js/MainWrapper';
import PrimeVue from 'primevue/config';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import Button from 'primevue/button';
import Chart from 'primevue/chart';
import FileUpload from 'primevue/fileupload';
import Menu from 'primevue/menu';
import Message from 'primevue/message';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Tooltip from 'primevue/tooltip';
import Skeleton from 'primevue/skeleton';
import Dropdown from "primevue/dropdown";

import axios from 'axios';
import VueAxios from 'vue-axios';

import en from './js/i18n/translations/en.json'
import ru from './js/i18n/translations/ru.json'

const i18n = setupI18n({
	globalInjection: true,
	legacy: false,
	locale: 'en',
	fallbackLocale: 'en',
	messages: {}
})

const router = setUpRouter(i18n);

if (document.getElementById('app')) {
	router.beforeEach(function (to, from, next) {
		window.scrollTo(0, 0);
		next();
	});
	
	
	const app = createApp(AppWrapper)
	
	app.config.globalProperties.$appState = reactive({theme: 'lara-light-indigo', darkTheme: false});
	app.config.globalProperties.$locale = reactive({code: 'en'});

// app.config.compilerOptions.delimiters = ['${', '}']
	
	app.use(PrimeVue, {ripple: true, inputStyle: 'outlined'});
	app.use(router)
	app.use(ToastService)
	app.use(VueAxios, axios)
	app.use(i18n)
	
	app.directive('tooltip', Tooltip);
	app.directive('ripple', Ripple);
// app.directive('code', CodeHighlight);
	app.directive('badge', BadgeDirective);
	app.directive('styleclass', StyleClass);
	
	app.component('Badge', Badge);
	app.component('Chart', Chart);
	app.component('FileUpload', FileUpload);
	app.component('Toast', Toast)
	app.component('Menu', Menu)
	app.component('Button', Button)
	app.component('Message', Message)
	
	// app.component('DataTable', DataTable);
	// app.component('Column', Column)
	app.component('Skeleton', Skeleton)
	app.component('Dropdown', Dropdown)

// app.component('font-awesome-icon', FontAwesomeIcon)
	app.mount('#app')
}
