<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'reviewer_id', 'review_period', 'quality_of_work', 'communication_skills', 'teamwork', 'problem_solving', 'overall_performance', 'strengths', 'areas_for_improvement', 'goals', 'additional_comments'])]
class PerformanceReview extends Model
{
    //
    protected $casts = [
        'overall_performance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
