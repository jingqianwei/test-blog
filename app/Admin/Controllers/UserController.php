<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('用户列表')
            ->description('description')
            ->body($this->grid()->render());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('用户详情')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('修改用户')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('创建用户')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('Id');
        $grid->avatar('头像')->image(config('app.url'), 50, 50);;
        $grid->name('姓名');
        $grid->phone('电话');
        $grid->email('邮箱');
        $grid->email_verified_at('邮箱验证时间');
        $grid->introduction('简介');
        $grid->last_actived_at('最后活跃时间');
        $grid->created_at('注册时间');
        $grid->updated_at('更新时间');

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->avatar('头像')->image(config('app.url'), 50, 50);
        $show->name('姓名');
        $show->phone('电话');
        $show->email('邮箱');
        $show->email_verified_at('邮箱验证时间');
        $show->password('密码');
        $show->introduction('简介');
        $show->last_actived_at('最后活跃时间');
        $show->created_at('注册时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', '姓名')->rules('required|between:3,25');
        $form->mobile('phone', '电话');
        $form->email('email', '邮箱')->rules(function ($form) {
            return 'required|unique:users,email,' . $form->model()->id;
        });
        $form->datetime('email_verified_at', '邮箱验证时间')->default(date('Y-m-d H:i:s'));
        $form->password('password', '密码')->placeholder('输入重置密码');
        $form->image('avatar', '头像')->move('images/avatars')->uniqueName();
        $form->text('introduction', '简介');
        $form->datetime('last_actived_at', '最后活跃时间')->default(date('Y-m-d H:i:s'));
        $form->saving(function (Form $form) {
            if ($form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        return $form;
    }
}
