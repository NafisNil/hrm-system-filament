<?php

namespace App\Filament\Hr\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->relationship('user', 'name')->preload()->searchable()->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in_time'),
                TimePicker::make('check_out_time'),
                ToggleButtons::make('status')
                    ->options(['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late'])->colors(['present' => 'success', 'absent' => 'danger', 'late' => 'warning'])->grouped() 
                    ->default('present')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
