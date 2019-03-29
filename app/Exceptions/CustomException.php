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
