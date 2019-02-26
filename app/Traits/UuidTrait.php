<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{

    /**
     * 在模型创建时，生成 UUID v4 。
     */
    protected static function boot()
    {
        parent::boot();

        self::uuid();
    }

    /**
     * 设置 UUID 对应的模型的字段。
     * @return string
     */
    protected static function uuidField()
    {
        return 'uuid';
    }

    /**
     * 重写模型的 boot()。
     */
    protected static function uuid()
    {
        static::creating(function ($model) {
            $model->{self::uuidField()} = Uuid::uuid4()->toString();
        });
    }
}
