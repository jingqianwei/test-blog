<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/4/3
 * Time: 15:07
 */

use Illuminate\Support\Str;

if (! function_exists('show_route')) {

    /**
     * @param object $model 模型对象
     * @param null $resources 资源结果
     * @return string
     */
    function show_route($model, $resources = null)
    {
        $resources = $resources ?? plural_from_model($model);

        return route("{$resources}.show", $model);
    }
}

if (! function_exists('plural_from_model')) {

    /**
     * @param object $model 模型对象
     * @return string
     */
    function plural_from_model($model)
    {
        $plural = Str::plural(class_basename($model));

        return Str::kebab($plural);
    }
}

if (! function_exists('batch_update')) {
    /**
     * 批量更新函数
     * @param $data array 待更新的数据，二维数组格式
     * @param string $table string 要更新的数据表
     * @param string $field string 值不同的条件，默认为id(查询条件)
     * @param array $params array 值相同的条件，键值对应的一维数组
     * @return bool|string
     * @throws Exception
     */
    function batch_update(array $data, $table, $field = 'id', array $params = [])
    {
        if (empty($data)) {
            throw new Exception('待更新的数据,不能为空');
        }

        if (check_array_dimension($data) === false) {
            throw new Exception('待更新的数据,不是二维数组');
        }

        $updates = parseUpdate($data, $field);
        $where = parseParams($params);
        // 获取所有键名为$field列的值，值两边加上单引号，保存在$fields数组中
        // array_column()函数需要PHP5.5.0+，如果小于这个版本，可以自己实现，
        // 参考地址：http://php.net/manual/zh/function.array-column.php#118831
        $fields = array_column($data, $field);
        $fields = implode(',', array_map(function ($value) {
            return "'" . $value . "'";
        }, $fields));
        $sql = sprintf("UPDATE `%s` SET %s WHERE `%s` IN (%s) %s", $table, $updates, $field, $fields, $where);
        return $sql;
    }

    /**
     * 将二维数组转换成CASE WHEN THEN的批量更新条件
     * @param $data array 二维数组
     * @param $field string 列名
     * @return string sql语句
     */
    function parseUpdate($data, $field)
    {
        $sql = '';
        $keys = array_keys(current($data));
        foreach ($keys as $column) {
            // 过滤调跟where条件相同的字段(默认以id为条件，即默认不更新id的值)
            if (($field == 'id') and ($field == $column)) continue;
            $sql .= sprintf("`%s` = CASE `%s` \n", $column, $field);
            foreach ($data as $line) {
                $sql .= sprintf("WHEN '%s' THEN '%s' \n", $line[$field], $line[$column]);
            }
            $sql .= "END,";
        }

        return rtrim($sql, ',');
    }

    /**
     * 解析where条件
     * @param $params
     * @return array|string
     */
    function parseParams($params)
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = sprintf("`%s` = '%s'", $key, $value);
        }
        return $where ? ' AND ' . implode(' AND ', $where) : '';
    }
}

if (! function_exists('check_array_dimension')) {
    /**
     * 检测数组维度(false: 一维，true：多维)
     * @param $array
     * @return bool
     */
    function check_array_dimension($array)
    {
        if (count($array) === count($array, 1)) {
            return false;
        }

        return true;
    }
}

if (! function_exists('avoid_repeat_write')) {
    /**
     * 通过redis避免重复写入
     * @param string $redis_key 设置redis的key值
     * @return bool
     */
    function avoid_repeat_write($redis_key)
    {
        $lock_key = 'LOCK_PREFIX' . $redis_key;
        $is_lock = Redis::setnx($lock_key, 1); // 加锁
        if($is_lock == true) { // 获取锁权限
            // 释放锁
            Redis::del($lock_key);

            return true;
        } else {
            // 防止死锁
            if(Redis::ttl($lock_key) == -1){
                Redis::expire($lock_key, 5);
            }
            return false; // 获取不到锁权限，直接返回
        }
    }
}

if (! function_exists('str_random')) {
    /**
     * 生成更真实的"随机"字符串
     * @param int $length 字符串长度(一个字母代表一个长度)
     * @return string
     * @throws Exception
     */
    function str_random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

if (! function_exists('factorial')) {
    /**
     * 阶乘(递归)，但这样当$n很大的时候，递归容易造成栈溢出
     * @param int $n 要阶乘的值
     * @return float|int
     */
    function factorial($n) {
        if ($n == 0) {
            return $n;
        }
        
        return $n * factorial($n - 1);
    }

    // 调用var_dump(factorial(100));
}

if (! function_exists('factorial')) {
    /**
     * 阶乘(尾递归)，但这样当$n很大的时候，尾递归容易造成栈溢出
     * @param int $n 要阶乘的值
     * @param int $acc 类加的值，默认为1
     * @return float|int
     */
    function factorial($n, $acc) {
        if ($n == 0) {
            return $acc;
        }

        return factorial($n - 1, $n * $acc);
    }

    // 调用var_dump(factorial(100, 1));
}

if (! function_exists('factorial')) {
    function factorial($n, $accumulator = 1) {
        if ($n == 0) {
            return $accumulator;
        }

        return function() use($n, $accumulator) {
            return factorial($n - 1, $accumulator * $n);
        };
    }

    function trampoline($callback, $params) {
        $result = call_user_func_array($callback, $params);

        while (is_callable($result)) {
            $result = $result();
        }

        return $result;
    }

    // 调用var_dump(trampoline('factorial', [100, 1]));
    // 注意到trampoline()函数没？简单点说就是利用高阶函数消除递归。
    //想更进一步了解 call_user_func_array，可以参看这篇文章：PHP函数补完：call_user_func()与call_user_func_array()
}


