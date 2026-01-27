<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\ProductCustomisation;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Livewire\Attributes\Computed;

use Lunar\Admin\Filament\Resources\ProductResource;
use Filament\Resources\Pages\Page;
use Lunar\Admin\Support\Facades\AttributeData;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;

class ProductCustomisations extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.product-customisations';

    use InteractsWithRecord;

    public $model;
    public $fields;

    public $pricingType = 'fixed';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->fields = $this->customisationAttributes->toArray();
        $this->model = $this->mapValues->toArray();
    }


    #[Computed]
    public function customisationAttributes()
    {
        $productTypeId = $this->record?->product_type_id;

        // If we have a product type, the attributes should be based off that.
        if ($productTypeId) {
            return  ProductType::find($productTypeId)->customisationAttributes->mapWithKeys(function (Attribute $attribute) {
                return [
                    $attribute->id => $attribute,
                ];
            });
        }

        return collect();
    }

    #[Computed]
    public function customisations()
    {
        return $this->record->customisations;
    }


    #[Computed]
    public function mapValues()
    {
        return $this->customisationAttributes->keys()->map(function (string $id) {
            $value = $this->customisations->firstWhere('option_id', $id);
            if (empty($value)) {
                return  [
                    'required' => null,
                    'min' => null,
                    'max' => null,
                    'value' => [],
                    'option_id' => $id,
                    'product_id' => $this->record->id,
                    'position' => 1
                ];
            }
            return $value;
        });
    }
}
