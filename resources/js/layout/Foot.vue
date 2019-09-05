<template>
    <footer class="app-foot">
        <div class="mo-container">
            <div class="foot-top mo-clearfix">
                <h3 class="slogan mo-left">CHINWE · 游弋在代码里的人生！</h3>
                <form method="get" action="https://google.com.hk/search" target="_blank" autocomplete="off" class="google-search mo-right">
                    <label class="search-label">
                        <input type="text" name="q" spellcheck="false" maxlength="40" placeholder="Search..." class="search-input">
                        <button data-tipsy="站内搜索" class="search-btn mo-tipsy--top">
                            <i class="mo-icon-search"></i>
                        </button>
                    </label>
                </form>
            </div>
            <div class="copyright">
                <ul class="links">
                    <li><a href="https://smohan.net/about/" class="router-link-exact-active active">关于</a></li>
                    <li><a href="https://smohan.net/archives/" class="">归档</a></li>
                    <li><a href="https://smohan.net/sitemap.xml" target="_blank">Sitemap</a></li>
                    <li><a href="https://smohan.net/rss.xml" target="_blank">Rss</a></li>
                    <li><a href="https://s-mohan.github.io/demo/" target="_blank">Demo</a></li>
                </ul>
                <p>Copyright © 2012-2019 Chinwe. All Rights Reserved.盗版必究.V.6.2.1.蜀ICP备16016092号-2.</p>
                <div class="contact-way">
                    <a target="_blank" href="https://github.com/S-mohan/" data-tipsy="Github" class="mo-tipsy--top">
                        <i class="mo-icon-github"></i>
                    </a>
                    <a target="_blank" href="https://weibo.com/smohan/" data-tipsy="Weibo" class="mo-tipsy--top">
                        <i class="mo-icon-weibo"></i>
                    </a>
                    <a target="_blank" href="https://codepen.io/smohan/" data-tipsy="Codepen" class="mo-tipsy--top">
                        <i class="mo-icon-codepen"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
        name: "Foot"
    }
</script>

<style scoped>

</style>
