<template>
    <div class="app">
        <Head></Head>
        <el-main>111</el-main>
        <Foot></Foot>
        <BackTop></BackTop>
    </div>
</template>

<script>
    // 引入组件
    import Head from './layout/Head.vue';
    import Foot from './layout/Foot.vue';
    import BackTop from './components/BackTop.vue';
    export default {
        name: "App",
        components: {Head, Foot, BackTop} // 注册组件
    }
</script>

<style scoped>

</style>
