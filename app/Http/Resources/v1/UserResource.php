<?php

namespace App\Http\Resources\v1;

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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => \Carbon\Carbon::parse($this->email_verified_at)->toIso8601String(),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->toIso8601String(),
            'updated_at' => \Carbon\Carbon::parse($this->updated_at)->toIso8601String(),
        ];
    }
}
