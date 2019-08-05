<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/8/5
 * Time: 15:29
 */

namespace App\Utils;

/**
 * 使用redis实现排行榜
 * @link https://blog.csdn.net/u011822516/article/details/82734992
 * @package App\Utils
 */
class RedisLeaderBoard
{
    /**
     * @var object redis实例
     */
    private $redis;

    /**
     * @var string 放置排行榜的key
     */
    private $leaderBoard;

    /**
     * 初始化
     * @param string $prefix 排行榜的前缀名
     * @param bool $uniqueKey 生成的key是否唯一
     */
    public function __construct($prefix = 'leader_board', $uniqueKey = true)
    {
        if (is_null($this->redis)) {
            $this->redis = new \Redis();
            $this->redis->connect('127.0.0.1');
            $this->redis->auth(123456);
        }

        if ($uniqueKey) {
            $this->leaderBoard = $prefix;
        } else {
            // 设置key值
            $this->leaderBoard = $prefix . mt_rand(1, 100000);

            // 如果key值已存在，则重新定义key
            while (! empty($this->redis->exists($this->leaderBoard))) {
                $this->leaderBoard = $prefix . mt_rand(1, 100000);
            }
        }
    }

    /**
     * 获取当前排行榜的key名
     * @return string
     */
    public function getCurrentLeaderBoard()
    {
        return $this->leaderBoard;
    }

    /**
     * 将对应的值写入排行榜中
     * @param string $node 对应的需要填入的值(比如商品的id)
     * @param int $count 对应的分数,默认值为1
     * @return int
     */
    public function addLeaderBoard($node, $count = 1)
    {
        return $this->redis->zAdd($this->leaderBoard, $count, $node);
    }

    /**
     * 给出对应的排行榜
     * @param int $number 需要给出排行榜数目
     * @param bool $asc 排序顺序 true为按照高分，第一高分为0,false的话第一低分为0
     * @param bool $withScores 是否需要分数,返回redis中的结构，value为key，scores为值
     * @param callback $callback 用于处理排行榜的回调函数
     * @return array
     */
    public function getLeaderBoard($number, $asc = true, $withScores = false, $callback = null)
    {
        if ($asc) {
            $nowLeaderBoard = $this->redis->zRevRange($this->leaderBoard, 0, $number - 1, $withScores); // 按照高分顺序排行
        } else {
            $nowLeaderBoard = $this->redis->zRange($this->leaderBoard, 0, $number - 1, $withScores); // 按照低分顺序排行
        }

        if ($callback) {
            // 使用回调处理
            return $callback($nowLeaderBoard);
        } else {
            return $nowLeaderBoard;
        }
    }

    /**
     * 获取给定节点的排名
     * @param string $node 对应节点的key名
     * @param bool $asc 是否按照分数大小正序排名, true的情况下分数越大,排名越高
     * @return int 节点排名,根据$asc排序,true的话,第一高分为0,false的话第一低分为0
     */
    public function getNodeRank($node, $asc = true)
    {
        if ($asc) {
            //zRevRank 分数最高的排行为0,所以需要加1位
            return $this->redis->zRevRank($this->leaderBoard, $node);
        } else {
            return $this->redis->zRank($this->leaderBoard, $node);
        }
    }
}
