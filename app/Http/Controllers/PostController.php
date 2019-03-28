<?php

namespace App\Http\Controllers;

use App\Events\PostViewEvent;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    /**
     * 缓存时间为5分钟
     * @var int
     */
    public $cacheExpires = 5;

    public function showPost(Request $request, $id)
    {
        // $count = 10; //自增数
        // $post = Post::find($id);
        // $post->view_count = \DB::raw("view_count + {$count}"); // 这样自增可以防止并发
        // $post->save();
        // dd('更新成功');
        // dd(Post::viewCount(1)->get());
        //Redis缓存中没有该post,则从数据库中取值,并存入Redis中,该键值key='post:cache'.$id生命时间5分钟
        $post = Cache::remember('post:cache:'.$id, $this->cacheExpires, function () use ($id) {
            return Post::find($id);
        });

        //获取客户端请求的IP
        $ip = $request->ip();

        //触发浏览次数统计时间
        event(new PostViewEvent($post, $ip));

        return view('pc.posts.show', compact('post'));
    }

    /**
     * redis测试练习
     * @link https://blog.csdn.net/qq_35641923/article/details/80825522
     * @link http://redisdoc.com/database/scan.html
     * @param Request $request
     * @param $id
     */
    public function testRedis(Request $request, $id)
    {
        $num = 100000000;
        dd(number_format($num));
        $arr1 = [1, 2];  /**
                                1. 数字数组，如果是相同的索引值，+号就是前面的值覆盖后面的值(前提是索引相同)，array_merge()就是纯粹的合并，前面和后面单独有的索引值，直接到新数组中
                                2. 关联数组，如果是相同的索引值，+号和array_merge()的效果一样，都是前面的索引值覆盖后面的索引值值(前提是索引相同),前面和后面单独有的索引值，直接到新数组中
                            * */
        $arr2 = [3, 4, 5];
        $arr3 = ['a' => 1, 'b' => 2];
        $arr4 = ['a' => 1, 'b' => 2, 'c' => 3];

        dd($arr1 + $arr2, array_merge($arr1, $arr2), $arr3 + $arr4, array_merge($arr3, $arr4));
        $path = storage_path('logs') . DIRECTORY_SEPARATOR . 'text.txt';
        $path1 = storage_path('logs') . DIRECTORY_SEPARATOR . 'text1.txt';
        dd(md5_file($path), md5_file($path1));
        echo 'scan方法取出的key值' . "<br>";
        $cursor = 0; // 初始游标
        $pattern = '*'; // 匹配所有的key值, 用正则进行匹配
        $count = 2; // 每一次取出的长度
        do {
            // 可能会取出空值
            list($cursor, $arrKey) = Redis::scan($cursor, ['match' => $pattern, 'count'=> $count]);
            //dump($arrKey, $cursor);
            if ($arrKey) {
                foreach ($arrKey as $key) {
                   echo $key . "<br>";
                }
            }
        } while ($cursor > 0);

        echo "<br>" . 'keys方法取出的key值' . "<br>";
        dd(Redis::keys('test_type_*'));
        //dd(Redis::set('test_type_' .$id , $id * 100)); // 不过期
        // 获取所有匹配的key值
        //dd(Redis::keys('user_type_*'));
        //dd(Redis::command('keys', ['user_type_*']));
        Redis::set('test_type', 44444444444444); // 不过期
        Redis::setex('user_type_' . $id, 3600, $request->name);

        // default是默认的Redis连接对象名，值是连接对象的参数；app('redis.connection')返回的就是该默认连接对象
        $redis = app('redis.connection');

        // cache是缓存定义的Redis连接对象名；通过执行app('redis')->connection('cache')可以获取该连接对象
        $redis = app('redis')->connection('cache');

        // my-cluster是我定义的Redis集群对象名；通过执行app('redis')->connection('my-cluster')可以获取该集群对象；
        $redis = app('redis')->connection('my-cluster');

        // 驱动predis输账号连接redis，方案1
        $array = [
            "host"=> "101.132.75.39",
            'parameters'=> [
                'password'=>'Hmf!1008'
            ],
            //"password"=> 'Hmf!1008',
            "port"=> "6379",
            "database" => 15,
        ];
        $redis = new RedisManager(app(), 'predis', ['default' => $array]); // laravel5.5以上连接方式

        // predis输账号连接redis，方案2
        $redis = new \Redis();
        $redis->connect('101.132.75.39', 6379);
        $redis->auth('Hmf!1008'); // 密码

        // 驱动redis输账号连接redis
        $array = [
            "host"=> "101.132.75.39",
            'parameters'=> [
                'password'=>'Hmf!1008'
            ],
            //"password"=> 'Hmf!1008',
            "port"=> "6379",
            "database" => 15,
        ];
        $redis = new RedisManager(app(), 'redis', ['default' => $array]); // laravel5.5以上连接方式

        // 获取所有匹配的key值
        dd(Redis::keys('user_type_*'));
        dd(Redis::command('keys', ['user_type_*']));
        Redis::set('test_type', 44444444444444); // 不过期
        Redis::setex('user_type_' . $id, 3600, $request->name);
    }
}
