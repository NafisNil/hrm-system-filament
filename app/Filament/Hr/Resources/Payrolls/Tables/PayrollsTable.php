<?php

namespace App\Filament\Hr\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Jobs\GeneratePayrollsAction;
class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table->headerActions([
            Action::make('generatePayrolls')
                ->label('Generate Payrolls')->icon('heroicon-o-currency-dollar')->schema([
                    // Add any form fields you want to include in the action modal here
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
                    ])->default(now()->format('F'))->required(),
                    TextInput::make('year')->default(now()->year)->minValue(2000)->maxValue(now()->year)->required()->numeric(),
                    Select::make('user_id')->relationship('user', 'name')->preload()->live()->searchable()->required(),

                ])->action(function (array $data) {
                    GeneratePayrollsAction::dispatch($data['month'], $data['year'], $data['user_id'] ?? null);

                    Notification::make()
                        ->title('Payroll generation job dispatched.')
                        ->success()
                        ->send();
                }),
        ])
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('user.employee_id')
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->numeric()->prefix('$')
                    ->sortable(),

                TextColumn::make('net_salary')
                    ->numeric()->prefix('$')
                    ->sortable(),

                TextColumn::make('month')
                    ->searchable(),
                TextColumn::make('year')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('paid_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
