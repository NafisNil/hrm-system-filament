<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;

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
                            ->revealable()->dehydrated(fn($state) => filled($state))->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null),
                            DatePicker::make('date_of_birth'),
                            Textarea::make('address')
                            ->default(null)
                            ->columnSpanFull(),
                            Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->searchable(),
                    ]),

                    Section::make('Employee Information')
                    ->schema([
                        TextInput::make('employee_id')
                            ->readonly()->hiddenOn('create')->unique(ignoreRecord: true),
                        Select::make('department_id')
                            ->relationship('department', 'name')->required()->searchable()->preload()->live(),
                        Select::make('position_id')
                            ->relationship('position', 'title', fn($query, Get $get) => $query->where('department_id', $get('department_id')))->required()->searchable()->preload()->live(),
                    ])
                    ->columns(2),




                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                
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
                
                TextInput::make('emergency_contact_name')
                    ->default(null),
                TextInput::make('emergency_contact_phone')
                    ->tel()
                    ->default(null),
            ]);
    }
}
