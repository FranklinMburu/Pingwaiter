<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'paypal_subscription_id', 'plan_name', 'status', 'ends_at',
    ];

    public function isActive()
    {
        return ! $this->ends_at || $this->ends_at->isFuture();
    }
}
