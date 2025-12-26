<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency',
        'donor_email',
        'donor_name',
        'donor_phone',
        'transaction_id',
        'payment_gateway',
        'status',
        'is_recurring',
        'recurring_interval',
        'subscription_id',
        'user_id',
        'campaign_id',
        'receipt_number',
        'receipt_sent',
        'receipt_sent_at',
        'donor_message',
        'is_anonymous',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_recurring' => 'boolean',
            'receipt_sent' => 'boolean',
            'receipt_sent_at' => 'datetime',
            'is_anonymous' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($donation) {
            if (empty($donation->receipt_number)) {
                $donation->receipt_number = 'LEO-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * The user who made this donation (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The campaign this donation is for (optional).
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        $currencies = config('leofoundation.donations.currencies');
        $symbol = $currencies[$this->currency]['symbol'] ?? $this->currency;

        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get the donor display name.
     */
    public function getDonorDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous Donor';
        }

        return $this->donor_name ?? 'Anonymous';
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Scope to get completed donations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get recurring donations.
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Scope to filter by payment gateway.
     */
    public function scopeByGateway($query, string $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }
}
