<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Forms\Components\Attributes;
use App\Forms\Components\Block;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Builder as FormBuilder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Lunar\Models\AttributeGroup;
use Lunar\Admin\Support\Facades\AttributeData;


class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()
                    ->schema([
                        static::getMetaAttributeDataFormComponent(),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        FormBuilder::make('blocks')
                            ->blocks(static::getBlocks()),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->required(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    protected static function getMetaAttributeDataFormComponent(): Component
    {
        return Attributes::make()
            ->using(Page::class)
            ->attributeDataField('attribute_data')
            ->setHook(fn($query) => $query->where('attribute_group_id', 8));
    }

    protected static function getBlocks(): array
    {
        $values = AttributeGroup::where(
            'attributable_type',
            'page'
        )
            ->where('handle', '<>', 'meta')
            ->orderBy('position', 'asc')
            ->get()
            ->map(function ($group) {
                $fields = $group->attributes->map(function ($field) {
                    return AttributeData::getFilamentComponent($field);
                })->toArray();
                return  FormBuilder\Block::make($group->handle)
                    ->schema($fields);
            })->toArray();
        logger()->info($values);
        return $values;
    }

    protected static function getBlocksAttributeDataFormComponent(): array
    {
        return  [
            FormBuilder\Block::make('newArrivals')
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('subtitle')->required(),
                    Repeater::make('products')
                        ->schema([
                            TextInput::make('product')->required(),
                        ])
                ]),
            FormBuilder\Block::make('features')
                ->schema([
                    Repeater::make('feature')
                        ->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('caption')->required(),
                            TextInput::make('icon')->required(),
                        ])
                ]),
        ];
    }
}
