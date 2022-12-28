<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostLikesResource;

class PostCommentResource extends JsonResource
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
            'id'    => $this->id,
            'user'  => $this->user->first_name . ' ' . $this->user->last_name,
            'comments'  => $this->comments,
            'created' => $this->created_at->format('d-m-Y'),
            #'children' => PostCommentResource::collection($this->whenLoaded('post_comments'))
        ];

    }
}
