<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lunar\Models\ProductType;
use Lunar\Models\Attribute;

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
            "images" => $this->images,
            "price" => $this->variants[0]->prices[0]->price->decimal(rounding: true),
            "customisations" => $this->customisations->map(function ($item) {
                $item->attribute = $this->customisable_attributes[$item->attribute_id];
                return $item;
            }),
            "rules" => $this->config['rules'],
            "dynamic_pricing" => $this->config['dynamic_pricing'],
        ];
    }
}
