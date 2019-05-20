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


