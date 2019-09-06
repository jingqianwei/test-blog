<template>
    <header class="app-head">
        <div class="mo-container app-head__body">
            <a :href="homepage" class="app-logo active">水墨寒的博客</a>
            <nav class="app-nav">
                <ul class="app-nav__main">
                    <li><a :href="homepage">首页</a></li>
                    <li @click="jumpPage('article')"><a>文章</a></li>
                    <li @click="jumpPage('message')"><a>留言</a></li>
                    <li @click="jumpPage('laboratory')"><a>实验室</a></li>
                    <li class="dropdown active"><a class="dropdown-toggle">更多</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__item"><a href="https://smohan.net/links/">前端导航</a></li>
                            <li class="dropdown-menu__item router-link-exact-active active"><a
                                href="https://smohan.net/about/">关于我</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
    export default {
        name: "Head",
        data() {
            return {
                homepage: window.location.href // 首页地址
            }
        },
        methods: {
            jumpPage(path){
                this.$router.push({name: path});
            }
        }
    }
    console.log(window.location.href)
</script>

<style scoped>

</style>
