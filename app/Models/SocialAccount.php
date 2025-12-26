<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'provider_id',
        'account_name',
        'account_username',
        'account_avatar',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_active',
        'user_id',
        'metadata',
        'last_used_at',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
            'last_used_at' => 'datetime',
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
        ];
    }

    /**
     * The user who owns this account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Social posts published to this account.
     */
    public function socialPosts(): BelongsToMany
    {
        return $this->belongsToMany(SocialPost::class)
            ->withPivot(['platform_post_id', 'platform_post_url', 'status', 'error_message', 'published_at'])
            ->withTimestamps();
    }

    /**
     * Check if token is expired.
     */
    public function getIsTokenExpiredAttribute(): bool
    {
        if ($this->token_expires_at === null) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Get the provider icon.
     */
    public function getProviderIconAttribute(): string
    {
        return config("leofoundation.social_platforms.{$this->provider_name}.icon", 'heroicon-o-share');
    }

    /**
     * Get the provider color.
     */
    public function getProviderColorAttribute(): string
    {
        return config("leofoundation.social_platforms.{$this->provider_name}.color", '#000000');
    }

    /**
     * Scope to get active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by provider.
     */
    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider_name', $provider);
    }

    /**
     * Scope to get accounts with valid tokens.
     */
    public function scopeWithValidToken($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('token_expires_at')
                ->orWhere('token_expires_at', '>', now());
        });
    }
}
