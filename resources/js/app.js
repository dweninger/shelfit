import { createApp } from 'vue';
import axios from 'axios';
import '../css/app.css';
import Dashboard from './components/Dashboard.vue';
import Home from "./components/Home.vue";

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}

const app = createApp({});
app.component('Home', Home);
app.component('Dashboard', Dashboard);
app.mount('#app');
