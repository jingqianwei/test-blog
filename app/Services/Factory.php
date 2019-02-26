<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:33
 */

namespace App\Services;

/**
 * 参考网址：https://learnku.com/articles/24372
 * 工厂模式
 * Class Factory
 * @package App\Services
 */
class Factory
{
    //这三个常量用于给客户端一个友好的提示，不要也行
    const VM = 1;
    const AUDI = 2;
    const PORSCHE = 3; // <----增加了一个常量

    /**
     * @param $type
     * @return AudiCar|PorscheCar|VwCar
     */
    public function produce($type)
    {
        switch ($type) {
            case self::VM:
                return new VwCar();
                break;
            case self::AUDI:
                return new AudiCar();
                break;
            case self::PORSCHE:  //  <----增加了一条产品线，用于生产保时捷
                return new PorscheCar();
                break;
        }
    }
}
