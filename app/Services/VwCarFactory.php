<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:48
 */

namespace App\Services;

/**
 * 子工厂
 * Class VwCarFactory
 * @package App\Services
 */
class VwCarFactory extends MethodFactory
{
    /**
     * @return VwCar|mixed
     */
    public function produce()
    {
        return new VwCar();
    }
}
