<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    /**
     * 注册
     * @param Request $request
     * @return bool|void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return false;
        }

        // 保存注册数据
        $user = User::create($request->all());

        // 发送邮件，推荐注册逻辑，这两件事一起都完成了，这样可以让控制器看起来更整洁，逻辑也更清晰
        event(new UserRegistered($user));

        return Auth::login($user);
    }
}
