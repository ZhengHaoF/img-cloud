import { createApp } from 'vue'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import App from './App.vue'
import {router} from './route'
import store from './store'
import axios from 'axios'
import VueAxios from 'vue-axios'


const app = createApp(App)
app.use(VueAxios, axios)
app.use(router);
app.use(ElementPlus);
app.use(store);
app.mount('#app');
document.title = "Z-ImgCloud"