<?php

namespace App\Models;

use App\Enums\WorkerDesignation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $casts = [
        'designation' => WorkerDesignation::class
    ];

    protected $table = 'workers';

    protected $fillable = ['name', 'email', 'designation', 'restaurant_id'];
}
