<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/6/20
 * Time: 13:52
 */

use Carbon\Carbon;

if (! function_exists('carbon')) {
    /**
     * 实例化carbon类
     * @param $time
     * @param $tz
     * @return Carbon
     * @throws Exception
     */
    function carbon($time = null, $tz = null)
    {
        return new Carbon($time, $tz);
    }
}

if (! function_exists('carbonFormatted')) {
    /**
     * 格式化时间
     * @param null $time
     * @param null $tz
     * @return string
     * @throws Exception
     */
    function carbonFormatted($time = null, $tz = null)
    {
        return carbon($time, $tz)->format('Y-m-d');
    }
}

