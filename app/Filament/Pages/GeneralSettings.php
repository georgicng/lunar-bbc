<?php

namespace App\Filament\Pages;

use Outerweb\FilamentSettings\Filament\Pages\Settings;
use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Forms;

class GeneralSettings extends Settings
{
    public function form(Form $form): Form
    {
        return $form->schema([
                 Forms\Components\Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                         Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                 Forms\Components\TextInput::make('general.brand_name')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Seo')
                            ->schema([
                                 Forms\Components\TextInput::make('seo.title')
                                    ->required(),
                                 Forms\Components\TextInput::make('seo.description')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
