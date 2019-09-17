import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// 配置NProgress的选项
//NProgress.configure({})
//import elementUI from 'element-ui';
//import 'element-ui/lib/theme-chalk/index.css';
//Vue.use(elementUI, {size: 'medium'}); // 将elementUI引入vue中

// 在路由页面跳转使用
router.beforeEach((to, from, next) => {
    // 开始进度条
    NProgress.start();

    // 继续路由
    next()
});

router.afterEach((to, from) => {
    // 结束进度条
    NProgress.done()
});

new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
