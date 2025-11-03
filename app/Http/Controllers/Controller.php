<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function generateQuotation()
    // {
    //     // Get cart data from localStorage via frontend and send via AJAX as JSON
    //     $cartData = json_decode(request()->input('cartData'), true);
    //     $toAddress = request()->input('toAddress');

    //     $pdf = Pdf::loadView('pdf.quotation', [
    //         'cartData' => $cartData,
    //         'toAddress' => $toAddress,
    //     ])->setPaper('a4', 'portrait');

    //     return $pdf->download('quotation.pdf');
    // }
}
