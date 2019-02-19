<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false, // Access-Control-Allow-Credentials	是否携带 Cookie
    'allowedOrigins' => ['*'], // Access-Control-Allow-Origin	允许的域名
    'allowedOriginsPatterns' => [], // Access-Control-Allow-Origin	通过正则匹配允许的域名
    'allowedHeaders' => ['*'], // Access-Control-Allow-Headers	允许的 Header
    'allowedMethods' => ['*'], // Access-Control-Allow-Methods	允许的 HTTP 方法
    'exposedHeaders' => [], // Access-Control-Expose-Headers  除了 6 个基本的头字段，额外允许的字段
    'maxAge' => 0, // Access-Control-Max-Age  预检请求的有效期

];
