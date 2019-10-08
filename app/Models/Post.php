<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'view_count'];

    /**
     * 获取缓存key，当数据更新时，缓存失效
     * @return string
     */
    public function cacheKey()
    {
        return sprintf(
            "%s/%s-%s",
            $this->getTable(),
            $this->getKey(),
            $this->updated_at->timestamp
        );
    }

    /**
     * 获取模型缓存数据
     * @return mixed
     */
    public function getCacheCommentsCountAttribute()
    {
        return Cache::remember($this->cacheKey() . ':comments_count', 15, function () {
            return $this->commentId();
        });
    }

    public function scopeViewCount($query, int $param)
    {
        return $query->where('view_count', $param);
    }

    /**
     * 用于同一表中，外键和主键在同一表中
     * @return \Illuminate\Database\Eloquent\Relations\HasOne]
     */
    public function commentId()
    {
        return $this->hasOne(self::class, 'comment_id', 'id');
    }
}
