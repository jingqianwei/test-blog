<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 13:36
 */

namespace App\DesignPattern;

/**
 * 大众车的子工厂
 * Class VwCarAbstractFactory
 * @package App\Services
 */
class VwCarAbstractFactory extends AbstractFactory
{
    /**
     * @return mixed
     */
    public function produceLowEndCar()
    {
        return new VwLowEndCar();
    }

    /**
     * @return mixed
     */
    public function produceHeightEndCar() // <----可以生产高端车了
    {
        return new VwHeightEndCar();
    }
}
