<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/27
 * Time: 16:26
 */

namespace App\Exceptions;


use Exception;
use Illuminate\Http\Request;

class ResponseNotXMLException extends Exception
{
    public function __construct($message, int $code = 200)
    {
        parent::__construct($message, $code);
    }

    // 当这个异常被触发时，会调用 render 方法来输出给用户
    public function render(Request $request)
    {
        // 如果用户通过 Api 请求，则返回 JSON 格式的错误信息
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->message], $this->code);
        }

        // 否则返回上一页并带上错误信息
        return redirect()->back()->withErrors(['xml' => $this->message]);
    }
}
