<template>
    <div class="app">
        <Heads></Heads>
        <Main></Main>
        <Foot></Foot>
        <BackTop></BackTop>
    </div>
</template>

<script>
    // 引入组件
    import Heads from './layout/Head.vue';
    import Main from './layout/Main.vue';
    import Foot from './layout/Foot.vue';
    import BackTop from './components/BackTop.vue';
    export default {
        name: "App",
        components: {Heads, Main, Foot, BackTop} // 注册组件
    }
</script>

<style scoped>

</style>
