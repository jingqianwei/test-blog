<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/2/26
 * Time: 10:45
 */

namespace App\Http\Controllers;


use App\Curl\Curl;
use App\Curl\JsonHttpCurlDriver;
use App\Mail\OrderShipped;
use App\Models\Order;
use App\Services\Factory;
use App\Services\VwCarAbstractFactory;
use App\Services\VwCarFactory;

/**
 * 设计模式的使用
 * Class TestModeController
 * @package App\Http\Controllers
 */
class TestModeController extends Controller
{
    protected $curl;

    public function __construct(JsonHttpCurlDriver $json)
    {
        $this->curl = new Curl($json); //假设返回的是 json 数据
    }

    public function getProductList()
    {
        // 封装curl类的测试
        return $this->curl->get('http://atest.woaap.com:11038/get/send/sms/code', ['mobile'=> '15999645710']);
    }

    public function test()
    {
        //工厂模式
        $factory = new Factory();
        $vw = $factory->produce(1); //生产大众车
        dd($vw); //输出 object(VwCar)#3 (0) { }

        //工厂方法模式
        $factory = new VwCarFactory(); //实例化大众车工场
        $vw = $factory->produce(); //生产大众车
        dd($vw); //输出 object(VwCar)#3 (0) { }

        //抽象工厂模式
        $factory = new VwCarAbstractFactory(); //实例化大众车工厂
        $VwLowEndCar = $factory->produceLowEndCar(); //生产大众低端车
        $VwHeightEndCar = $factory->produceHeightEndCar(); // 生产大众高端车
        dd($VwHeightEndCar); //输出 object(VwHeightEndCar)#3 (0) { }
        dd($VwLowEndCar); //输出 object(VwLowEndCar)#3 (0) { }

        // 测试发送邮件
        $order = Order::findOrFail(1); // 查询出订单集合
        \Mail::to('jqw@qq.com')
            ->cc('chinwe@etocrm.com') // 接收者
            ->send(new OrderShipped($order));
    }
}
