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
            console.log('router:', this.$router, 'route:', this.$route, 'vuex参数为：', this.$store.state.count); // 打印路由

            this.$store.commit('increment');
            console.info('计算后的结果为', this.$store.state.count, this.$store.state.detail.item)
        },
        computed: { // 计算属性

        },
        watch: { // 侦听器

        },
        methods: { // 函数

        }
    }
</script>

<style scoped>

</style>
