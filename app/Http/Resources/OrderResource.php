<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "userId" => $this->user_id,
            "status" => $this->status,
            "reference" => $this->reference,
            "notes" => $this->notes,
            "currencyCode" => $this->currency_code,
            "total" => $this->total,
            "subTotal" => $this->sub_total,
            "shippingTotal" => $this->shipping_total,
            "taxTotal" => $this->tax_total,
            "taxBreakdown" => $this->tax_breakdown,
            "discountTotal" => $this->discount_total,
            "placedAt" => $this->placed_at,
            "lines" => $this->lines->map(function ($line) {
                return [
                    'id' => $line->id,
                    'identifier' => $line->identifier,
                    'quantity' => $line->quantity,
                    'name' => $line->description,
                    'image' => $line->purchasable->product->getMedia('images')->first()?->getUrl(),
                    'option' => $line->option,
                    'meta' => $line->meta,
                    'subTotal' => $line->sub_total,
                    'unitPrice' => $line->unit_price,
                ];
            }),
        ];
    }
}
