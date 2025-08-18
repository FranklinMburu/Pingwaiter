<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\WorkerDesignation;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements BannableContract, HasMedia
{
    use Bannable, Billable, HasApiTokens, HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        // 'role',
        'is_active',
        'restaurant_id',
        'stripe_subscription_id',
        'subscription_amount',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'is_onboarded',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<int, string>
     */
    protected $casts = [
        // 'role' => WorkerDesignation::class,
        'email_verified_at' => 'datetime',
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'is_onboarded' => 'boolean',
    ];

    /**
     * Check if the user has an active subscription.
     * This could be used for checking whether the user has paid their monthly subscription.
     *
     * @return bool
     */
    // public function hasActiveSubscription()
    // {
    //     return $this->subscription_status === 'active' &&
    //         now()->lessThanOrEqualTo($this->subscription_end_date);
    // }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    /**
     * Scope to get users with active subscriptions.
     */
    public function scopeActiveSubscription($query)
    {
        return $query->where('subscription_status', 'active')
            ->where('subscription_end_date', '>=', now());
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tablePings()
    {
        return $this->hasMany(TablePing::class, 'client_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function hasActiveSubscription()
    {
        return $this->subscription && $this->subscription->isActive();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(WorkerDesignation::ADMIN->value);
    }

    public function isRestaurant(): bool
    {
        return $this->hasRole(WorkerDesignation::RESTAURANT->value);
    }

    public function isWaiter(): bool
    {
        return $this->hasRole(WorkerDesignation::WAITER->value);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(WorkerDesignation::CUSTOMER->value);
    }

    public function isCook(): bool
    {
        return $this->hasRole(WorkerDesignation::COOK->value);
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole([
            WorkerDesignation::RESTAURANT->value,
            WorkerDesignation::CASHIER->value,
            WorkerDesignation::COOK->value,
            WorkerDesignation::WAITER->value,
        ]);
    }
}
