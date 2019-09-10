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
 * 资源库模式（Repository）
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

    /**
     * 根据id查询
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->user->find($id);
    }

    /**
     * 创建数据
     * @param array $data
     * @return mixed
     */
    public function create(array $data): bool
    {
        return $this->user->create($data);
    }
}
