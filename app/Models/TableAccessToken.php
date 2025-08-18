<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableAccessToken extends Model
{
    protected $fillable = ['table_id', 'token', 'expires_at','first_scan'];

    protected $casts = [
        'expires_at' => 'datetime',
        'first_scan' => 'boolean'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function isExpired()
    {
        return $this->first_scan && $this->expires_at && $this->expires_at < now();
    }

    protected static function booted()
    {
        static::creating(function ($token) {
            // Find and delete expired tokens before creating a new one
            static::where('table_id', $token->table_id)
                ->get()
                ->filter
                ->isExpired()
                ->each
                ->delete();
        });
    }
}