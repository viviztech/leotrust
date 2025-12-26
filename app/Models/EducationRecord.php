<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'school_name',
        'grade',
        'section',
        'academic_year',
        'attendance_percentage',
        'performance',
        'subjects',
        'achievements',
        'areas_of_improvement',
        'teacher_contact',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'attendance_percentage' => 'decimal:2',
        ];
    }

    /**
     * The beneficiary this record belongs to.
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * Get the performance color.
     */
    public function getPerformanceColorAttribute(): string
    {
        return match ($this->performance) {
            'excellent' => 'success',
            'good' => 'info',
            'average' => 'warning',
            'below_average' => 'danger',
            'poor' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get the attendance status.
     */
    public function getAttendanceStatusAttribute(): string
    {
        if ($this->attendance_percentage === null) {
            return 'unknown';
        }

        return match (true) {
            $this->attendance_percentage >= 90 => 'excellent',
            $this->attendance_percentage >= 75 => 'good',
            $this->attendance_percentage >= 60 => 'average',
            default => 'poor',
        };
    }
}
