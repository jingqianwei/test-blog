<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 11:32
 */

namespace App\Services;

/**
 * 总工厂
 * Class AbstractFactory
 * @package App\Services
 */
abstract class AbstractFactory
{
    abstract public function produceLowEndCar();
}
