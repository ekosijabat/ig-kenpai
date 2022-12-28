<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostLikesResource;

class PostLikesListResource extends JsonResource
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
            'id'        => $this->id,
            'caption'   => $this->caption,
            'created'   => $this->created_at->format('d-m-Y'),
            'likes'     => PostLikesResource::collection($this->whenLoaded('post_likes')),
        ];
    }
}
