<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

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
            "id" => $this->id,
            "username" => $this->username,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "phone_number" => $this->phone_number,
            "date_of_birth" => $this->date_of_birth,
        ];
    }
}
