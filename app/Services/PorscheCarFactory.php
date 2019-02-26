<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 13:34
 */

namespace App\Services;


class PorscheCarFactory extends MethodFactory
{
    /**
     * @return mixed
     */
    public function produce()
    {
        return new PorscheCar();
    }
}
