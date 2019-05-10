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
use App\Events\TestRegistered;
use App\Jobs\ExerciseQueue;
use App\Jobs\RegisterSendPoint;
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
        $this->swooleTest();
        // 封装curl类的测试
        return $this->curl->get('http://atest.woaap.com:11038/get/send/sms/code', ['mobile'=> '15999645710']);
    }

    public function testRegister()
    {
        // 假装前面已经注册成功了

        // 测试队列执行，走default队列，异步执行
        $this->dispatch(new ExerciseQueue());

        // 用队列送积分，走register队列，异步执行
        $this->dispatch((new RegisterSendPoint())->onQueue('register'));

        // 用监听事件送积分，默认同步执行
        event(new TestRegistered());
    }

    /**
     * @param array $param
     */
    public function swooleTest($param = ['s_id'=>2, 'info'=>'info'])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://101.132.75.39:9502");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $param;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_exec($ch);
        curl_close($ch);
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


    /**
     * 测试根据不同的设备加载不同的模板文件
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templateView()
    {
        return view('registration.index');
    }
}
