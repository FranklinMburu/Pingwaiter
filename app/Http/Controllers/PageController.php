<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PageController extends Controller
{
    public function index()
    {
        $tableId = 'T001';
        $url = URL::signedRoute('table.menu', ['table' => $tableId]);
        $qrCode = QrCode::size(300)->generate($url);

        return view('pages.index',
            [
                'qrCode' => $qrCode,
                'link' => $url,
                'tableId' => $tableId,
            ]
        );
    }
}
