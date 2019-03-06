<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    public function render(Request $request, Exception $e)
    {
        $errMsg = $e->getMessage() ?: '系统发生了一点错误(' . $e->getCode() . ')';
        if ($request->expectsJson() || $request->isJson()) {
            return response([
                'errCode' => $e->getCode() ?: 500,
                'errMsg' => $errMsg,
            ]);
        } else {
            return response()->view('errors.default', [
                'code'=> $e->getCode() ?: 500,
                'msg' => $errMsg,
            ]);
        }
    }
}
