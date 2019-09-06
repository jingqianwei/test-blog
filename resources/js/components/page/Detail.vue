<template>
    <main class="app-main post-detail">
        <aside class="post-detail__banner">
            <figure class="g-banner-bg in" data-suffix="postBanner"
                    style="background-image: url(&quot;https://img.smohan.net/c4e769c9031225f3c39166368a15b05a.jpg?imageMogr2/format/webp/interlace/1/blur/15x15/quality/85|imageslim&quot;);"></figure>
            <div class="app-viewport"><h1 class="post-detail__title">基于Webpack/TypeScript/Koa的环境配置</h1>
                <p class="post-detail__tags"><span class="mo-tag mo-tag--primary">原创</span> <span
                    class="mo-tag mo-tag--primary-ghost"><span class="gradient-text">前端开发</span></span></p></div>
        </aside>
        <div class="app-viewport">
            <article class="post-detail_main markdown-body">
                <div class="mo-share clearfix" id="mo-share">
                    <ul class="mo-share__items">
                        <li class="mo-share__btn mo-share__item share-reward mo-dropdown"><a
                            class="mo-dropdown__toggle"><span>赏</span></a>
                            <div class="mo-dropdown__body dropdown-reward js-flip">
                                <div class="reward-item reward-item__front">
                                    <div class="reward-type"><span>微信</span> <span class="corner js-flip-toggle"
                                                                                   title="切换到支付宝"></span></div>
                                    <img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                        data-lazy="/static/img/wechat-reward.jpg" alt="微信赞助" class="js-lazy g-lazy-img">
                                    <span class="title">喝杯茶咯~</span></div>
                                <div class="reward-item reward-item__back">
                                    <div class="reward-type"><span>支付宝</span> <span class="corner js-flip-toggle"
                                                                                    title="切换到微信"></span></div>
                                    <img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                        data-lazy="/static/img/ali-reward.jpg" alt="支付宝赞助" class="js-lazy g-lazy-img">
                                    <span class="title">喝杯茶咯~</span></div>
                            </div>
                        </li>
                        <li class="mo-share__btn mo-share__item share-weibo"><a
                            href="http://service.weibo.com/share/share.php?url=https://smohan.net/blog/zkqfmq&amp;title=基于Webpack/TypeScript/Koa的环境配置&amp;appkey=678438995&amp;pic=https://img.smohan.net/c4e769c9031225f3c39166368a15b05a.jpg&amp;searchPic=true&amp;ralateUid=3061825921"
                            target="_blank">
                            <svg class="svg-icon" height="24" width="24" aria-hidden="true">
                                <use xlink:href="#icon-weibo"></use>
                            </svg>
                        </a></li>
                        <li class="mo-share__btn mo-share__item share-wechat mo-dropdown"><a
                            class="mo-dropdown__toggle">
                            <svg class="svg-icon" height="24" width="24" aria-hidden="true">
                                <use xlink:href="#icon-wechat"></use>
                            </svg>
                        </a>
                            <div class="mo-dropdown__body dropdown-qrcode"><img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                data-lazy="https://my.tv.sohu.com/user/a/wvideo/getQRCode.do?text=https://smohan.net/blog/zkqfmq&amp;width=500&amp;height=500"
                                class="js-lazy g-lazy-img"></div>
                        </li>
                        <li class="mo-share__btn mo-share__item share-twitter"><a
                            href="https://twitter.com/intent/tweet?text=基于Webpack/TypeScript/Koa的环境配置 https://smohan.net/blog/zkqfmq"
                            target="_blank">
                            <svg class="svg-icon" height="24" width="24" aria-hidden="true">
                                <use xlink:href="#icon-twitter"></use>
                            </svg>
                        </a></li>
                    </ul>
                </div>
                <section class="post-detail__comments"><h3 id="comments">评论<sup id="post-comments__count"> 「 ... 」</sup>
                </h3>
                    <div id="post-comments__form" style="margin-top:30px;position:relative;z-index:3">
                        <div class="comment-form is-focusin js-form">
                            <div class="comment-form__user">
                                <figure class="comment-form__avatar js-shown-user js-avatar"><img
                                    src="//img.smohan.net/avatar/default.gif" alt="Me" class="js-shown-avatar"></figure>
                                <div class="comment-form__nick"><a
                                    class="text-overflow js-shown-user js-shown-nick gradient-text">雁过留名</a></div>
                                <div class="popover popover-user-form" data-placement="right">
                                    <div class="form-row is-input"><input type="text" spellcheck="false"
                                                                          placeholder="起个牛逼点的昵称吧~" maxlength="50"
                                                                          class="js-input js-input-user js-fd-nick"
                                                                          title="起个牛逼点的昵称吧~"></div>
                                    <div class="form-row is-input"><input type="email" spellcheck="false"
                                                                          placeholder="电子邮箱" maxlength="50"
                                                                          class="js-input js-input-user js-fd-email"
                                                                          title="电子邮箱"></div>
                                    <div class="form-row is-input"><input type="url" spellcheck="false"
                                                                          placeholder="博客/Github地址" maxlength="100"
                                                                          class="js-input js-input-user js-fd-url"
                                                                          title="博客/Github地址"></div>
                                    <div class="form-row is-btns">
                                        <button type="button" class="cancel gradient-text js-user-cancel">取消</button>
                                        <button type="button" class="confirm mo-btn js-user-submit" disabled="">确定
                                        </button>
                                    </div>
                                </div>
                                <div class="popover-backdrop js-user-cancel"></div>
                            </div>
                            <div class="comment-form__body js-form-body">
                                <div class="comment-form__edit js-form-edit" tabindex="0" role="form"><textarea
                                    class="textarea js-input js-fd-content" spellcheck="false"
                                    placeholder="留点空白给你说..."></textarea>
                                    <div class="feedback">
                                        <svg class="svg-icon" height="48" width="48" aria-hidden="true"
                                             style="fill:url(#gradient-horizontal) currentColor">
                                            <use xlink:href="#icon-check_circle"></use>
                                        </svg>
                                        <span class="feedback-label gradient-text">发布成功，将在审核后展示...</span></div>
                                </div>
                                <div class="comment-form__bar clearfix" tabindex="0" role="button">
                                    <div class="mo-dropdown pull-left"><a
                                        class="mo-dropdown__toggle comment-form__emoji js-emoji-toggle">
                                        <svg class="svg-icon" height="24" width="24" aria-hidden="true">
                                            <use xlink:href="#icon-face" class="emoji-normal"></use>
                                            <use xlink:href="#icon-face-2" class="emoji-actived"></use>
                                        </svg>
                                    </a>
                                        <div class="mo-dropdown__body">
                                            <ul class="emoji-wrap js-emoji-wrap"></ul>
                                        </div>
                                    </div>
                                    <div class="comment-form__submit pull-right"><a
                                        class="comment-form__submittip g-link" href="/about/#comment" target="_blank">关于评论
                                        »</a>
                                        <button type="button" class="btn mo-btn js-form-submit" disabled="disabled">发布
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="post-comments__list" class="comments-list"></div>
                    <div id="post-comments__loading">
                        <div class="comments-loading">
                            <div class="comment-item">
                                <figure class="comment-item__avatar"></figure>
                                <div class="comment-item__body">
                                    <div class="comment-item__info"></div>
                                    <div class="comment-item__content"><span></span> <span></span> <span></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </main>
</template>

<script>
    export default {
        name: "Detail",
        created() {
            // 获取文章id
            let article_id = this.$route.params.id;
            console.log('文章id为：' + article_id)

            // 然后通过文章id，去查询数据库，获取文章详情，并渲染页面即可
        }
    }
</script>

<style scoped>

</style>
