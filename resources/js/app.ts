import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css'
import { VueQueryPlugin } from '@tanstack/vue-query'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: {
                        ...Aura,
                        semantic: {
                            ...Aura,
                            primary: {
                                50: '{gray.50}',
                                100: '{gray.100}',
                                200: '{gray.200}',
                                300: '{gray.300}',
                                400: '{gray.400}',
                                500: '{gray.800}',
                                600: '{gray.600}',
                                700: '{gray.700}',
                                800: '{gray.800}',
                                900: '{gray.900}',
                                950: '{gray.950}'
                            },
                        },
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .use(VueQueryPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
