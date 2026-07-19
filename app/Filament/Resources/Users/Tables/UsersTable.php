<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee_id')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employment_type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->sortable(),
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
