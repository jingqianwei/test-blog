<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:45
 */

namespace App\Http\Controllers;


use App\Services\Factory;
use App\Services\VwCarFactory;

class TestModeController extends Controller
{
    public function test()
    {
        // 工厂模式
        $factory = new Factory();
        $vw = $factory->produce(1); //生产大众车
        dd($vw); //输出 object(VwCar)#3 (0) { }

        //工厂方法模式
        $factory = new VwCarFactory(); //实例化大众车工场
        $vw = $factory->produce(); //生产大众车
        dd($vw); //输出 object(VwCar)#3 (0) { }
    }
}
