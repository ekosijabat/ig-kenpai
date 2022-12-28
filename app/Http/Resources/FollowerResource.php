<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'        => (isset($this->action)) ? $this->following->id : $this->followers->id,
            'name'      => (isset($this->action)) ? $this->following->first_name . ' ' . $this->following->last_name : $this->followers->first_name . ' ' . $this->followers->last_name,
            'since'     => (isset($this->action)) ? $this->following->created_at->format('d-m-Y') : $this->followers->created_at->format('d-m-Y'),
            'pic'       => (isset($this->action)) ? $this->following->profile_pic : $this->followers->profile_pic,
            'pic_path'  => (isset($this->action)) ? $this->following->path : $this->followers->path,
        ];
    }
}
