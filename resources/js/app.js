import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue';
//import elementUI from 'element-ui';
//import 'element-ui/lib/theme-chalk/index.css';
//Vue.use(elementUI, {size: 'medium'}); // 将elementUI引入vue中

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
