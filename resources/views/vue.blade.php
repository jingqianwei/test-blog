<!Doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <link rel="icon" href="/images/touch-icon.png" />
    <title>追风少年的博客</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/blog.css') }}">
</head>
<body>
<!--定义一个挂载点--->
<div id="app">
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
