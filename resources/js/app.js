import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import Layout from "./Layout/main.vue";
import { defineRule } from "vee-validate";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || Layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        defineRule("required", (value) => {
            if (!value) {
                return `This field is required`;
            }
            return true;
        });
        defineRule("min", (value, min) => {
            // The field is empty so it should pass
            if (!Array.isArray(value) || !value.length) {
                return true;
            }
            const numericValue = value.length;
            if (numericValue < min) {
                return `This field must be greater than ${min}`;
            }
            return true;
        });
        defineRule("max", (value, max) => {
            // The field is empty so it should pass
            if (!Array.isArray(value) || !value.length) {
                return true;
            }
            const numericValue = value.length;
            if (numericValue > max) {
                return `Maximum of ${max} exceeded`;
            }
            return true;
        });
        defineRule("minMax", (value, [min, max]) => {
            // The field is empty so it should pass
            if (!Array.isArray(value) || !value.length) {
                return true;
            }
            const numericValue = value.length;
            if (numericValue < min) {
                return `This field must be greater than ${min}`;
            }
            if (numericValue > max) {
                return `Maximum of ${max} exceeded`;
            }
            return true;
        });
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
