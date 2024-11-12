import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler.js";
import vuetify from "./vuetify";
import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

const componentContext = import.meta.glob("./components/**/*.vue", {
    eager: true,
});

const app = createApp({});

for (const path in componentContext) {
    const componentModule = componentContext[path];
    const fileName = path
        .split("/")
        .pop()
        .replace(/\.\w+$/, "");

    const component = componentModule.default;
    app.component(fileName, component);
}

app.use(vuetify).mount("#app");
