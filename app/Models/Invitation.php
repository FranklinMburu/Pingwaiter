<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'role', 'token', 'status', 'inviter_id', 'expires_at', 'accepted_at', 'revoked_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'revoked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRevoked()
    {
        return $this->status === 'revoked';
    }
}
