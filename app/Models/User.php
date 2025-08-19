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
        'role',
        'is_first_user',
        'invited_by',
        'invitation_token',
        'invitation_expires_at',
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
        'email_verified_at' => 'datetime',
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'invitation_expires_at' => 'datetime',
        'is_onboarded' => 'boolean',
        'is_first_user' => 'boolean',
    ];

    /**
     * Validation rules for the role field.
     */
    public static function roleRules(): array
    {
        return [
            'role' => 'required|in:admin,restaurant,waiter,cashier,cook',
        ];
    }
    /**
     * Relationship: The user who invited this user.
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Relationship: Users invited by this user.
     */
    public function invitedUsers()
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    /**
     * Query scopes for each role.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
    public function scopeRestaurant($query)
    {
        return $query->where('role', 'restaurant');
    }
    public function scopeWaiters($query)
    {
        return $query->where('role', 'waiter');
    }
    public function scopeCashiers($query)
    {
        return $query->where('role', 'cashier');
    }
    public function scopeCooks($query)
    {
        return $query->where('role', 'cook');
    }

    /**
     * Role check methods.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isRestaurant(): bool
    {
        return $this->role === 'restaurant';
    }
    public function isWaiter(): bool
    {
        return $this->role === 'waiter';
    }
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }
    public function isCook(): bool
    {
        return $this->role === 'cook';
    }

    /**
     * Make this user the first admin user.
     */
    public function makeFirstUserAdmin(): void
    {
        $this->role = 'admin';
        $this->is_first_user = true;
        $this->save();
    }

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

    // ...existing code...
}
