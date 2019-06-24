<?php

namespace App\Http\Controllers;

class TestException extends Controller
{
    public function test()
    {
        echo 'start' . "<br/>";
        // try catch就是直接捕获错误
        try {
            dd($this->test1(2));
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br />";
            // 捕获错误了，如果不throw $e;则不会抛出错误
        }

        echo 'end';
    }

    /**
     * @param $num
     * @return mixed
     * @throws \Exception
     */
    private function test1($num)
    {
        try{
            return $this->test2($num);
        } catch (\Exception $e) {
            throw $e; // 把错误传入上一层
        }
    }

    /**
     * @param $num
     * @return mixed
     * @throws \Exception
     */
    private function test2($num)
    {
        try {
            return $this->test3($num);
        } catch (\Exception $e) {
            throw $e; // 把错误传入上一层
        }
    }

    /**
     * @param $num
     * @return mixed
     * @throws \Exception
     */
    private function test3($num)
    {
        if ($num > 1) {
            throw new \Exception('数据出错'); // 直接抛出错误
        }

        return $num;
    }
}
