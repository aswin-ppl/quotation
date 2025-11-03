<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::withTrashed()->with('descriptions')->get();
        return view('dashboard', compact('products'));
    }
}
