<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'mobile' => $this->mobile,
            'phone' => $this->appid,
            'email'=> $this->email,
            // 还可以输出关联表中的值，例如 posts跟users有关联
            'post' => $this->post // 输出posts表中匹配的值
        ];
    }
}
