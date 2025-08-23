<?php

namespace App\Observers;

use App\Models\Table;

class TableObserver
{
    public function creating(Table $table)
    {
        // Generate QR code URL and store as string
        $table->qr_code = $table->generateQrCode();
    }
}
