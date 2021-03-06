<!Doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
<div id="test">
    <input type="button" id="btnConnection" value="连接" />
    <input type="button" id="btnClose" value="关闭" />
    <input type="button" id="btnSend" value="发送" />
    <a href="{{ url('/test-curl') }}">运行WebSocket</a>
</div>
<script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        let socket;
        if(typeof(WebSocket) == "undefined") {
            alert("您的浏览器不支持WebSocket");
            return false;
        }
        $("#btnConnection").click(function() {
            //实现化WebSocket对象，指定要连接的服务器地址与端口
            socket = new WebSocket("ws://101.132.75.39:9502");
            //打开事件
            socket.onopen = function() {
                alert("Socket 已打开");
            };
            //获得消息事件
            socket.onmessage = function(msg) {
                alert(msg.data);
            };
            //关闭事件
            socket.onclose = function() {
                alert("Socket已关闭");
            };
            //发生了错误事件
            socket.onerror = function() {
                alert("发生了错误");
            };
        });

        //发送消息
        $("#btnSend").click(function() {
            let message = {
                title: '这是来自客户端的消息',
                nickname: 'user' + Math.ceil((Math.random() * 1000)),
                mobile: '1599964571' + Math.ceil((Math.random() * 1000)),
                comment: '小白',
                time: new Date(),
                url: location.href
            };

            socket.send(JSON.stringify(message));
        });

        //关闭
        $("#btnClose").click(function() {
            socket.close();
        });
    });
</script>
</body>
</html>
