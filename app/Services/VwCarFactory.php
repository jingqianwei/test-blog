<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:48
 */

namespace App\Services;


class VwCarFactory extends MethodFactory
{

    public function produce()
    {
        return new VwCar();
    }
}
