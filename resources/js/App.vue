<template>
    <div class="app">
        <top></top>
        <router-view></router-view>
        <foot></foot>
        <backTop></backTop>
    </div>
</template>

<script>
    // 引入组件
    import top from './layout/Top';
    import foot from './layout/Foot';
    import backTop from './layout/BackTop';
    export default {
        name: "App",
        components: {top, foot, backTop}, // 注册组件
        created() {
            console.log('router:', this.$router, 'route:', this.$route) // 打印路由
        }
    }
</script>

<style scoped>

</style>
