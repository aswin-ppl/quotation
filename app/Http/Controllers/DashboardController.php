<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\Quotation;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Products: allow search by name and paginate 9 per page
        $productsQuery = Product::withTrashed()->with('descriptions');
        $search = request()->query('q');
        if ($search) {
            $productsQuery->where('name', 'like', '%' . $search . '%');
        }
        $products = $productsQuery->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

        // Quotations summary
        $total = Quotation::count();
        $today = Quotation::whereDate('created_at', Carbon::today())->count();
        $week = Quotation::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $month = Quotation::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();

        $quotationCounts = [
            'total' => $total,
            'today' => $today,
            'week' => $week,
            'month' => $month,
        ];

        return view('dashboard', compact('products', 'quotationCounts'));
    }

    /**
     * Return products partial HTML for AJAX requests (live search)
     */
    public function productsPartial(Request $request)
    {
        try {
            $productsQuery = Product::withTrashed()->with('descriptions');
            $search = $request->query('q');
            if ($search) {
                $productsQuery->where('name', 'like', '%' . $search . '%');
            }

            $products = $productsQuery->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

            $html = view('dashboard._products', compact('products'))->render();

            return response()->json(['html' => $html]);
        } catch (\Throwable $e) {
            Log::error('Products partial load failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['html' => '<div class="text-muted">Failed to load products.</div>'], 500);
        }
    }
}
