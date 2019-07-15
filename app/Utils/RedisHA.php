<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/7/5
 * Time: 13:50
 */

namespace App\Utils;

use Illuminate\Redis\RedisManager;

/**
 * 使用方法：
 * $index 指定redis连接键
 * $hash_set = RedisHash::connection($index)->hset('hash:key', 1204752,json_encode(['name'=>'小锦鲤']));
 */
class RedisHA
{
    protected static $connections = [];

    /**
     * 初始化redis集群
     */
    protected static function init()
    {
        // 获取redis集群host
        $hosts = explode(',', env('REDIS_HA_HOSTS'));

        // 获取redis库
        $databases = explode(',', env('REDIS_HA_DATABASES'));

        foreach ($hosts as $index => $host) {
            $redisManage = new RedisManager(app(), config('database.redis.client'), [
                'default' => [
                    'host' => $host,
                    'password' => env('REDIS_PASSWORD', null),
                    'port' => env('REDIS_PORT', 6379),
                    'database' => $databases[$index],
                ]
            ]);

            self::$connections[$index] = $redisManage->connection();
        }
    }

    /**
     * 获取redis集群连接数
     * @return int
     */
    public static function count()
    {
        if (empty(self::$connections)) {
            self::init();
        }

        return count(self::$connections);
    }

    /**
     * 获取指定的redis连接
     *
     * index生成的方法，其中$openid为唯一的字符换，$redis_host为redis集群的所有连接ip
     * $index= crc32($openid) % count(explode(',', array($redis_host)));
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    public static function connection($index)
    {
        if (empty(self::$connections)) {
            self::init();
        }

        if (!isset(self::$connections[$index])) {
            throw new \Exception('未找到redis连接');
        }

        return self::$connections[$index];
    }

    /**
     * 执行redis命令
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($method, $parameters)
    {
        if (empty(self::$connections)) {
            self::init();
        }

        $result = [];
        foreach (self::$connections as $connection) {
            $result[] = $connection->{$method}(...$parameters);
        }

        return $result;
    }
}
