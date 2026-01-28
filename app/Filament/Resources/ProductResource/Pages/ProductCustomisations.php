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

    public $pricingType;

    public $rules;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->fields = $this->customisationAttributes->toArray();
        $this->model = $this->mapValues->toArray();
        $this->pricingType = $this->config['pricing_type'];
        $this->rules = $this->config['rules'];
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
        return $this->customisationAttributes->keys()->map(function (int $id) {
            $value = $this->customisations->firstWhere('attribute_id', $id);
            if (empty($value)) {
                return  [
                    'required' => 0,
                    'min' => null,
                    'max' => null,
                    'attribute_data' => [],
                    'attribute_id' => $id,
                    'product_id' => $this->record->id,
                    'position' => 1
                ];
            }
            return $value;
        });
    }

    #[Computed]
    public function config()
    {
        return $this->record->meta['config'] ?? ['rules' => [], 'pricing_type' => 'fixed'];
    }

    public function saveCustom()
    {
        logger('values.', ['model' => $this->model]);
        foreach ($this->model as $key => $value) {
            $collection = collect($value);
            ProductCustomisation::updateOrCreate(
                $collection->only(['product_id', 'attribute_id'])->toArray(),
                $collection->except(['product_id', 'attribute_id'])->toArray()
            );
        }/*
        ProductCustomisation::upsert($this->model, ['product_id', 'attribute_id'], [
            'required',
            'min',
            'max',
            'attribute_data',
            'position'
        ]); */
        if (!isset($this->record->meta)) {
            $this->record->meta = [];
        }
        $this->record->meta = [...$this->record->meta, 'config' => ['rules' => $this->rules, 'pricing_type' => $this->pricingType]];
        $this->record->save();
        //$this->notify('success', 'Customisations saved successfully.');
    }
}
