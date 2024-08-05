import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        let app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: {
                        ...Aura,
                        semantic: {
                            ...Aura.semantic,
                            primary: {
                                50: '{purple.50}',
                                100: '{purple.100}',
                                200: '{purple.200}',
                                300: '{purple.300}',
                                400: '{purple.400}',
                                500: '{purple.500}',
                                600: '{purple.600}',
                                700: '{purple.700}',
                                800: '{purple.800}',
                                900: '{purple.900}',
                            },
                        },
                    },
                    options: {
                        darkModeSelector: '.dark-app'
                    }
                }
            })
            .use(ConfirmationService)
            .use(ToastService)
            .mount(el);
        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
