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
