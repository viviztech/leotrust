<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'created_by',
        'report_date',
        'title',
        'summary',
        'observations',
        'recommendations',
        'overall_status',
        'health_score',
        'behavior_score',
        'progress_score',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'health_score' => 'decimal:1',
            'behavior_score' => 'decimal:1',
            'progress_score' => 'decimal:1',
            'attachments' => 'array',
        ];
    }

    /**
     * The beneficiary this report belongs to.
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * The user who created this report.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the average score.
     */
    public function getAverageScoreAttribute(): ?float
    {
        $scores = array_filter([
            $this->health_score,
            $this->behavior_score,
            $this->progress_score,
        ], fn($score) => $score !== null);

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 1);
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->overall_status) {
            'excellent' => 'success',
            'good' => 'info',
            'satisfactory' => 'warning',
            'needs_attention' => 'danger',
            'critical' => 'danger',
            default => 'gray',
        };
    }
}
