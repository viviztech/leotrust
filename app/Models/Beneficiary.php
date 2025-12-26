<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'type',
        'status',
        'admission_date',
        'gender',
        'blood_group',
        'address',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_conditions',
        'notes',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'admission_date' => 'date',
        ];
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the age attribute.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return config("leofoundation.beneficiary_types.{$this->type}.label", ucfirst($this->type));
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return config("leofoundation.beneficiary_statuses.{$this->status}.label", ucfirst($this->status));
    }

    /**
     * Progress reports for this beneficiary.
     */
    public function progressReports(): HasMany
    {
        return $this->hasMany(ProgressReport::class);
    }

    /**
     * Education record for this beneficiary.
     */
    public function educationRecord(): HasOne
    {
        return $this->hasOne(EducationRecord::class)->latestOfMany();
    }

    /**
     * All education records for this beneficiary.
     */
    public function educationRecords(): HasMany
    {
        return $this->hasMany(EducationRecord::class);
    }

    /**
     * Success stories about this beneficiary.
     */
    public function successStories(): HasMany
    {
        return $this->hasMany(SuccessStory::class);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active beneficiaries.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
