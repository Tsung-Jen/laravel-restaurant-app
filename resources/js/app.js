import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import AdminLayout from './layouts/AdminLayout.vue';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true });
        const page = pages[`./pages/${name}.vue`];

        if (name.startsWith('Admin/')) {
            page.default.layout = page.default.layout || AdminLayout;
        }

        return page;
    },
    progress: {
        delay: 200,
        color: '#d97706',
        includeCSS: true,
        showSpinner: false,
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, typeof Ziggy !== 'undefined' ? Ziggy : {})
            .mount(el);
    },
});
