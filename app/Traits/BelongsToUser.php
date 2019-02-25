<?php

namespace App\Traits;

/**
 * trait的使用
*/
trait  BelongsToUser{
    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

