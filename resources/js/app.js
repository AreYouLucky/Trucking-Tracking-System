import "./bootstrap";
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import vuetify from "./vuetify";
import 'leaflet/dist/leaflet.css';
import 'leaflet-routing-machine'; // Adds L.Routing onto L
import 'lrm-graphhopper';
import axios from 'axios';



const componentContext = import.meta.glob('./components/**/*.vue',{ eager: true });

const app = createApp({});

for (const path in componentContext) {
    const componentModule = componentContext[path];
    const fileName = path.split('/').pop().replace(/\.\w+$/, '');
  
    const component = componentModule.default;
    app.component(fileName, component);
  };
  
app.use(vuetify).mount("#app");
