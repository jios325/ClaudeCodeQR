<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DynamicQRCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'filename' => $this->filename,
            'redirect_identifier' => $this->redirect_identifier,
            'url' => $this->url,
            'foreground_color' => $this->foreground_color,
            'background_color' => $this->background_color,
            'precision' => $this->precision,
            'size' => $this->size,
            'scan_count' => $this->scan_count,
            'status' => $this->status,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
