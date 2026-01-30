import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from '@vitejs/plugin-vue';

const port = 5173;
const origin = `${process.env.DDEV_PRIMARY_URL}:${port}`;
//const origin = `${process.env.DDEV_PRIMARY_URL_WITHOUT_PORT}:${port}`

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/**/*.blade.php",
                "vendor/filament/**/*.blade.php",
            ],
            refresh: [...refreshPaths, "app/Livewire/**"],
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        host: "0.0.0.0",
        port,
        strictPort: true,
        origin,
        cors: {
            origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(\.ddev\.site)(?::\d+)?$/,
        },
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
