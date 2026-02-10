<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Lunar\Admin\Support\Facades\AttributeData;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;

class Block extends Forms\Components\Group
{
    public ?string $modelClassOverride = null;
    //protected string $view = 'forms.components.attributes';

    protected Closure $hook;

    protected string|Closure $attributeGroupField = '';

    public function attributeGroupField(string|Closure $attributeGroupField): static
    {
        $this->attributeGroupField = $attributeGroupField;

        if (blank($this->relationship)) {
            $this->statePath($attributeGroupField);
        }

        return $this;
    }

    public function getattributeGroupField(): string
    {
        return $this->evaluate($this->attributeGroupField);
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
        return 'attributeGroup' . $this->modelClassOverride;
    }

    protected function mount(): void
    {
        $this->statePath($this->attributeGroupField);
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (blank($this->childComponents)) {
            $this->schema(function (\Filament\Forms\Get $get, Livewire $livewire, ?Model $record) {
                $modelClass = $this->modelClassOverride ?: $livewire::getResource()::getModel();

                $morphMap = $modelClass::morphName();

                $attributeQuery = Attribute::where('attribute_group_id', $this->statePath($this->attributeGroupField));
                $attributes = $attributeQuery->orderBy('position')->get();
                $group = $attributes->first()->attributeGroup;
                $fields = $attributes->map(function ($field) {
                    return AttributeData::getFilamentComponent($field);
                });


                return Forms\Components\Section::make($group->translate('name'))
                    ->schema($fields);
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
                $component->getattributeGroupField() => $data,
            ];
        });

        $this->mutateRelationshipDataBeforeFillUsing(static function (Attributes $component, array $data): array {
            return $data[$component->getattributeGroupField()] ?? [];
        });
    }
}
