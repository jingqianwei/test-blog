<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/6/19
 * Time: 9:57
 */

namespace App\Utils;

/**
 * Class RedisLock
 * @package App\Utils
 * @link https://blog.csdn.net/love_yu_er/article/details/77153936
 * @link https://blog.csdn.net/fenglvming/article/details/51996406
 */
class RedisLock
{
    /**
     * @var string 当前锁标识，用于解锁
     */
    private $lockFlag;

    /**
     * @var object \Redis 实例
     */
    private $redis;

    public function __construct($host = '127.0.0.1', $port = '6379', $password = '')
    {
        $this->redis = new \Redis();
        $this->redis->connect($host, $port);
        if ($password) {
            $this->redis->auth($password);
        }
    }

    /**
     * 普通加锁
     * @param string $key key值
     * @param int $expire 过期时间
     * @return bool
     */
    public function lock($key, $expire = 5)
    {
        $now = time();
        $expireTime = $expire + $now;
        if ($this->redis->setnx($key, $expireTime)) {
            $this->lockFlag = $expireTime;
            return true;
        }

        // 获取上一个锁的到期时间
        $currentLockTime = $this->redis->get($key);
        if ($currentLockTime < $now) {
            /*
                用于解决C0超时了,还持有锁,加入C1/C2/...同时请求进入了方法里面
                C1/C2都执行了getset方法(由于getset方法的原子性,
                所以两个请求返回的值必定不相等保证了C1/C2只有一个获取了锁)
            */
            $oldLockTime = $this->redis->getset($key, $expireTime);
            if ($currentLockTime == $oldLockTime) {
                $this->lockFlag = $expireTime;
                return true;
            }
        }

        return false;
    }

    /**
     * 通过lua进行加锁
     * @param string $key 加索的key值
     * @param int $expire 过期时间
     * @return mixed
     */
    public function lockByLua($key, $expire = 5)
    {
        $script = <<<EOF
            local key = KEYS[1]
            local value = ARGV[1]
            local ttl = ARGV[2]
            
            if (redis.call('setnx', key, value) == 1) then
                return redis.call('expire', key, ttl)
            elseif (redis.call('ttl', key) == -1) then
                return redis.call('expire', key, ttl)
            end

            return 0
EOF;

        $this->lockFlag = md5(microtime(true));
        return $this->_eval($script, [$key, $this->lockFlag, $expire]);
    }

    /**
     * 释放锁
     * @param string $key 加锁的key值
     * @return mixed
     */
    public function unlock($key)
    {
        $script = <<<EOF
            local key = KEYS[1]
            local value = ARGV[1]
            
            if (redis.call('exists', key) == 1 and redis.call('get', key) == value) 
            then
                return redis.call('del', key)
            end
            
            return 0
EOF;

        if ($this->lockFlag) {
            return $this->_eval($script, [$key, $this->lockFlag]);
        }

        return true;
    }

    /**
     * 检查一个key值是否存在，不存在就设置一个值
     * @param string $key key值
     * @param string $value 设置的值，默认为空
     * @param int $expire 过期时间，默认为1
     * @return mixed
     */
    public function existsKey($key, $value = '', $expire = 1)
    {
        $script = <<<EOF
            local key = KEYS[1]
            local value = ARGV[1]
            local expire = ARGV[2]
            
            if redis.call('get', key) == false 
            then 
                redis.call('set', key, value) 
                redis.call('expire', key, expire) 
                return 0 
            else 
                return 1 
            end
EOF;

        return $this->_eval($script, [$key, $value, $expire]);
    }

    /**
     * 执行lua命令
     * @param string $script 要执行的lua命令
     * @param array $params 传进去的参数(必须是数组):
     * @param int $keyNum 表示第二个参数数组中 有几个是参数(数组其他剩下来的是附加参数)
     * @return mixed
     */
    private function _eval($script, array $params, $keyNum = 1)
    {
        $hash = $this->redis->script('load', $script);
        return $this->redis->evalSha($hash, $params, $keyNum);
    }

    /**
     *  操作方法
     *  $redisLock = new RedisLock();
        $key = 'lock';
        if ($redisLock->lockByLua($key)) {
            // to do...
            $redisLock->unlock($key);
        }
     *  通知lua执行的命令都具有原子性，要不都成功，要不都失败
     */
}
