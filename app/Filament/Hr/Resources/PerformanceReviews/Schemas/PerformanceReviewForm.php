<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Performance Review Details')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')->relationship('user', 'name')->searchable()->preload()
                            ->required(),
                        Select::make('reviewer_id')->relationship('reviewer', 'name')->searchable()->preload()
                            ->required(),
                        TextInput::make('review_period')->default(now()->format('Y-m-d'))
                            ->required(),
                        TextInput::make('quality_of_work')
                            ->numeric()->minValue(1)->maxValue(5)->live()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateOverallPerformance($state, $get, $set))
                            ->default(null),
                        TextInput::make('communication_skills')
                            ->numeric()->minValue(1)->maxValue(5)->live()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateOverallPerformance($state, $get, $set))
                            ->default(null),
                        TextInput::make('teamwork')
                            ->numeric()->minValue(1)->maxValue(5)->live()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateOverallPerformance($state, $get, $set))
                            ->default(null),
                        TextInput::make('problem_solving')
                            ->numeric()->minValue(1)->maxValue(5)->live()->afterStateUpdated(fn($state, Set $set, Get $get)=>self::calculateOverallPerformance($state, $get, $set))
                            ->default(null),
                        TextInput::make('overall_performance')
                            ->numeric()->suffix('/5')->disabled()->dehydrated()
                            ->default(null),
                    ]),

                
                Section::make('Additional Comments')
                    ->columns(1)
                    ->schema([
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
                    ]),
            ]);
    }

    protected static function calculateOverallPerformance($state, Get $get, Set $set)
    {
        $quality_of_work =(int) $get('quality_of_work') ?? 0;
        $communication_skills = (int) $get('communication_skills') ?? 0;
        $teamwork = (int) $get('teamwork') ?? 0;
        $problem_solving = (int) $get('problem_solving') ?? 0;

        $overall_performance = ($quality_of_work + $communication_skills + $teamwork + $problem_solving) / 4;

        $set('overall_performance', round($overall_performance, 2));
    }
}
