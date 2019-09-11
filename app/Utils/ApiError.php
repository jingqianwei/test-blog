<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/7/18
 * Time: 17:48
 */

namespace App\Utils;

use Illuminate\Http\Response;

/**
 * 接口错误文档
 * Class ApiErr
 * @package App\Utils
 */
class ApiError extends Response
{
    const SUCCESS = [0,'Success'];
    const UNKNOWN_ERR = [1,'未知错误'];
    const ERR_URL = [2,'访问接口不存在'];
    const TOKEN_ERR = [3,'TOKEN错误'];
    const NO_TOKEN_ERR = [4,'TOKEN不存在'];
    const USER_NOT_EXIST = [5,'用户不存在'];

    //TODO ...
}
