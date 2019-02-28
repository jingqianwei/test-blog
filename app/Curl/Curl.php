<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/27
 * Time: 17:55
 */

namespace App\Curl;

use App\Exceptions\ResponseNotJsonException;
use App\Exceptions\ResponseNotXMLException;

/**
 * 实现对外调用的 cURL 类
 * Class Curl
 * @package App\Curl
 * @method get(string $string, array $array)
 */
class Curl
{
    private $curlDriver;

    public function __construct(CurlInterfaceDriver $curl)
    {
        $this->curlDriver = $curl;
    }

    //在对象中调用一个不可访问方法时，__call() 会被调用
    public function __call($name, $arguments)
    {
        $this->curlDriver->{$name}(...$arguments);

        try {
            return ApiDataArrayFactory::make($this->curlDriver);
        } catch (ResponseNotJsonException $e) {
        } catch (ResponseNotXMLException $e) {
        }
    }
}
