<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
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
            'name'      => $this->last_name,
            'email'     => $this->email,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'phone_number' => $this->phone_number,
            'date_of_birth' => Carbon::parse($this->date_of_birth)->format('d-m-Y'),
            'profile_pic' => $this->profile_pic
        ];
    }
}
