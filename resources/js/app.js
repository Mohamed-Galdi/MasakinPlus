import "../css/app.css";
import "./bootstrap";
import "intl-tel-input/build/css/intlTelInput.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura"; // Import the desired theme
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
import Tooltip from "primevue/tooltip";
import "primeicons/primeicons.css";
import { FontAwesomeIcon } from "./plugins/fontawesome";
import { formatCurrency, formatDate } from "./plugins/formatters";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${appName} ${title}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura, // Using the Aura theme as an example
                    options: {
                        prefix: "p",
                        darkModeSelector: "light", // force light mode
                        cssLayer: false,
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .directive("tooltip", Tooltip)
            .component("Icon", FontAwesomeIcon); // Register FontAwesome component globally

        // Add global properties for formatters
        app.config.globalProperties.$formatCurrency = formatCurrency;
        app.config.globalProperties.$formatDate = formatDate;

        return app.mount(el);
    },
    progress: {
        color: "#36bda7",
    },
});
