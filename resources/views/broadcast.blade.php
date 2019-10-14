<!Doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>laravel广播</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
    <div class="content">
        广播练习
    </div>
    <script type="text/javascript" src="{{ mix('js/bootstrap.js') }}"></script>
    <script type="text/javascript">
        Echo.channel('news') // 广播频道名称
            .listen('\\broadcast.news', (e) => { // 消息名称。由于自定义了消息名称所以需要在名称前加.或者加\\
                console.log(e);
            });
    </script>
    </body>
</html>
