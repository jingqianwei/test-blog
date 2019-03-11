<?php

namespace App\Http\Middleware;

use Closure;

class Template
{
    // 白名单
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $result = new WhichBrowser\Parser(getallheaders());
        // 如果是桌面类型, 返回true
        $isDesktop = $result->isType('desktop');
        if ($isDesktop) {
            // 加载pc端的模板文件
            $path = resource_path('views' . DIRECTORY_SEPARATOR . 'pc');
        } else {
            // 加载mobile端的模板文件
            $path = resource_path('views' . DIRECTORY_SEPARATOR . 'mobile');
        }
        // 获取视图查找器实例
        $view = app('view')->getFinder();
        // 重新定义视图目录
        $view->prependLocation($path);
        // 返回请求
        return $next($request);
    }
}
