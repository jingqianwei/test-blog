<!DOCTYPE html>
<html lang="en" xmlns="">
<head>
    <meta charset="UTF-8">
    <title>error</title>
    <link rel="stylesheet" type="text/css" href="{{env('ASSETS_CDN_URL')}}/css/error.css">
</head>
<body>
    <main>
        <div class="err">
            <div class="err_content">
                <div class="err_left"><img src="/img/sad.png" /></div><div class="err_right">
                    <h1>出错啦! {{ $code }}</h1>
                    <p>{{$msg or '您访问的页面出错啦. 请刷新试试, 或稍等几分钟后重试.'}}</p>
                </div>
            </div>
            <div class="err_bottom">
                <div>
                    <button onclick="reload()">
                        <div class="img"><img src="/img/refresh.png" /></div><p>刷新</p>
                    </button>
                </div><div>
                    <button onclick="back()">
                        <div class="img"><img src="/img/return.png" /></div><p>返回</p>
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    function reload(){
        window.location.reload();
    }
    function back(){
        window.history.back(-1)
    }
</script>
</html>
