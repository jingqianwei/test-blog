<?php

namespace App\Models;

use App\Traits\HashIdHelper;
use App\Traits\UuidTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, UuidTrait, HashIdHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 禁用时间更新
    //public $timestamps = false;

    /**
     * 在模型创建时，生成 UUID v4 。
     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        self::uuid();
//    }

    /**
     * 用户头像访问器
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        $disk = config('admin.upload.disk');

        // 解决头像显示不正常的问题
        if ($avatar && array_key_exists($disk, config('filesystems.disks'))) {
            return Storage::disk(config('admin.upload.disk'))->url($avatar);
        }

        return admin_asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg');
    }

    /**
     * 用户头像修改器
     * Set avatar attribute.
     *
     * @param string $path
     */
//    public function setAvatarAttribute($path)
//    {
//        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
//        if ( ! starts_with($path, 'http')) {
//
//            $path = Storage::disk(config('admin.upload.disk'))->url($path);
//            // 拼接完整的 URL
//            //$path = config('app.url') . "/uploads/images/avatars/$path";
//        }
//
//        $this->attributes['avatar'] = $path;
//    }
}
