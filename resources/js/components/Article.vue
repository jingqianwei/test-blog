<template>
    <main class="app-main blog-list">
        <div class="app-viewport">
            <div class="post-list">
                <div class="post-list__main">
                    <article class="post-items">
                        <div id="post-list-wrap">
                            <section class="post-item">
                                <aside class="post-item__head"><h3 class="post-item__title"><a class="gradient-link"
                                                                                               href="/blog/d5uvpu">如何实现一个颜色选择器</a>
                                </h3>
                                    <div class="post-item__info"><span>作者：Smohan</span><span>栏目：<a
                                        href="/blog/c/580e36616dd7c320d45984aa" class="g-link">前端开发</a></span></div>
                                    <div class="post-item__time"><span class="day gradient-text">22</span><span
                                        class="month">Feb</span><span class="year">2019</span></div>
                                </aside>
                                <div class="post-item__body clearfix">
                                    <figure class="post-item__thumbnail g-flicker"><a href="/blog/d5uvpu"><img
                                        src=""
                                        alt="如何实现一个颜色选择器" class="lazy in"></a></figure>
                                    <div class="markdown-body post-item__contents post-contents"><p>
                                        在开发公司UI组件库的过程中，颜色组件ColorPicker由于时间关系没有去深入研究，看着花花绿绿的色谱图，以为实现起来会很复杂，就直接将一个开源的颜色选择器封装了一下。这大概是半年以前的事了，这篇文章也在我的博客中以草稿形式（只有标题没有内容，当时是做了一个记录，想着后来有时间了去研究，目前这种草稿还有很多😂）存放了半年了。前段时间请教了公司UI同事一些颜色相关的概念，又去搜索了下这方面的知识，收获还是蛮大的，尤其是知乎<a
                                        href="https://www.zhihu.com/question/22077462" title="null" target="_blank">色彩空间中的
                                        HSL、HSV、HSB 有什么区别？</a>这篇提问中，<a href="https://www.zhihu.com/people/forrest-72"
                                                                       title="null" target="_blank">@Forrest</a>近乎大白话的回答，超赞👍。
                                    </p></div>
                                </div>
                                <aside class="post-item__foot clearfix">
                                    <div class="post-item__tags pull-left text-overflow">
                                        <svg class="svg-icon" height="16" width="16" aria-hidden="true">
                                            <use xlink:href="#icon-bookmaks"></use>
                                        </svg>
                                        <a href="/blog/t/color-picker" class="tag">color-picker</a> <a
                                        href="/blog/t/%E9%A2%9C%E8%89%B2%E9%80%89%E6%8B%A9%E5%99%A8"
                                        class="tag">颜色选择器</a> <a href="/blog/t/%E8%89%B2%E7%9B%B8" class="tag">色相</a> <a
                                        href="/blog/t/hsv" class="tag">hsv</a> <a href="/blog/t/rgb2hsv" class="tag">rgb2hsv</a>
                                    </div>
                                    <div class="post-item__count pull-right text-overflow"><span class="count"><svg
                                        class="svg-icon" height="18" width="18" aria-hidden="true"
                                        style="position:relative;top:-1px"><use
                                        xlink:href="#icon-eye"></use></svg> <strong>531</strong></span><a
                                        href="/blog/d5uvpu#comments" class="count g-link">
                                        <svg class="svg-icon" height="16" width="16" aria-hidden="true">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <strong>5</strong></a></div>
                                </aside>
                            </section>
                        </div>
                        <section class="post-item posts-loading" id="posts-loading">
                            <div class="posts-loading__head">
                                <div class="posts-loading__item posts-loading__title"></div>
                                <div class="posts-loading__item posts-loading__info"></div>
                            </div>
                            <div class="posts-loading__body">
                                <div class="posts-loading__item posts-loading__thumb"></div>
                                <div class="posts-loading__content">
                                    <div class="posts-loading__item"></div>
                                    <div class="posts-loading__item"></div>
                                    <div class="posts-loading__item"></div>
                                    <div class="posts-loading__item"></div>
                                    <div class="posts-loading__item"></div>
                                </div>
                            </div>
                            <div class="posts-loading__item posts-loading__foot"></div>
                        </section>
                    </article>
                </div>
                <aside class="post-list__aside">
                    <div class="post-aside__item search-category">
                        <div class="post-search">
                            <form class="post-search__form js-search-form"><label class="post-search__label"><input
                                type="search" placeholder="检索..." spellcheck="false" autocomplete="off" id="search">
                                <span class="search-btn"><svg class="svg-icon" height="16" width="16"
                                                              aria-hidden="true"><use
                                    xlink:href="#icon-search"></use></svg></span></label></form>
                        </div>
                        <div class="post-search-category">
                            <div class="post-categroy shown js-category-wrap">
                                <ul id="blog-category">
                                    <li class="post-category__item" data-index="0">
                                        <a href="/blog/" class="actived">全部博文</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="post-search__result js-search-wrap">
                                <ul class="post-search__list" id="post-search-list"></ul>
                                <div class="post-search__loading" id="post-search-loading">
                                    <div class="mo-loading"><span class="hour"></span> <span class="minute"></span>
                                    </div>
                                    <span class="gradient-text">Turn back the clock...</span></div>
                                <div class="post-search__empty" id="post-search-empty"><img
                                    src="" class="in" alt="qrcode for gh"
                                    data-suffix="none">
                                    <p class="gradient-text">{ 关注公众号，获取最新文章 }</p></div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        name: "Article"
    }
</script>

<style scoped>

</style>
