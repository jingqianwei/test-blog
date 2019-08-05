<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Utils\JwtAuth;
use App\Utils\RedisLeaderBoard;
use App\Utils\SignGenerator;
use App\Utils\SimpleSnowFlake;
use App\Utils\SnowFlake;
use App\Utils\Timer;

Route::get('/', function () {
    return view('welcome');
});

//日志查看页面
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// 测试API数据格式处理
Route::get('test-api', function () {
    return UserResource::collection(User::paginate());
    //return UserResource::collection(User::all());
    //return new UserResource(User::all());
});

// 测试curl封装处理
Route::get('test-curl', 'TestModeController@getProductList');

// 测试根据不同的设备加载不同的模板文件
Route::get('test-template-view', 'TestModeController@templateView');

// 定义帖子的访问路由
Route::get('/post/{id}', 'PostController@showPost');

// 测试redis的使用
Route::get('/test-redis/{id}', 'PostController@testRedis');

// 用github登陆
Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('test-array', function() {
    // insertGetId() 返回插入id(id是自增主键)的值，如果自增列不是id(例如user_id为自增列)，那就得传第二个参数，例如：insertGetId(['title' => '军事', 'content' => '嘎嘎嘎嘎', 'view_count' => 2], 'user_id');
    Post::query()->insertGetId(['title' => '军事', 'content' => '嘎嘎嘎嘎', 'view_count' => 2], 'user_id'); // user_id为自增列，则返回得就是user_id的值
    // updateOrInsert()跟updateOrCreate()基本功能都一样，唯一的区别就是Insert()与Create()插入数据的区别
    $post = Post::query()->updateOrInsert(['title' => '军事'], ['content' => '嘎嘎嘎嘎', 'view_count' => 2]);
    dd($post);
    // updateOrCreate() 如果查询不到值，则直接插入一条记录，自动保存，如果查询到值了，则直接更改对应字段值，自动保存
    $post = Post::query()->updateOrCreate(['title' => '军事'], ['content' => '嘎嘎嘎嘎', 'view_count' => 2]);
    dd($post);
    // firstOrNew() 如果查询不到值，则赋值到对应字段但不保存到数据库，需要手动保存，返回bool，成功与否
    $post = Post::query()->firstOrNew(['title' => '体育'], ['content' => '嘻嘻嘻嘻', 'view_count' => 1]);
    dd($post->save());
    // firstOrCreate() 如果查询不到值，则直接插入一条记录，自动保存，并返回这个Eloquent模型
    $post = Post::query()->firstOrCreate(['title' => '军事'], ['content' => '哈哈哈哈', 'view_count' => 1]);
    dd($post);
    // 这样更加优化
    User::query()->chunkById(100, function($query) {
        foreach ($query as $val) {
            // 可以直接进行递增更新，前100个值都已经更新了
           $val->increment('status', 1, ['name' => '测试用户123', 'phone' => 2]);
        }
        dd($query);
    });
    // 如果只有一条数据，返回的是一个对象，不具有集合的使用方法，如果有多条数据，返回的就是一个集合，只具备集合的使用方法，不具备模型的方法
    $user = DB::table('users')->where('id',1)->lockForUpdate()->get(['id', 'name']);
    dd($user->pluck('id', 'name'));

    // 如果只有一条数据，返回的是单个Eloquent模型，具有集合的所有使用方法和模型包含的方法，如果有多条数据返回的就是Eloquent模型集合，具有集合的所有使用方法
    $user = User::where('id', 1)->lockForUpdate()->first(['id', 'name']); // 排他锁
    $user = User::where('id', 1)->sharedLock()->first(['id', 'name']); // 共享锁
    if ($user) {
        // 第一种更新
        User::where('id', 1)->update(['name' => '景乾威555']);
        // 第二种更新
        $user->update(['name' => '景乾威666']);
        // 第三种更新
        $user->name = '景乾威777';
        $user->save();
    }
    dd($user);
    dd(str_pad(1, 12, 0)); // 通过指定字符把字符串填充到指定长度 100000000000
    //dd(env('ARRAY'));
    $input = [["key" => "value1"], ["key" => "value2"]];
	//$input = ['php', 'php', 'java', 'go', 'python'];
	dd(collect($input)->last()['key']);
	$diffArr = array_diff($input, ['php']); //都可以进行过滤
	$filterArr = array_filter($input, function ($item) {
	    return $item != 'php';
    });

	dd($diffArr, $filterArr, $input);
	dd(array_unique($input));
	list($key, $val) = each($input); // 0, php
	dd($key, $val);
	dd(each($input));
	//dd(array_push($input, 1)); // 传入一个值到数组尾部中，函数的返回是新数组中元素的个数
	//dd(array_pop($input)); // 删除数组末尾的元素，函数返回这个删除的元素值
	//dd(array_shift($input)); // 删除数组头部的元素，函数返回这个删除元素的值
	dd(array_unshift($input, 2)); // 传入一个值到数组的头部中，函数返回新数组中元素的个数
	dd(array_rand($input, 1)); // 如果不传或者第二个参数传1，就返回随机的一个键值，如果第二个参数大于1，则返回随机键值的数组

 	// 随机取数组中的值
	return $input[array_rand($input)];
});

