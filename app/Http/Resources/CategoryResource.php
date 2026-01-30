<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "data" => $this->attribute_data,
            "media" => [
                "id" => $this->media->id,
                "link" => $this->media->getUrl(),
                "meta" => $this->media->custom_properties

            ]
        ];
    }
}
