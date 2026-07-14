<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;
class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->default(null),
                Select::make('manager_id')->relationship('manager', 'name')->default(null)->searchable()->preload()->live(),
                ColorPicker::make('color_name')->default(null)->label('Color Name'),

            ]);
    }
}
