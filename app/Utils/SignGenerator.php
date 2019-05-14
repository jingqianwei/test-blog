<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/5/14
 * Time: 10:45
 */

namespace App\Utils;


class SignGenerator
{
    CONST BITS_FULL = 64;
    CONST BITS_PRE = 1;//固定
    CONST BITS_TIME = 41;//毫秒时间戳 可以最多支持69年
    CONST BITS_SERVER = 5; //服务器最多支持32台
    CONST BITS_WORKER = 5; //最多支持32种业务
    CONST BITS_SEQUENCE = 12; //一毫秒内支持4096个请求

    CONST OFFSET_TIME = "2019-05-05 00:00:00";//时间戳起点时间

    /**
     * 服务器id
     */
    protected $serverId;

    /**
     * 业务id
     */
    protected $workerId;

    /**
     * 实例
     */
    protected static $instance;

    /**
     * redis 服务
     */
    protected static $redis;

    /**
     * 获取单个实例
     * @param $redis
     * @return SignGenerator
     * @throws \Exception
     */
    public static function getInstance($redis)
    {
        if(isset(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($redis);
        }
    }

    /**
     * 构造初始化实例
     * @param $redis
     * @throws \Exception
     */
    protected function __construct($redis)
    {
        if($redis instanceof \Redis || $redis instanceof \Predis\Client) {
            self::$redis = $redis;
        } else {
            throw new \Exception("redis service is lost");
        }
    }

    /**
     * 获取唯一值 TODO 注意使用方法，不然容易造成死循环
     * @throws \ErrorException
     * @throws \Exception
     */
    public function getNumber()
    {
        if(!isset($this->serverId)) {
            throw new \Exception("serverId is lost");
        }
        if(!isset($this->workerId)) {
            throw new \Exception("workerId is lost");
        }

        do{
            // 1、初始化发号器
            $id = pow(2,self::BITS_FULL - self::BITS_PRE) << self::BITS_PRE;

            // 2、为发号器添加时间属性，时间戳 41位
            $nowTime = (int)(microtime(true) * 1000);
            $startTime = (int)(strtotime(self::OFFSET_TIME) * 1000);
            //计算毫秒差，基于上图，这里 diffTime=326570168
            $diffTime = $nowTime - $startTime;
            //计算出位移 的偏移量
            $shift = self::BITS_FULL - self::BITS_PRE - self::BITS_TIME;
            //改变uuid的时间bit位
            $id |= $diffTime << $shift;
            echo "diffTime=",$diffTime,"\t";

            // 3、为发号器添加服务器编号，服务器
            //在新的$shift 计算出位移 的偏移量
            $shift = $shift - self::BITS_SERVER;
            //改变uuid的服务器bit位
            $id |= $this->serverId << $shift;
            echo "serverId=",$this->serverId,"\t";

            // 4、为发号器添加业务编号，业务
            //在新的$shift 计算出位移 的偏移量
            $shift = $shift - self::BITS_WORKER;
            //改变uuid的业务编号bit位
            $id |= $this->workerId << $shift;
            echo "workerId=",$this->workerId,"\t";

            // 5、为发号器添加sequence，自增值
            $sequenceNumber = $this->getSequence($id);
            echo "sequenceNumber=",$sequenceNumber,"\t";
            if($sequenceNumber > pow(2, self::BITS_SEQUENCE)) {
                usleep(1000);
            } else {
                $id |= $sequenceNumber;
                return $id;
            }
        } while(true);
    }

    /**
     * 反解获取业务数据
     * @param $number
     * @return array
     */
    public function reverseNumber($number)
    {
        $uuidItem = [];
        $shift = self::BITS_FULL - self::BITS_PRE - self::BITS_TIME;
        $uuidItem['diffTime'] = ($number >> $shift) & (pow(2, self::BITS_TIME) - 1);

        $shift -= self::BITS_SERVER;
        $uuidItem['serverId'] = ($number >> $shift) & (pow(2, self::BITS_SERVER) - 1);

        $shift -= self::BITS_WORKER;
        $uuidItem['workerId'] = ($number >> $shift) & (pow(2, self::BITS_WORKER) - 1);

        $shift -= self::BITS_SEQUENCE;
        $uuidItem['sequenceNumber'] = ($number >> $shift) & (pow(2, self::BITS_SEQUENCE) - 1);

        $time = (int)($uuidItem['diffTime']/1000) + strtotime(self::OFFSET_TIME);
        $uuidItem['generateTime'] = date("Y-m-d H:i:s", $time);

        return $uuidItem;
    }

    /**
     * 获取自增序列
     * @param $id
     * @return mixed
     * @throws \ErrorException
     */
    protected function getSequence($id)
    {
        $lua = <<<LUA
            local sequenceKey = KEYS[1]
            local sequenceNumber = redis.call("incr", sequenceKey);
            redis.call("pexpire", sequenceKey, 1);
            return sequenceNumber
LUA;
        $sequence = self::$redis->eval($lua, [$id], 1);
        $luaError = self::$redis->getLastError();
        if(isset($luaError)) {
            throw new \ErrorException($luaError);
        } else {
            return $sequence;
        }
    }

    /**
     * 获取服务器id
     * @return mixed
     */
    public function getServerId()
    {
        return $this->serverId;
    }

    /**
     * 设置服务器id
     * @param mixed $serverId
     * @return SignGenerator
     */
    public function setServerId($serverId)
    {
        $this->serverId = $serverId;
        return $this;
    }

    /**
     * 获取业务id
     * @return mixed
     */
    public function getWorkerId()
    {
        return $this->workerId;
    }

    /**
     * 设置业务id
     * @param mixed $workerId
     * @return SignGenerator
     */
    public function setWorkerId($workerId)
    {
        $this->workerId = $workerId;
        return $this;
    }
}
