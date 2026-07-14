<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')->preload()
                    ->required(),
                TextInput::make('min_salary')->prefix('$')
                    ->numeric()
                    ->default(null),
                TextInput::make('max_salary')->prefix('$')
                    ->numeric()->gte('min_salary')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
