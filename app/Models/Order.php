<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'table_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['style_name', 'date'];

    protected static function booted()
    {
        static::created(function ($order) {
            $order->update([
                'group_number' => (int) str_pad($order->id, 6, '0', STR_PAD_LEFT),
            ]);
        });
    }

    public function getStyleNameAttribute()
    {
        if (! $this->style) {
            return null;
        }

        $style = FoodStyle::find($this->style);

        return $style ? $style->name : null;
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class, 'item_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status scopes
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeConfirmed($query) { return $query->where('status', 'confirmed'); }
    public function scopePreparing($query) { return $query->where('status', 'preparing'); }
    public function scopeReady($query) { return $query->where('status', 'ready'); }
    public function scopeDelivered($query) { return $query->where('status', 'delivered'); }
    public function scopePaid($query) { return $query->where('status', 'paid'); }
    public function scopeCancelled($query) { return $query->where('status', 'cancelled'); }

    // Generate order number
    public static function generateOrderNumber(): string
    {
        return strtoupper(Str::random(10));
    }

    // Calculate totals
    public function calculateSubtotal(): float
    {
        return $this->items->sum('total_price');
    }
    public function calculateTax(float $rate = 0.0): float
    {
        return round($this->calculateSubtotal() * $rate, 2);
    }
    public function calculateTotal(): float
    {
        return round($this->calculateSubtotal() + $this->tax_amount, 2);
    }

    // Validation rules
    public static function rules(): array
    {
        return [
            'customer_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'order_number' => 'required|unique:orders,order_number',
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,paid,cancelled',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    // Status transition methods
    public function canTransitionTo($newStatus): bool
    {
        $valid = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['delivered', 'cancelled'],
            'delivered' => ['paid', 'cancelled'],
            'paid' => [],
            'cancelled' => [],
        ];
        return in_array($newStatus, $valid[$this->status] ?? []);
    }
    public function transitionStatus($newStatus): bool
    {
        if ($this->canTransitionTo($newStatus)) {
            $this->status = $newStatus;
            $this->save();
            return true;
        }
        return false;
    }
}
