<?php

namespace App\Filament\Hr\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\User;
class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->preload()->live()->required()->afterStateUpdated(function ($state, Set $set) {
                        $user = User::find($state);
                        if ($user) {
                            $set('basic_salary', $user->salary);
                        }
                    })
                    ->searchable(),
                TextInput::make('basic_salary')
                    ->required()->prefix('$')->live()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateNetSalary($state, $get, $set))
                    ->numeric(),
                TextInput::make('allowances')
                    ->required()
                    ->numeric()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateNetSalary($state, $get, $set))->live(onBlur: true)
                    ->default(0.0),
                TextInput::make('deductions')
                    ->required()
                    ->numeric()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateNetSalary($state, $get, $set))->live(onBlur: true)
                    ->default(0.0),
                TextInput::make('net_salary')
                    ->required()->disabled()->prefix('$')->dehydrated()
                    ->numeric(),
                TextInput::make('bonus')
                    ->required()->prefix('$')->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateNetSalary($state, $get, $set))->live(onBlur: true)
                    ->numeric()
                    ->default(0.0),
                Select::make('month')->options([
                    'January' => 'January',
                    'February' => 'February',
                    'March' => 'March',
                    'April' => 'April',
                    'May' => 'May',
                    'June' => 'June',
                    'July' => 'July',
                    'August' => 'August',
                    'September' => 'September',
                    'October' => 'October',
                    'November' => 'November',
                    'December' => 'December',
                ])
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->numeric()->default(date('Y')),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid'])
                    ->default('pending')
                    ->required(),
                DatePicker::make('paid_at'),
            ]);
    }

    protected static function calculateNetSalary($state, Get $get, Set $set)
    {
        $basic_salary = $get('basic_salary') ?? 0;
        $allowances = $get('allowances') ?? 0;
        $deductions = $get('deductions') ?? 0;
        $bonus = $get('bonus') ?? 0;
        $net_salary = $basic_salary + $allowances - $deductions + $bonus;
        $set('net_salary', $net_salary);
    }

}
