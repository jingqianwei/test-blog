<template>
    <div class="app">
        <Header></Header>
        <router-view></router-view>
        <Footer></Footer>
        <BackTop></BackTop>
    </div>
</template>

<script>
    // 引入组件
    import Header from './layout/Header';
    import Footer from './layout/Footer';
    import BackTop from './components/BackTop';
    export default {
        name: "App",
        components: {Header, Footer, BackTop}, // 注册组件
        created() {
            console.log('router:',this.$router, 'route:',this.$route) // 打印路由
        }
    }
</script>

<style scoped>

</style>
