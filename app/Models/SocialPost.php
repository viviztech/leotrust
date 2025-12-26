<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'image_path',
        'additional_images',
        'scheduled_at',
        'published_at',
        'status',
        'user_id',
        'error_message',
        'retry_count',
    ];

    protected function casts(): array
    {
        return [
            'additional_images' => 'array',
            'scheduled_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The user who created this post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Social accounts this post will be published to.
     */
    public function socialAccounts(): BelongsToMany
    {
        return $this->belongsToMany(SocialAccount::class)
            ->withPivot(['platform_post_id', 'platform_post_url', 'status', 'error_message', 'published_at'])
            ->withTimestamps();
    }

    /**
     * Check if post is ready to publish.
     */
    public function getIsReadyToPublishAttribute(): bool
    {
        return $this->status === 'scheduled'
            && $this->scheduled_at
            && $this->scheduled_at->isPast();
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'scheduled' => 'info',
            'publishing' => 'warning',
            'published' => 'success',
            'failed' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get character count for content.
     */
    public function getCharacterCountAttribute(): int
    {
        return mb_strlen($this->content);
    }

    /**
     * Get the content preview (truncated).
     */
    public function getContentPreviewAttribute(): string
    {
        return mb_strlen($this->content) > 100
            ? mb_substr($this->content, 0, 100) . '...'
            : $this->content;
    }

    /**
     * Scope to get scheduled posts.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope to get posts ready to publish.
     */
    public function scopeReadyToPublish($query)
    {
        return $query->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now());
    }

    /**
     * Scope to get drafts.
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get failed posts.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
