import Vue from 'vue'
import VueRouter from 'vue-router'

// 内容组件
import Index from '../components/Index'
import Article from '../components/Article'
import Message from '../components/Message'
import Laboratory from '../components/Laboratory'
import More from '../components/More'

Vue.use(VueRouter);

const routes = [
    {
        path: '/index',
        name: 'index',
        component: Index,
    },
    {
        path: '/article',
        name: 'article',
        component: Article,
    },
    {
        path: '/message',
        name: 'message',
        component: Message,
    },
    {
        path: '/laboratory',
        name: 'laboratory',
        component: Laboratory,
    },
    {
        path: '/more',
        name: 'more',
        component: More,
    },
    {
        path: '*',
        redirect: '/index',
    }
];

const router = new VueRouter({
    routes: routes,
    base:'vue', // 基础路径
    mode: 'history'
});

export default router

