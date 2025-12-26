<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category',
        'sku',
        'quantity',
        'unit',
        'minimum_threshold',
        'unit_cost',
        'supplier_name',
        'supplier_contact',
        'expiry_date',
        'storage_location',
        'status',
        'notes',
        'last_updated_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'minimum_threshold' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'expiry_date' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inventory) {
            // Auto-update status based on quantity
            if ($inventory->quantity <= 0) {
                $inventory->status = 'out_of_stock';
            } elseif ($inventory->quantity <= $inventory->minimum_threshold) {
                $inventory->status = 'low_stock';
            } else {
                $inventory->status = 'in_stock';
            }
        });
    }

    /**
     * The user who last updated this inventory item.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * Transaction history for this item.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get the category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return config("leofoundation.inventory_categories.{$this->category}.label", ucfirst($this->category));
    }

    /**
     * Get the category icon.
     */
    public function getCategoryIconAttribute(): string
    {
        return config("leofoundation.inventory_categories.{$this->category}.icon", 'heroicon-o-cube');
    }

    /**
     * Check if item is low on stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->minimum_threshold && $this->quantity > 0;
    }

    /**
     * Check if item is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if item is expiring soon (within 30 days).
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date
            && $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get the total value.
     */
    public function getTotalValueAttribute(): ?float
    {
        if ($this->unit_cost === null) {
            return null;
        }

        return $this->quantity * $this->unit_cost;
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'in_stock' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Scope to get low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->where('status', 'low_stock');
    }

    /**
     * Scope to get out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'out_of_stock');
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get expiring items.
     */
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays($days));
    }
}
