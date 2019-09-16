<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 11:19
 */

namespace App\DesignPattern;

/**
 * 子工厂
 * Class AudiCarFactory
 * @package App\Services
 */
class AudiCarFactory extends MethodFactory
{
    /**
     * @return AudiCar
     */
    public function produce()
    {
        return new AudiCar();
    }
}
