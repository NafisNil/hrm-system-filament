<?php

namespace App\Filament\Employee\Resources\PerformanceReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerformanceReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table->modifyQueryUsing(function ($query) {
            return $query->where('user_id', auth()->id());
        })->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reviewer_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('review_period')
                    ->searchable(),
                TextColumn::make('quality_of_work')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('communication_skills')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('teamwork')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('problem_solving')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overall_performance')
                    ->numeric()
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
