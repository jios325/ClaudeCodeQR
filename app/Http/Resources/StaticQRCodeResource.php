<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaticQRCodeResource extends JsonResource
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
            'content_type' => $this->content_type,
            'content' => $this->content,
            'foreground_color' => $this->foreground_color,
            'background_color' => $this->background_color,
            'precision' => $this->precision,
            'size' => $this->size,
            'format' => $this->format,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
