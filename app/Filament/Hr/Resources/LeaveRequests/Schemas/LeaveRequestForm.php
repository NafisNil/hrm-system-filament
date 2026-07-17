<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Carbon\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->relationship('user', 'name')->preload()->searchable()
                    ->required(),
                Select::make('leave_type_id')->relationship('leaveType', 'name')->preload()->searchable()
                    ->required(),
                DatePicker::make('start_date')->live()->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateDaysRequested($get, $set))
                    ->required(),
                DatePicker::make('end_date')->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateDaysRequested($get, $set))->live()
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('pending')->live()
                    ->required(),
                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('days_requested')->disabled()->dehydrated()
                    ->required()
                    ->numeric()
                    ->default(0),

                DateTimePicker::make('approved_at'),
                DateTimePicker::make('rejected_at')->visible(fn(Get $get) => $get('status') === 'rejected'),
                Textarea::make('rejection_reason')->visible(fn(Get $get) => $get('status') === 'rejected')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    protected static function calculateDaysRequested(Get $get, Set $set): void
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
