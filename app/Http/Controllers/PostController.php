<?php

namespace App\Http\Controllers;

use App\Events\PostViewEvent;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * 缓存时间为30分钟
     * @var int
     */
    public $cacheExpires = 5;

    public function showPost(Request $request, $id)
    {
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
}
