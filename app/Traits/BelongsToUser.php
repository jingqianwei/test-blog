<?php

namespace App\Traits;


/**
 * trait的使用
 * Trait BelongsToUser
 * @package App\Traits
 */
trait  BelongsToUser{
    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

