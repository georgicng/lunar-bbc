<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Lunar\Admin\Support\Facades\AttributeData;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;

class Attributes extends Forms\Components\Group
{
    public ?string $modelClassOverride = null;
    //protected string $view = 'forms.components.attributes';

    protected Closure $hook;

    protected string|Closure $attributeDataField = 'attribute_data';

    public function attributeDataField(string|Closure $attributeDataField): static
    {
        $this->attributeDataField = $attributeDataField;

        if (blank($this->relationship)) {
            $this->statePath($attributeDataField);
        }

        return $this;
    }

    public function getAttributeDataField(): string
    {
        return $this->evaluate($this->attributeDataField);
    }

    public function setHook(callable $callback): self
     {
         $this->hook = $callback;
         return $this;
    }

    public function using(string $modelClass): self
    {
        $this->modelClassOverride = $modelClass;

        return $this;
    }

    public function getKey(): ?string
    {
        return 'attributeData'.$this->modelClassOverride;
    }

    protected function mount (): void {
        $this->statePath($this->attributeDataField);
    }

    protected function setUp(): void
    {
        parent::setUp();        

        if (blank($this->childComponents)) {
            $this->schema(function (\Filament\Forms\Get $get, Livewire $livewire, ?Model $record) {
                $modelClass = $this->modelClassOverride ?: $livewire::getResource()::getModel();

                $productTypeId = null;

                $morphMap = $modelClass::morphName();

                $attributeQuery = Attribute::where('attribute_type', $morphMap);
                
                if ($this->hook) {
                    ($this->hook)($attributeQuery);
                }

                $attributes = $attributeQuery->orderBy('position')->get();

                $groups = AttributeGroup::where(
                    'attributable_type',
                    $morphMap
                )->orderBy('position', 'asc')
                    ->get()
                    ->map(function ($group) use ($attributes) {
                        return [
                            'model' => $group,
                            'fields' => $attributes->groupBy('attribute_group_id')->get($group->id, []),
                        ];
                    })
                    ->filter(fn ($group) => count($group['fields']));

                $groupComponents = [];

                foreach ($groups as $group) {
                    $sectionFields = [];

                    foreach ($group['fields'] as $field) {
                        $sectionFields[] = AttributeData::getFilamentComponent($field);
                    }
                    $groupComponents[] = Forms\Components\Section::make($group['model']
                        ->translate('name'))
                        ->schema($sectionFields);
                }

                return $groupComponents;
            });
        }

        $this->mutateStateForValidationUsing(function ($state) {
            if (! is_array($state)) {
                return $state;
            }

            foreach ($state as $key => $value) {
                if (! $value instanceof \Lunar\Base\FieldType) {
                    continue;
                }

                $state[$key] = $value->getValue();
            }

            return $state;
        });

        $this->mutateRelationshipDataBeforeSaveUsing(static function (Attributes $component, array $data): array {
            return [
                $component->getAttributeDataField() => $data,
            ];
        });

        $this->mutateRelationshipDataBeforeFillUsing(static function (Attributes $component, array $data): array {
            return $data[$component->getAttributeDataField()] ?? [];
        });
    }
}