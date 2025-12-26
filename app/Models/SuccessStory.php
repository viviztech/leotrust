<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SuccessStory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'original_content',
        'anonymized_content',
        'short_excerpt',
        'category',
        'beneficiary_id',
        'featured_image',
        'gallery_images',
        'is_published',
        'is_featured',
        'published_at',
        'created_by',
        'approved_by',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            if (empty($story->slug)) {
                $story->slug = Str::slug($story->title);
            }
        });
    }

    /**
     * The beneficiary this story is about (optional, for internal reference).
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * The user who created this story.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who approved this story.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'recovery' => 'Recovery Journey',
            'education' => 'Educational Success',
            'welfare' => 'Welfare Support',
            'health' => 'Health & Wellness',
            default => ucfirst($this->category),
        };
    }

    /**
     * Get the category color.
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'recovery' => 'success',
            'education' => 'info',
            'welfare' => 'warning',
            'health' => 'primary',
            default => 'gray',
        };
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Scope to get published stories.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get featured stories.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
