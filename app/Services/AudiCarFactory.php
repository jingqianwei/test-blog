<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 11:19
 */

namespace App\Services;


class AudiCarFactory extends MethodFactory
{

    public function produce()
    {
        return new AudiCar();
    }
}
