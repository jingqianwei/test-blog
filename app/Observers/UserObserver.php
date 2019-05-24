<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param  User $user
     * @return void
     */
    public function creating(User $user)
    {

    }

    /**
     * 监听数据创建后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {

    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param  User $user
     * @return void
     */
    public function updating(User $user)
    {

    }

    /**
     * 监听数据更新后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function updated(User $user)
    {

    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param  User $user
     * @return void
     */
    public function saving(User $user)
    {

    }

    /**
     * 监听数据保存后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function saved(User $user)
    {

    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param  User $user
     * @return void
     */
    public function deleting(User $user)
    {

    }

    /**
     * 监听数据删除后的事件。
     *
     * @param  User $user
     * @return void
     */
    public function deleted(User $user)
    {

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
