<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * Class SecondKillController
 * @link https://www.jianshu.com/p/01e004984514
 * @package App\Http\Controllers
 */
class SecondKillController extends Controller
{
    // 文件句柄
    protected $fp;

    /**
     * 第一步将商品存入redis队列中
     * @param Request $request
     * @return array|string
     */
    public function storage(Request $request)
    {
        #查询商品
        //dd(Redis::keys('goods_store:*'));//查询有哪些队列
        dd(Redis::lrange('goods_store:*', 0, '-1'));//查询队列的所有元素
        $goods_id = $request->get('goods_id');
        $result = DB::table('s_goods')->where('id', $goods_id)->first(['number','id']);
        $store = $result->number;
        $res = Redis::llen('goods_store:' . $result->id);
        $count = $store - $res;
        for($i = 0;$i < $count; $i++) {
            Redis::lpush('goods_store:' . $result->id, $result->id);
        }

        return $this->outData(__LINE__, 'success');
    }

    /**
     * 将用户也存入队列中（就是将访问请求数据）(此处没有进行用户过滤，同一个用户进行多次请求也会进入队列)
     * @param $userId
     * @return bool
     */
    public function requestUser($userId)
    {
        #判断排队数（防止队列数据过大，redis服务器炸掉）
       if (Redis::llen('user_list') <= 1000) {
           #添加数据
           Redis::lpush('user_list', $userId);
           return true;
       }

        // return '排队数大于商品总数';
        return false;
    }

    /**
     * 使用文件锁flock(),（单一处理的思想）
     * 原理使用模拟使用文件操作，当flockOrder()程序未运行完毕时,就是$fp未关闭，再次运行flockOrder()，
     * flock($fp, LOCK_EX | LOCK_NB)则会返回 false,从而达到我们的需求
     * @return array
     */
    public function flockOrder()
    {
        $this->fp = fopen(storage_path(). '/logs/lock.txt', 'w+');

        return flock($this->fp, LOCK_EX | LOCK_NB);
    }

    /**
     * 生成订单(采用数据库事务的悲观锁)
     * @param $user_id
     * @param $goods_id
     * @param $number
     * @return int|string
     */
    public function storeOrder($user_id, $goods_id, $number)
    {
        try {
            #开启事务
            DB::beginTransaction();

            #查询库存sharedLock()共享锁，可以读取到数据，事务未提交不能修改，直到事务提交, lockForUpdate()不能读取到数据
            $goodsNumber = DB::table('s_goods')->where('id', $goods_id)->lockForUpdate()->value('number');

            #添加订单
            if ( $goodsNumber ) {
                $result_order = DB::table('s_order')->insert([
                    'user_id' => $user_id,
                    'goods_id' => $goods_id,
                    'goods_number' => $goodsNumber
                ]);

                #减少库存
                $result_update = DB::table('s_goods')->where('id', $goods_id)->decrement('number', $number);
                if ($result_order > 0 && $result_update > 0) {
                    DB::commit();
                    return true;
                }
            }

            DB::rollBack();
        } catch(\Exception $e) {
            \Log::error('生成订单失败 ' . __METHOD__, [$e->getMessage()]);
        }

        return false;
    }

    /**
     * 实现秒杀(通过100线程的并发测试)
     * @param Request $request
     * @return array|string
     */
    public function secondsKill(Request $request)
    {
        try {
            $user_data = $request->only(['user_id', 'goods_id']);
            if ( !$user_data ) {
                return $this->outData(__LINE__, '数据不能为空');
            }

            #访问用户入队接口
            $user_list = $this->requestUser($user_data['user_id']);
            if ( !$user_list ) {
                return $this->outData(__LINE__, '排队数大于商品总数');
            }

            #进入文件锁(访问文件锁接口)（有return 一定要有关闭文件的操作）
            $file_flock = $this->flockOrder();
            if ( !$file_flock ) {
                # 关闭文件
                fclose($this->fp);
                return $this->outData(__LINE__, '访问人数多，请稍后重试');
            }

            // $goods = Redis::lrange('goods_store', 0, '-1');  查询所有商品
            #消费商品,从队列中取出商品
            $count= Redis::lpop('goods_store:' . $user_data['goods_id']);
            if( !$count ) {
                # 关闭文件
                fclose($this->fp);
                return $this->outData(__LINE__, '商品抢光了');
            }

            #将用户从队列里面弹出（先进先出，存的时候用的lpush,所以取应该rpop）
            $userId = Redis::rpop('user_list');

            #最后进入数据库操作(每次固定消费1个)
            $mysql_data = $this->storeOrder($userId, $count, '1');
            if ( !$mysql_data ) {
                # 关闭文件
                fclose($this->fp);
                return $this->outData(__LINE__, '生成订单失败');
            }

            # 关闭文件
            fclose($this->fp);
            return $this->outData(__LINE__, '抢购成功');
        } catch (\Exception $e) {
            return $this->outData(__LINE__, $e->getMessage());
        }
    }

    /**
     * 定义输出
     * @param $code
     * @param $msg
     * @param array $data
     * @return array
     */
    protected function outData($code, $msg, $data = [])
    {
        return [
            'errCode' => $code,
            'errMsg'  => $msg,
            'data'    => $data,
        ];
    }
}
