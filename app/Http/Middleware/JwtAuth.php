<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Utils\ApiError;
use Closure;
use App\Utils\JwtAuth as ApiJwtAuth;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param int $type 支持中件间传参,在路由中直接加
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next, $type = 0)
    {
        //中间件中不能用json_encode
        $token = $request->token;

        if ($token) {
            $jwtAuth = ApiJwtAuth::getInstance();
            $jwtAuth->setToken($token);
            if ($jwtAuth->validate() && $jwtAuth->verify()) {
                return $next($request);
            } else {
                throw new ApiException(ApiError::TOKEN_ERR);
            }
        } else {
            throw new ApiException(ApiError::NO_TOKEN_ERR);
        }
    }

    // 用于在返回结果之后调用
    public function terminate($request, $response)
    {
        // 存储 session 数据...
        \Log::info('结束之后的数据', [$request->ip(), $request->old()]);
        // 结果返回之后调用
        // 记录 API 的响应结果
    }
}
