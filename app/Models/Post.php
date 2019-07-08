<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'view_count'];

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
