<?php

namespace App\Http\Controllers;

use App\Models\DownloadedQuotation;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\Quotation;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     // Products: allow search by name and paginate 9 per page
    //     $productsQuery = Product::with('descriptions');
    //     $search = request()->query('q');
    //     if ($search) {
    //         $productsQuery->where('name', 'like', '%' . $search . '%');
    //     }
    //     $products = $productsQuery->orderBy('created_at', 'desc')->paginate(9)->withQueryString();

    //     // Quotations summary
    //     // $total = DownloadedQuotation::sum('download_count');
    //     $overallTotal = DownloadedQuotation::sum('download_count');

    //     $total = DownloadedQuotation::where('downloaded_by', auth()->id())
    //         ->sum('download_count');

    //     $today = DownloadedQuotation::where('downloaded_by', auth()->id())->whereDate('created_at', Carbon::today())
    //         ->sum('download_count');

    //     $week = DownloadedQuotation::where('downloaded_by', auth()->id())->whereBetween('created_at', [
    //         Carbon::now()->startOfWeek(),
    //         Carbon::now()->endOfWeek()
    //     ])->sum('download_count');

    //     $month = DownloadedQuotation::where('downloaded_by', auth()->id())->whereYear('created_at', Carbon::now()->year)
    //         ->whereMonth('created_at', Carbon::now()->month)
    //         ->sum('download_count');

    //     $quotationCounts = [
    //         'overall' => $overallTotal,
    //         'total' => $total,
    //         'today' => $today,
    //         'week' => $week,
    //         'month' => $month,
    //     ];

    //     return view('dashboard', compact('products', 'quotationCounts'));
    // }

    public function index()
    {
        $search = request()->query('q');

        // Fetch quotations that were downloaded by the current user
        $quotationsQuery = DownloadedQuotation::with([
            'quotation.products.customer'
        ])->where('downloaded_by', auth()->id());

        // Optional search by quotation number or product name
        if ($search) {
            $quotationsQuery->whereHas('quotation', function ($q) use ($search) {
                $q->where('quotation_number', 'like', '%' . $search . '%');
            })->orWhereHas('quotation.products', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Paginate 9 per page
        $quotations = $quotationsQuery
            ->orderBy('downloaded_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        // The rest of your download counts (unchanged)
        $overallTotal = DownloadedQuotation::sum('download_count');

        $total = DownloadedQuotation::where('downloaded_by', auth()->id())
            ->sum('download_count');

        $today = DownloadedQuotation::where('downloaded_by', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->sum('download_count');

        $week = DownloadedQuotation::where('downloaded_by', auth()->id())
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->sum('download_count');

        $month = DownloadedQuotation::where('downloaded_by', auth()->id())
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('download_count');

        $quotationCounts = [
            'overall' => $overallTotal,
            'total' => $total,
            'today' => $today,
            'week' => $week,
            'month' => $month,
        ];

        return view('dashboard', compact('quotations', 'quotationCounts'));
    }


    public function productsPartial(Request $request)
    {
        try {
            $search = $request->query('q');

            // Base query with eager loading
            $quotationsQuery = DownloadedQuotation::with([
                'quotation.products.customer'
            ])->where('downloaded_by', auth()->id());

            // Apply search on customer name OR quotation number
            if ($search) {
                $quotationsQuery->where(function ($query) use ($search) {

                    // Search by quotation number
                    $query->whereHas('quotation', function ($q) use ($search) {
                        $q->where('quotation_number', 'like', '%' . $search . '%');
                    });

                    // Search by customer name
                    $query->orWhereHas('quotation.products.customer', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                });
            }

            // Pagination
            $quotations = $quotationsQuery
                ->orderBy('downloaded_at', 'desc')
                ->paginate(9)
                ->withQueryString();

            // Render partial
            $html = view('dashboard._quotations', compact('quotations'))->render();

            return response()->json(['html' => $html]);

        } catch (\Throwable $e) {
            Log::error('Quotations partial load failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['html' => '<div class="text-muted">Failed to load quotations.</div>'], 500);
        }
    }

    public function viewQuotation($id)
    {
        $dq = DownloadedQuotation::findOrFail($id);

        $path = storage_path('app/private/' . $dq->file_path);

        if (!file_exists($path)) {
            abort(404, 'PDF not found or expired');
        }

        return response()->file($path);
    }

}
