<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

class RestaurantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone',
        'email',
        'timezone',
        'is_accepting_orders',
        'closed_message',
        'operating_hours',
    ];

    protected $casts = [
        'is_accepting_orders' => 'boolean',
        'operating_hours' => 'array',
    ];

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'timezone' => 'required|string|max:64',
            'is_accepting_orders' => 'boolean',
            'closed_message' => 'nullable|string',
            'operating_hours' => 'nullable|array',
        ];
    }

    // Helper: Is restaurant accepting orders now?
    public function isOpenNow(): bool
    {
        if (!$this->is_accepting_orders) {
            return false;
        }
        if (!$this->operating_hours || !is_array($this->operating_hours)) {
            return true;
        }
        $now = Carbon::now($this->timezone ?? config('app.timezone'));
        $day = strtolower($now->format('l'));
        $hours = $this->operating_hours[$day] ?? null;
        if (!$hours || !isset($hours['open'], $hours['close'])) {
            return true;
        }
        $open = Carbon::parse($hours['open'], $this->timezone);
        $close = Carbon::parse($hours['close'], $this->timezone);
        return $now->between($open, $close);
    }

    // Helper: Get closed message
    public function getClosedMessage(): string
    {
        return $this->closed_message ?? 'Restaurant is currently closed.';
    }

    // Validate operating_hours structure
    public static function validateOperatingHours($hours): bool
    {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        if (!is_array($hours)) return false;
        foreach ($days as $day) {
            if (!isset($hours[$day]) || !is_array($hours[$day])) return false;
            if (!isset($hours[$day]['open'], $hours[$day]['close'])) return false;
            // Optionally, validate time format
            if (!preg_match('/^\d{2}:\d{2}$/', $hours[$day]['open'])) return false;
            if (!preg_match('/^\d{2}:\d{2}$/', $hours[$day]['close'])) return false;
        }
        return true;
    }

    // Setter for safe update of operating_hours
    public function setOperatingHours(array $hours): bool
    {
        if (self::validateOperatingHours($hours)) {
            $this->operating_hours = $hours;
            $this->save();
            return true;
        }
        return false;
    }

    // Event dispatch on status change
    protected static function booted()
    {
        static::updating(function ($model) {
            if ($model->isDirty('is_accepting_orders')) {
                Event::dispatch('restaurant.status.changed', $model);
            }
            if ($model->isDirty('operating_hours')) {
                Event::dispatch('restaurant.hours.changed', $model);
            }
        });
    }
}
