<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'preferences',
        'ban_reason',
        'banned_at',
    ];

    protected $casts = [
        'preferences' => 'array',
        'banned_at'   => 'datetime',
    ];
    public function getPhoneAttribute($value)
    {
        // Remove spaces, dashes, parentheses, and ensure + prefix if missing
        $normalized = preg_replace('/[\s\-\(\)]/', '', $value);
        if ($normalized && $normalized[0] !== '+') {
            $normalized = '+' . ltrim($normalized, '0');
        }
        return $normalized;
    }

    public function setPhoneAttribute($value)
    {
        $normalized = preg_replace('/[\s\-\(\)]/', '', $value);
        if ($normalized && $normalized[0] !== '+') {
            $normalized = '+' . ltrim($normalized, '0');
        }
        $this->attributes['phone'] = $normalized;
    }


    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('banned_at');
    }

    // Methods
    public function ban(string $reason = null): void
    {
        $this->ban_reason = $reason;
        $this->banned_at = Carbon::now();
        $this->save();
    }

    public function unban(): void
    {
        $this->ban_reason = null;
        $this->banned_at = null;
        $this->save();
    }

    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    // Validation rules
    public static function rules(): array
    {
        return [
            'user_id'     => 'required|exists:users,id',
            'phone'       => [
                'required',
                'string',
                'regex:/^\+?[0-9]{7,15}$/', // E.164 format, 7-15 digits
            ],
            'preferences' => 'nullable|array',
            'ban_reason'  => 'nullable|string|max:255',
        ];
    }
}
