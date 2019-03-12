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
