import { createApp } from "vue";
import App from "./App.vue";
import router from "./router"; // si tu as un routeur
import "./style.css";
import {createPinia} from "pinia";

const pinia = createPinia();

const app = createApp(App);

app.use(pinia);
app.use(router); // tu peux commenter cette ligne si tu n’as pas encore de router
app.mount("#app");
