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
}
