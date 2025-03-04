<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScanLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'qr_code_id' => $this->qr_code_id,
            'qr_code_type' => $this->qr_code_type,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'timestamp' => $this->timestamp,
        ];
    }
}
