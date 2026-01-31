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
        $customisationAttributes = [];
        if ($this->product_type_id) {
            $customisationAttributes = ProductType::find($this->product_type_id)->customisationAttributes->mapWithKeys(function (Attribute $attribute) {
                return [
                    $attribute->id => $attribute,
                ];
            });
        }
        $config  = $this->meta['config'] ?? ['rules' => [], 'dynamic_pricing' => false];
        return [
            "id" => $this->id,
            "data" => $this->attribute_data,
            "collection" => $this->collections[0]->attribute_data,
            "images" => $this->images,
            "price" => $this->variants[0]->prices[0]->price->value,
            "customisations" => $this->customisations->map(function ($item) use ($customisationAttributes) {
                $item->attribute = $customisationAttributes->get($item->attribute_id);
                return $item;
            }),
            "rules" => $config['rules'] ?? [],
            "dynamic_pricing" => $config['dynamic_pricing'] ?? false,
        ];
    }
}
