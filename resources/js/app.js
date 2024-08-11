import { createApp } from 'vue';
import '../css/app.css';
import Home from "./components/Home.vue";

const app = createApp({});
app.component('Home', Home);
app.mount('#app');
