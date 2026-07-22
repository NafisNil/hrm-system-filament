<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Hidden;
use Carbon\Carbon;
class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->relationship('user', 'name')
                    ->default(auth()->id())->disabled()->dehydrated(),
                Select::make('leave_type_id')->relationship('leaveType', 'name')
                    ->required(),
                DatePicker::make('start_date')->minDate(now()->toDateString())->live()->afterStateUpdated(fn($state, Get $get, Set $set)=>self::calculateDaysRequested($state, $get, $set))
                    ->required(),
                DatePicker::make('end_date')->live()->afterStateUpdated(fn($state, Get $get, Set $set)=>self::calculateDaysRequested($state, $get, $set))->minDate(now()->toDateString())
                    ->required(),

                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('days_requested')
                    ->required()->disabled()
                    ->numeric()
                    ->default(0),

                Hidden::make('status')->default('pending'),
            ]);
    }

    protected static function calculateDaysRequested($state, Get $get, Set $set): void
    {
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $daysRequested = $startDate->diffInDays($endDate) + 1; // +1 to include the start date
            $set('days_requested', $daysRequested);
        } else {
            $set('days_requested', 0);
        }
    }
}
