<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/5/13
 * Time: 18:26
 */

namespace App\Repository;


use App\Models\User;

/**
 * 操作user模型的仓库类，作用是，容易对模型数据进行处理
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
