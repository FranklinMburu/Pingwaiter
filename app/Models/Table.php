<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $fillable = [
        'table_number',
        'qr_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function rules($id = null)
    {
        return [
            'table_number' => 'required|string|max:255|unique:tables,table_number,' . ($id ?? 'NULL') . ',id',
            'qr_code' => 'required|string',
            'is_active' => 'boolean',
        ];
    }

    public function generateQrCode($content)
    {
        return app(\App\Services\TableQrCodeService::class)->generate($content);
    }
}
