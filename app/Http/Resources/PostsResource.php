<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostsResource;

class PostsResource extends JsonResource
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
            'id'            => $this->id,
            'caption'       => $this->caption,
            'total_likes'   => $this->total_likes,
            'status'        => $this->status,
            'images'     => PostPictureResource::collection($this->whenLoaded('post_pictures')),
            'created'    => $this->created_at->format('d-m-Y'),
        ];
    }
}
