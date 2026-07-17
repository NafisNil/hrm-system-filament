<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reviewer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('review_period')
                    ->required(),
                TextInput::make('quality_of_work')
                    ->numeric()
                    ->default(null),
                TextInput::make('communication_skills')
                    ->numeric()
                    ->default(null),
                TextInput::make('teamwork')
                    ->numeric()
                    ->default(null),
                TextInput::make('problem_solving')
                    ->numeric()
                    ->default(null),
                TextInput::make('overall_performance')
                    ->numeric()
                    ->default(null),
                Textarea::make('strengths')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('areas_for_improvement')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('goals')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('additional_comments')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
