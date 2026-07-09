<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $context) => $context === 'create')
                            ->revealable(),
                    ]),

                TextInput::make('department_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('position_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('employee_id')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                DatePicker::make('date_of_birth'),
                DatePicker::make('hire_date'),
                Select::make('employment_type')
                    ->options([
            'full_time' => 'Full time',
            'part_time' => 'Part time',
            'contractor' => 'Contractor',
            'intern' => 'Intern',
        ])
                    ->default(null),
                Select::make('status')
                    ->options([
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_leave' => 'On leave',
            'terminated' => 'Terminated',
        ])
                    ->default('active')
                    ->required(),
                TextInput::make('salary')
                    ->numeric()
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('emergency_contact_name')
                    ->default(null),
                TextInput::make('emergency_contact_phone')
                    ->tel()
                    ->default(null),
            ]);
    }
}
