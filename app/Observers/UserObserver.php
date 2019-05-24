<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * 监听数据即将保存的事件。
     *
     * @param  User $user
     * @return void
     */
    public function saving(User $user)
    {
        // todo 相当于对user表得各个字段完成了赋值，但还没开始进行sql插入语句的执行
        \Log::info('监听数据即将保存的事件: saving ', $user->toArray());
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function saved(User $user)
    {
        // todo 跟在created(updated)后面，sql插入(更新)语句已经执行完毕后，created(updated)事件结束后触发的事件
        \Log::info('监听数据保存后的事件: saved ', $user->toArray());
    }

    /**
     * 监听数据即将创建的事件。
     *
     * @param  User $user
     * @return void
     */
    public function creating(User $user)
    {
        // todo 跟在saving事件后面，但还是没进行sql插入语句的执行
        \Log::info('监听数据即将创建的事件: creating ', $user->toArray());
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {
        // todo 跟在creating后面，sql插入语句已经执行完毕后，触发的事件
        \Log::info('监听数据创建后的事件: created ', $user->toArray());
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param  User $user
     * @return void
     */
    public function updating(User $user)
    {
        // todo 跟在saving事件后面，但还是没进行sql更新语句的执行
        \Log::info('监听数据即将更新的事件: updating ', $user->toArray());
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function updated(User $user)
    {
        // todo 跟在updating后面，sql更新语句已经执行完毕后，触发的事件
        \Log::info('监听数据更新后的事件: updated ', $user->toArray());
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param  User $user
     * @return void
     */
    public function deleting(User $user)
    {
        // todo 表中数据删除前的执行，已经指定要删除的记录，但还没执行sql删除语句
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function deleted(User $user)
    {
        // todo 表中数据删除后的执行，sql删除语句已经执行完毕后，触发的事件
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param  User $user
     * @return void
     */
    public function restoring(User $user)
    {

    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function restored(User $user)
    {

    }

    /**
     *  url地址：https://learnku.com/articles/6657/model-events-and-observer-in-laravel
     *  分析完毕，大概可以做一个总结了。
     *  当模型已存在，不是新建的时候，依次触发的顺序是:
     *  saving -> updating -> updated -> saved
     *  当模型不存在，需要新增的时候，依次触发的顺序则是
     *  saving -> creating -> created -> saved
     *  那么 saving,saved 和 updating,updated 到底有什么区别呢？
     *  上面已经讲过，Laravel 的 Eloquent 会维护实例的两个数组，分别是 original 和 attributes
     *  只有在 saved 事件触发之后，Laravel 才会对两个数组执行 syncOriginal 操作，这样就很好理解了。
     *  updating 和 updated 会在数据库中的真值修改前后触发。
     *  saving 和 saved 则会在 Eloquent 实例的 original 数组真值更改前后触发。
     *  这样我们就可以根据业务场景来选择更合适的触发事件了～
     */
}
