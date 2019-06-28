<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes; // 软删除

    /**
     * 取消订单
     */
    public function cancel()
    {

    }
}
