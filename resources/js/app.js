import './bootstrap';
import { createApp } from 'vue'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import ItemList from './components/ItemList.vue'
import 'bootstrap-icons/font/bootstrap-icons.css'
import '../css/app.css';


const app = createApp(ItemList)
app.config.globalProperties.$axios = axios
app.mount('#app')