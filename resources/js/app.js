window.Vue = require('vue');
import router from './router'
//import elementUI from 'element-ui';
//import 'element-ui/lib/theme-chalk/index.css';
//Vue.use(elementUI, {size: 'medium'}); // 将elementUI引入vue中
import App from './App.vue';

new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
