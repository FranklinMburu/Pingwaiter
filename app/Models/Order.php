<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $casts = [
        'status' => OrderStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'item_id',
        'user_id',
        'group_number',
        'approved_by',
        'prepared_by',
        'delivered_by',
        'completed_by',
        'table_id',
        'quantity',
        'price',
        'remark',
        'status',
        'paid_status',
        'cookie_code',
        'style',
        'ip_address',
        'is_banned',
        'created_at',
        'updated_at',
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
        return $this->belongsTo(User::class);
    }
}
