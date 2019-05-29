<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * windows上使用方法 C:\phpStudy\WWW\test-blog\vendor\bin\phpunit --filter testBasicTest
     *
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $res = User::find(1);
        $this->assertTrue((bool)$res); // 判断运行结果是否为true
        $name = '景乾威888';
        $this->assertEquals($name, $res->name); // 判断两个变量是否相等
    }
}
