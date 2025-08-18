<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Restaurant extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'restaurants';

    protected $casts = [
        'picture' => 'array',
    ];

    protected $fillable = [
        'name',
        'address',
        'location',
        'phone_number',
        'whatsapp_number',
        'email',
        'owner_name',
        'owner_whatsapp',
        'manager_name',
        'manager_whatsapp',
        'cashier_name',
        'cashier_whatsapp',
        'supervisor_name',
        'supervisor_whatsapp',
        'website',
        'admin_email',
        'description',
        'logo',
        'picture',
        'video',
        'allow_call_owner',
        'allow_call_manager',
        'allow_call_cashier',
        'allow_call_supervisor',
        'allow_place_order',

        'currency_symbol',
        'rewards_per_dollar',
        'google_maps_link',
        'google_review_link',
        'instagram_link',
        'facebook_link',
        'twitter_link',
        'opening_hours',
        'notify_customer',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->hasMany(FoodCategory::class);
    }

    public function getCurrencyAttribute(): string
    {
        $code = $this->currency_symbol;

        if (class_exists(\Akaunting\Money\Currency::class)) {
            $currencies = \Akaunting\Money\Currency::getCurrencies();

            return $currencies[$code]['symbol'] ?? '$';
        }

        return '$';
    }
}
