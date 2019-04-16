<template>
    <div>
        <Head></Head>
        <Foot></Foot>
    </div>
</template>

<script>
    // 引入组件
    import Head from './layout/Head.vue';
    import Foot from './layout/Foot.vue';
    export default {
        name: "App",
        components: {Head, Foot} // 注册组件
    }
</script>

<style scoped>

</style>
