import Vue from 'vue'
import VueRouter from 'vue-router'

// 内容组件
import Index from '../components/Index'
import Article from '../components/Article'
import Message from '../components/Message'
import Laboratory from '../components/Laboratory'
import More from '../components/More'

// 文章详情页
import Detail from '../components/page/Detail'

Vue.use(VueRouter);

const routes = [
    {
        path: '',
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
        path: '/article/:id', // 文章详情
        name: 'detail',
        component: Detail,
    },
    {
        path: '*',
        redirect: '',
    }
];

export default new VueRouter({
    routes: routes,
    base: 'vue', // 基础路径
    mode: 'history',
    saveScrollPosition: true,
});

