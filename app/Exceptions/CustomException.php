<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

/**
 * 自定义错误输出
 * Class CustomException
 * @package App\Exceptions
 */
class CustomException extends Exception
{
    public function __construct($message, int $code = 200)
    {
        parent::__construct($message, $code);
    }

    /**
     * 报告异常， 这样不用在Handler里面使用，直接就会输出到页面
     *
     * @param Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        //
    }

    /**
     * 转换异常为 HTTP 响应
     * 直接就会输出到页面
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function render(Request $request, Exception $e)
    {
        $code = $e->getCode() ?: 500;
        $errMsg = $e->getMessage() ?: '系统发生了一点错误(' . $code . ')';
        if ($request->expectsJson() || $request->isJson()) {
            return response([
                'errCode' => $code,
                'errMsg' => $errMsg,
            ]);
        } else {
            $view = 'errors.default';
            if (view()->exists('errors' . $code)) { // 如果存在对应的错误页面
                $view = 'errors' . $code;
            }

            return response()->view($view, [
                'code'=> $code,
                'msg' => $errMsg,
            ]);
        }
    }
}
