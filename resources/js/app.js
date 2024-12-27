import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import ToastPlugin from 'vue-toast-notification';
//import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import { createApp } from 'vue';
import App from './App.vue';  // Import the main App.vue component
import store from './store';
import router from "./router/index.js";


// Create the Vue app
const app = createApp(App);  // Pass App.vue to the createApp method

// Use the router and store in the app instance
app.use(router);
app.use(store);
app.use(ToastPlugin);
app.mount('#app');
