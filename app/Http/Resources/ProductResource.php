<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "collection" => $this->collections[0]->attribute_data,
            "media" => [
                "id" => $this->images[0]->id,
                "link" => $this->images[0]->getUrl(),
                "meta" => $this->images[0]->custom_properties

            ],
            "price" => $this->variants[0]->prices[0]->price->value,
            "customisations" => $this->customisations,
        ];
    }
}
