<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->uuid,
            "email" => $this->email,
            "name" => $this->name,
            "emailVerificationAt" => $this->email_verification_at,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updatedAt
        ];
    }
}