// 测试vue
Route::view('test-vue', 'vue');

// 测试WebSocket
Route::view('test-web-socket', 'websocket');

Route::get('test-except', 'TestException@test');

Route::get('test-event', 'TestModeController@testRegister');

Route::get('generate-id', function () {
    $res = new SimpleSnowFlake();
    $res1 = new SnowFlake(1, 1, 1, 1);
    dd($res->generateID(), $res1->getGenerateId());
});

Route::get('redis-id', function() {
    // 连接redis
    $redis = new \Redis;
    $redis->connect("127.0.0.1", 6379);
    $redis->auth('123456');
    $instance = SignGenerator::getInstance($redis);

    // 获取uuid
    $instance->setWorkerId(2)->setServerId(1);
    $number = $instance->getNumber();
    dd($number);

    // 反解uuid
    $item = $instance->reverseNumber(3467477958926337);
    dd($item);
});

Route::get('test-pipe', function() {
    function G($star,$end = '')
    {
        static $info = []; #静态变量在生命周期内都是有效的
        if (!empty($end))
        {
            $info[$end] = microtime(true);
            $use = $info[$end] - $info[$star];
            echo $use,"s<br/>";

        } else {
            $info[$star] =  microtime(true);
        }
    }

    // 连接redis
    $redis = new \Redis;
    $redis->connect("127.0.0.1", 6379);
    $redis->auth('123456');

    G('1');
    //不具备原子性 ,管道(pipeline 只是把多个redis指令一起发出去，redis并没有保证这些指定的执行是原子的)
    $redis->multi(Redis::PIPELINE); // 或者直接使用$redis->pipeline()
    for ($i=0;$i<100000;$i++)
    {
        $redis->set("test_{$i}",pow($i,2));
        $redis->get("test_{$i}");
    }
    $redis->exec();
    $redis->close();
    G('1','e');

    // 连接redis
    $redis = new \Redis;
    $redis->connect("127.0.0.1", 6379);
    $redis->auth('123456');
    G('2');
    //事物具备原子性(multi相当于一个redis的transaction的，保证整个操作的原子性，避免由于中途出错而导致最后产生的数据不一致)
    $redis->multi();
    for ($i=0;$i<100000;$i++)
    {
        $redis->set("test_{$i}",pow($i,2));
        $redis->get("test_{$i}");
    }
    $redis->exec();
    $redis->close();
    G('2','e');

    // 连接redis
    $redis = new \Redis;
    $redis->connect("127.0.0.1", 6379);
    $redis->auth('123456');
    //普通
    G('3');
    //事物具备原子性(单线程，保证唯一原子性)
    for ($i=0;$i<100000;$i++)
    {
        $redis->set("test_{$i}",pow($i,2));
        $redis->get("test_{$i}");
    }
    $redis->close();
    G('3','e');

    // Redis 管道技术pipe
    $pipe = $redis->multi(Redis::PIPELINE); //另一种写法$redis->pipeline();
    for ($i = 0; $i < 10000; $i++) {
        $pipe->set("key::$i",str_pad($i, 4,'0', 0));
        $pipe->get("key::$i");
    }

    $replies = $pipe->exec();
    dd($replies);
});

