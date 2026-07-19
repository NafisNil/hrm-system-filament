<?php

namespace App\Filament\Employee\Resources\PerformanceReviews\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PerformanceReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('reviewer_id')
                    ->numeric(),
                TextEntry::make('review_period'),
                TextEntry::make('quality_of_work')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('communication_skills')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('teamwork')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('problem_solving')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('overall_performance')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('strengths')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('areas_for_improvement')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('goals')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('additional_comments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
