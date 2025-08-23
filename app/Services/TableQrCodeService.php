<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Generator;

class TableQrCodeService
{
    protected $qr;

    public function __construct()
    {
        $this->qr = new Generator;
    }

    public function generate($content, $size = 200, $format = 'png')
    {
        return $this->qr->format($format)->size($size)->generate($content);
    }
}
