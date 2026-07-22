<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Tables;

use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('leaveType.name')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('days_requested')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('rejected_at')
                    ->dateTime()
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->visible(fn ($record) => $record->status === 'pending'),
                DeleteAction::make()->visible(fn ($record) => $record->status === 'pending'),
                Action::make('approve')->icon('heroicon-o-check-circle')->color('success')
                    ->action(function ($record, $data) {
                        $record->status = 'approved';
                        $record->approved_at = now();
                        $record->rejected_at = null;
                        $record->rejection_reason = null;
                        $record->approved_by = Auth::user()->id;
                        $record->save();

                        Notification::make()
                            ->title('Leave request approved')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),


                    Action::make('reject')->icon('heroicon-o-x-circle')->color('danger')->schema([
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->status = 'rejected';
                        $record->rejected_at = now();
                        $record->approved_at = null;
                        $record->rejection_reason = $data['rejection_reason'];
                        $record->approved_by = null;
                        $record->save();

                        Notification::make()
                            ->title('Leave request rejected')
                            ->danger()
                            ->send();
                    })->visible(fn ($record) => $record->status === 'pending'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
