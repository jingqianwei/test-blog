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
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
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
}
