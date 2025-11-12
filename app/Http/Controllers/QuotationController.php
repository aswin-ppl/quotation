<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\Master\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\DownloadedQuotation;
use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->latest()->get();
        return view('quotation.view', compact('customers'));
    }


    public function generatePdf($id, $defaultAddress)
    {
        $quotation = Quotation::with(['items.product', 'customer.addresses'])
            ->findOrFail($id);
        $settings = Setting::getCompanyDetails();
        $pdf = Pdf::loadView('pdf.quotation', compact('quotation', 'settings', 'defaultAddress'));

        return view('pdf.quotation', compact('quotation', 'settings', 'defaultAddress'));

        // return $pdf->download('quotation_' . $quotation->id . '.pdf');
    }

    public function download($id, $defaultAddress)
    {
        try {
            $quotation = Quotation::with(['items.product', 'customer.addresses'])
                ->findOrFail($id);

            $settings = Setting::getCompanyDetails();
            $pdf = Pdf::loadView('pdf.quotation', compact('quotation', 'settings', 'defaultAddress'));

            // Record download details
            $download = DownloadedQuotation::firstOrNew([
                'quotation_id' => $quotation->id,
                'downloaded_by' => auth()->id(),
            ]);

            // If the record is new, start count at 1
            if (!$download->exists) {
                $download->download_count = 1;
            } else {
                $download->download_count += 1;
            }

            $download->download_ip = request()->ip();
            $download->downloaded_at = now();
            $download->file_format = 'pdf';
            $download->save();



            return $pdf->download('Quotation_' . $quotation->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF.');
        }
    }


    public function preview($id, $defaultAddress)
    {
        $quotation = Quotation::with(['items.product', 'customer.addresses'])
            ->findOrFail($id);
        $settings = Setting::getCompanyDetails();
        $pdf = Pdf::loadView('pdf.quotation', compact('quotation', 'settings', 'defaultAddress'));

        return view('pdf.quotation', compact('quotation', 'settings', 'defaultAddress'));
    }

    public function store(Request $request)
    {
        logger($request);
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer',
            'items.*.product_name' => 'required|string',
            'items.*.description' => 'nullable|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subTotal = collect($validated['items'])->sum('total');
            $discount = $request->input('discount', 0);
            $tax = $request->input('tax', 0);
            $grandTotal = $subTotal - $discount + $tax;

            $quotation = Quotation::create([
                'date' => now(),
                'expiry' => now()->addDays(30),
                'customer_id' => $validated['customer_id'],
                'sub_total' => $subTotal,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'status' => 'draft',
                'notes' => $request->input('notes'),
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'description' => json_encode($item['description'] ?? []),
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $quotation->id,
                'redirect' => route('quotation.pdf', [$quotation->id, $request->defaultAddress]),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
