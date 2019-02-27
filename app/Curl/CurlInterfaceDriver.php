<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/27
 * Time: 15:33
 */

namespace App\Curl;


interface CurlInterfaceDriver
{
    public function get($url, array $options = []);

    public function post($url, array $options = []);

    public function request($method, $url, array $options = []);
}
