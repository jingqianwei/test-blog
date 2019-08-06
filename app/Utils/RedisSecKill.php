<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/8/6
 * Time: 10:04
 */

namespace App\Utils;


class RedisSecKill
{
    /**
     * @var object mysql实例
     */
    protected $mysql;

    /**
     * @var object \Redis实例
     */
    protected $redis;

    /**
     * @var string key值
     */
    protected $redisKey;

    // 设置库存为20
    const STOCK = 20;

    public function __construct($prefix = 'secKill')
    {
        if (is_null($this->redis)) {
            $this->redis = new \Redis();
            $this->redis->connect('127.0.0.1');
            $this->redis->auth(123456);
        }

        // 设置key值
        $this->redisKey = $prefix;
    }

    /**
     * 生产秒杀队列
     */
    public function producer()
    {
        // 模拟100人请求秒杀
        for ($i = 0; $i < 100; $i++) {
            $uid = mt_rand(100000000, 999999999);

            if ($this->redis->lLen($this->redisKey) < self::STOCK) {
                // 加入队列，右进
                $this->redis->rPush($this->redisKey, $uid);
                echo $uid . "秒杀成功"."<br>";
            } else {
                //如果当前队列人数已经达到20人,则返回秒杀已完成
                echo "秒杀已结束<br>";
            }
        }
    }

    /**
     * 队列消费
     */
    public function consumer()
    {
        if (is_null($this->mysql)) {
            //PDO连接mysql数据库
            $this->mysql = new \PDO("mysql:dbname=test;host=127.0.0.1", 'root', 'root');
        }

        // 从队列最前面取出一个值,因为队列是右进，所以左出是第一个，并减少一个
        while ($uid = $this->redis->lPop($this->redisKey)) {
            //生成订单号
            $orderNum = $this->build_order_no($uid);

            //生成订单时间
            $timeStamp = time();

            //构造插入数组
            $user_data = ['uid' => $uid, 'username' => 'name' . $uid, 'time_stamp' => $timeStamp, 'order_num' => $orderNum];

            //将数据保存到数据库
            $sql = "insert into student (uid, username, time_stamp, order_num) values (:uid, :username, :time_stamp, :order_num)";
            $stmt = $this->mysql->prepare($sql);
            $res = $stmt->execute($user_data);

            //数据库插入数据失败,回滚
            if(! $res){
                $this->redis->rPush($this->redisKey, $uid);
            }
        }
    }

    //生成唯一订单号
    protected function build_order_no($uid){
        return  substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . $uid;
    }

    public function __destruct()
    {
        //关闭redis连接
        $this->redis->close();
    }
}
