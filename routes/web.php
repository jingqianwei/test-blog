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
