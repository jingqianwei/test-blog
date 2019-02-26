<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:42
 */

namespace App\Services;

/**
 * 总工厂
 * Class MethodFactory
 * @package App\Services
 */
abstract class MethodFactory
{
    /**
     * @return mixed
     */
    abstract public function produce();
}