// 制作计数器
Route::get('test-db', function () {
    DB::beginTransaction();
    $post = Post::where('id', 1)->lockForUpdate()->first();
    if ($post) {
        Log::info('获取的值为' . $post->view_count); // 使用悲观锁，取到的值都是正确的
        $post->view_count = DB::raw('view_count + 1');
        $post->save();
        DB::commit();
    } else {
        DB::rollBack();
    }
});

// 生成批量更新的sql语句
Route::get('test-update', function () {
    $data = [
        ['id' => 1, 'sort' => 1],
        ['id' => 2, 'sort' => 3],
        ['id' => 3, 'sort' => 5],
    ];

    echo batch_update($data, 'posts');
});

// 测试观察者的使用
Route::get('test-user', 'UserController@create');

Route::get('test-time', function() {
    $timer = new Timer();

    //注册 - 3s - 重复触发
    $timer->insert(array('expire' => 3, 'repeat' => true, 'action' => function(){
        Log::info('3秒 - 重复 - hello world' . "\r\n");
    }));

    //注册 - 3s - 重复触发
    $timer->insert(array('expire' => 3, 'repeat' => true, 'action' => function(){
        Log::info('3秒 - 重复 - go, go' . "\r\n");
    }));

    //注册 - 6s - 触发一次
    $timer->insert(array('expire' => 6, 'repeat' => false, 'action' => function(){
        Log::info('6秒 - 一次 - hello mo,mo' . "\r\n");
    }));

    //监听
    $timer->monitor(false);
});

// 框架启动时间
Route::get('start-up/time', function () {
    dd(round(microtime(true) - LARAVEL_START, 3) . 's');
});


// 集合使用
Route::get('test-collect', function () {
    $arr = [1, 2, 3, 4, 5];
    $collect = collect($arr)->map(function ($item) {
        return [
            'key1' => $item * 2,
            'key2' => $item * 4,
        ];
    })->values()->toArray();
    $collect['name'] = 1;

    dd($collect);
});

// 测试订阅redis过期
Route::get('test-cache', function() {
    Cache::put('ORDER_CONFIRM:222222', 222222,3); // 1分钟后过期--执行取消订单
});

// redis给test-channel发布消息
Route::get('test-channel', function() {
    \Illuminate\Support\Facades\Redis::publish('test-channel', json_encode(['foo' => 'bar']));
});

// 测试模型关系
Route::get('test-model', function() {
    dd(Post::with('commentId')->withCount('commentId')->get()->toArray());
});


# 测试生成jwt-token
Route::get('test/jwt', function () {
    $apiJwt = JwtAuth::getInstance();
    $token = $apiJwt->encode();

    dd($token, $apiJwt->decode());
});

# 测试秒杀
Route::get('test/store', 'SecondKillController@storage');

# 秒杀
Route::get('test/kill', 'SecondKillController@secondsKill');

# redis排行榜
Route::get('test-board', function () {
    $redis = new RedisLeaderBoard();
//    // 插入10条数据
//    for ($i = 0; $i < 10; $i++) {
//        $res = $redis->addLeaderBoard('id'. $i, $i);
//        dump($res);
//    }

    // 获取最高分的前5条数据
    $preLimit = $redis->getLeaderBoard(5);

    // 获取最低分的前5条数据
    $nextLimit = $redis->getLeaderBoard(5, false);

    // 取最大值，用最小的key, id0 ~ id9
    $bigValueAes = $redis->getNodeRank('id0'); // 最大值，用最小的key

    // 取最大值，用最大的key, id0 ~ id9
    $bigValueDesc = $redis->getNodeRank('id9', false); // 最大值，用最大的key

    dd($preLimit, $nextLimit, $bigValueAes, $bigValueDesc);
});
