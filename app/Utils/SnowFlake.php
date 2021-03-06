<?php

namespace App\Utils;

use App\Exceptions\InvalidSystemClockException;

/**
 * url: https://gist.github.com/lifei6671/095e9a072a468092a777cbef9157d78b
 * 使用 snowflake 算法生成递增的分布式唯一ID.
 * 该算法支持 15 条业务线，4 个数据中心，每个数据中心最多 128 台机器，每台机器每毫秒可生成 4096 个不重复ID.
 */
class SnowFlake
{
    const SEQUENCE_BITS         = 12;
    const MILLISECOND_BITS      = 39;
    const BUSINESS_ID_BITS      = 4;
    const DATA_CENTER_ID_BITS   = 2;
    const MACHINE_ID_BITS       = 7;
    const TWE_POC               = 1399943202863;
    /**
     * @var int 7bit 毫秒内序列号
     */
    protected $sequence;
    /**
     * @var int 39bit 毫秒数,最后一次生成的时间戳
     */
    protected $last_timestamp;
    /**
     * @var int 4bit 业务线标识
     */
    protected $business_id;
    /**
     * @var int 2bit 机房标识,仅支持4个数据中心
     */
    protected $data_center_id;
    /**
     * @var int 7bit 机器标识，
     */
    protected $machine_id;

    /**
     * Snowflake constructor.
     * @param int $business_id 业务线标识，必须大于0小于等于15
     * @param int $data_center_id 数据中心标识，必须大于0小于等于4
     * @param int $machine_id 机器标识，必须大于0小于等于128
     * @param int $sequence 顺序号，必须大于0小于等于4096
     * @throws \Exception
     */
    public function __construct($business_id = 1, $data_center_id = 1, $machine_id = 1, $sequence = 1)
    {
        //判断参数合法性
        if($business_id <= 0 || $business_id > $this->maxBusinessId()){
            throw new \Exception('Business Id can\'t be greater than 15 or less than 0');
        }
        //判断参数合法性
        if($data_center_id <=0 || $data_center_id > $this->maxDataCenterId()){
            throw new \Exception('DataCenter Id can\'t be greater than 4 or less than 0');
        }
        //判断参数合法性
        if($machine_id <= 0 || $machine_id > $this->maxMachineId()){
            throw new \Exception('Machine Id can\'t be greater than 128 or less than 0');
        }
        //判断参数合法性
        if($sequence <= 0 || $sequence > $this->maxSequence()){
            throw new \Exception('Sequence can\'t be greater than 4096 or less than 0');
        }
        //设置业务线
        $this->business_id = $business_id;
        //设置数据中心
        $this->data_center_id = $data_center_id;
        //设置当前机器
        $this->machine_id = $machine_id;
        //设置当前顺序号
        $this->sequence = $sequence;
    }

    /**
     * 获取当前毫秒数
     * @return float
     */
    private function getTimestamp()
    {
        return floor(microtime(true) * 1000);
    }

    /**
     * 阻塞到下一个毫秒，直到获得新的时间戳
     * @param float $lastTimestamp 上次生成ID的时间截
     * @return float 当前毫秒时间戳
     */
    private function nextMillisecond($lastTimestamp)
    {
        $timestamp = $this->getTimestamp();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->getTimestamp();
        }
        return $timestamp;
    }

    /**
     * 最大机器标识
     * @return int
     */
    private function maxMachineId()
    {
        return -1 ^ (-1 << self::MACHINE_ID_BITS);
    }

    /**
     * 最大数据中心
     * @return int
     */
    private function maxDataCenterId()
    {
        return -1 ^ (-1 << self::DATA_CENTER_ID_BITS);
    }

    /**
     * 最大业务线标识
     * @return int
     */
    private function maxBusinessId()
    {
        return -1 ^ (-1 << self::BUSINESS_ID_BITS);
    }

    /**
     * 最大顺序号
     * @return int
     */
    private function maxSequence()
    {
        return -1 ^ (-1 << self::SEQUENCE_BITS);
    }

    /**
     * 移位并通过或运算拼到一起组成64位的ID
     * @param $timestamp
     * @param $business_id
     * @param $data_center_id
     * @param $machine_id
     * @param $sequence
     * @return int
     */
    private function mintId64($timestamp, $business_id, $data_center_id, $machine_id, $sequence)
    {
        return (string)$timestamp | $business_id | $data_center_id | $machine_id | $sequence;
    }

    /**
     * 时间戳左移位
     * @return int
     */
    private function timestampLeftShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS + self::DATA_CENTER_ID_BITS + self::BUSINESS_ID_BITS;
    }

    /**
     * 业务线标识左移位
     * @return int
     */
    private function businessIdLeftShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS + self::DATA_CENTER_ID_BITS ;
    }

    /**
     * 数据中心左移位
     * @return int
     */
    private function dataCenterIdShift()
    {
        return self::SEQUENCE_BITS + self::MACHINE_ID_BITS;
    }

    /**
     * 机器标识左移位
     * @return int
     */
    private function machineIdShift()
    {
        return self::SEQUENCE_BITS;
    }

    /**
     * 下一个序列号
     * @return int
     */
    private function nextSequence()
    {
        return $this->sequence++;
    }

    /**
     * 获取最后时间戳
     * @return int
     */
    public function getLastTimestamp()
    {
        return $this->last_timestamp;
    }

    /**
     * 获取数据中心
     * @return int
     */
    public function getDataCenterId()
    {
        return $this->data_center_id;
    }

    /**
     * 获取机器标识
     * @return int
     */
    public function getMachineId()
    {
        return $this->machine_id;
    }

    /**
     * 获取业务线标识
     * @return int
     */
    public function getBusinessId()
    {
        return $this->business_id;
    }

    /**
     * 获取生成id
     * @return int
     * @throws InvalidSystemClockException
     */
    public function getGenerateId()
    {
        //获取当前毫秒时间戳
        $timestamp = $this->getTimestamp();
        //如果当前时间小于上一次ID生成的时间戳，说明系统时钟回退过这个时候应当抛出异常
        if ($timestamp < $this->last_timestamp) {
            throw new InvalidSystemClockException(sprintf("Clock moved backwards. Refusing to generate id for %d milliseconds", ($this->last_timestamp - $timestamp)));
        }

        //如果是同一毫秒内生成的，则进行毫秒序列化
        if ($timestamp == $this->last_timestamp) {
            //获取当前序列号值, &是位运算符
            $sequence = $this->nextSequence() & $this->maxSequence();
            // sequence rollover, wait til next millisecond
            //毫秒序列化值溢出（就是超过了4096）
            if ($sequence == 0) {
                //阻塞到下一秒，获得新的时间戳
                $timestamp = $this->nextMillisecond($this->last_timestamp);
            }
        } else {  //如果不是同一毫秒，那么重置毫秒序列化值
            $this->sequence = 0;
            //获取当前序列号值
            $sequence = $this->nextSequence();
        }
        //重置上一次生成的时间戳
        $this->last_timestamp = $timestamp;
        //时间戳左移 22 位
        $t = floor($timestamp - self::TWE_POC) << $this->timestampLeftShift();
        //业务线标识左移 12 位
        $b = $this->getBusinessId() << $this->businessIdLeftShift();
        //数据中心左移 12 位
        $dc = $this->getDataCenterId() << $this->dataCenterIdShift();
        //机器id左移 12 位
        $worker = $this->getMachineId() << $this->machineIdShift();
        //移位并通过或运算拼到一起组成64位的ID
        return $this->mintId64($t, $b, $dc, $worker, $sequence);
    }
}
