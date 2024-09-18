import { createApp } from 'vue';
import axios from 'axios'; // Import axios
import '../css/app.css';
import Home from "./components/Home.vue";

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const auth_token = localStorage.getItem('token');
if (auth_token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${auth_token}`;
}

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}

const app = createApp({});
app.component('Home', Home);
app.mount('#app');
