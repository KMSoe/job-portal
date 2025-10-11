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
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'designation' => $this->employee?->designation ?? null,
            'photo_url'   => $this->photo_url,
            'google_account_connected' => isset($this->google_refresh_token) && now()->lt($this->google_token_expires_at) ? true : false,
        ];
    }
}
