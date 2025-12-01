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

    public function show($id)
    {
        Log::info('Loading quotation: ' . $id);
        try {
            $quotation = Quotation::with(['products.customer', 'products.address', 'products.descriptions', 'products.images'])
                ->findOrFail($id);
            Log::info('Quotation loaded, returning view');
            return view('quotation.view', compact('quotation'));
        } catch (\Exception $e) {
            Log::error('Error loading quotation: ' . $e->getMessage());
            return back()->with('error', 'Quotation not found.');
        }
    }


    public function generatePdf($id, $defaultAddress = 1)
    {
        try {
            Log::info('PDF generation started for quotation: ' . $id);

            $quotation = Quotation::with(['products.customer', 'products.address', 'products.descriptions', 'products.images'])
                ->findOrFail($id);
            Log::info('Quotation loaded');

            $settings = Setting::getCompanyDetails();
            Log::info('Settings loaded');

            // Load the preview view and generate PDF from it
            Log::info('Rendering view...');
            $html = view('quotation.preview', compact('quotation', 'settings', 'defaultAddress'))->render();
            Log::info('View rendered, HTML length: ' . strlen($html));

            Log::info('Loading HTML to PDF...');
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', true)
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 15)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 15);

            Log::info('Streaming PDF...');
            return $pdf->stream('Quotation_' . $quotation->quotation_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function download($id, $defaultAddress = 1)
    {
        // Increase PHP limits
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        Log::info("PDF download started for quotation: $id");

        try {
            $quotation = Quotation::with([
                'products.customer',
                'products.address.city',
                'products.address.district',
                'products.address.state',
                'products.address.pincode',
                'products.descriptions',
                'products.images'
            ])->findOrFail($id);

            $settings = Setting::getCompanyDetails();

            // Pre-process images before rendering
            $this->prepareImagesForPdf($quotation);

            Log::info('Rendering view for PDF...');
            $html = view('quotation.preview', [
                'quotation' => $quotation,
                'settings' => $settings,
                'defaultAddress' => $defaultAddress,
                'pdfMode' => true
            ])->render();

            Log::info('View rendered, HTML length: ' . strlen($html));

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('enable-local-file-access', true)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', false) // Security + performance
                ->setOption('isRemoteEnabled', false)
                ->setOption('chroot', [storage_path('app')])
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 15)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 15);

            // Record download
            $this->recordDownload($quotation->id);

            Log::info('Streaming PDF...');
            return $pdf->stream('Quotation_' . $quotation->quotation_number . '.pdf');

        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'PDF generation failed. Please try again or contact support.');
        }
    }
    public function preview($id, $defaultAddress = 1)
    {
        $quotation = Quotation::with([
            'products.customer',
            'products.address.city',
            'products.address.district',
            'products.address.state',
            'products.address.pincode',
            'products.descriptions',
            'products.images'
        ])->findOrFail($id);

        $settings = Setting::getCompanyDetails();

        return view('quotation.preview', [
            'quotation' => $quotation,
            'settings' => $settings,
            'defaultAddress' => $defaultAddress,
            'pdfMode' => false,
        ]);
    }

    /**
     * Pre-convert WebP images to JPG for faster PDF rendering
     */
    private function prepareImagesForPdf($quotation)
    {
        $tempDir = storage_path('app/temp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        foreach ($quotation->products as $product) {
            if (!$product->images)
                continue;

            foreach ($product->images as $image) {
                $rawPath = storage_path('app/private/' . $image->path);

                if (!file_exists($rawPath)) {
                    Log::warning("Image not found: $rawPath");
                    continue;
                }

                // Convert WebP to JPG
                if (str_ends_with(strtolower($rawPath), '.webp')) {
                    $jpgPath = $tempDir . '/' . basename($image->path, '.webp') . '.jpg';

                    if (!file_exists($jpgPath)) {
                        try {
                            \Intervention\Image\Facades\Image::make($rawPath)
                                ->encode('jpg', 85) // Lower quality = faster
                                ->save($jpgPath);

                            Log::info("Converted WebP to JPG: $jpgPath");
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed: " . $e->getMessage());
                        }
                    }
                }

                // Optimize PNG (remove transparency)
                if (str_ends_with(strtolower($rawPath), '.png')) {
                    $jpgPath = $tempDir . '/' . basename($image->path, '.png') . '.jpg';

                    if (!file_exists($jpgPath)) {
                        try {
                            \Intervention\Image\Facades\Image::make($rawPath)
                                ->encode('jpg', 85)
                                ->save($jpgPath);

                            Log::info("Converted PNG to JPG: $jpgPath");
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    }

    /**
     * Record download statistics
     */
    private function recordDownload($quotationId)
    {
        try {
            $download = DownloadedQuotation::firstOrNew([
                'quotation_id' => $quotationId,
                'downloaded_by' => auth()->id(),
            ]);

            $download->download_count = ($download->download_count ?? 0) + 1;
            $download->download_ip = request()->ip();
            $download->downloaded_at = now();
            $download->file_format = 'pdf';
            $download->save();
        } catch (\Exception $e) {
            Log::error('Failed to record download: ' . $e->getMessage());
        }
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

            // Create a single quotation for all items with auto-generated quotation_number
            $quotation = Quotation::create([
                'quotation_number' => Quotation::generateQuotationNumber(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $quotation->id,
                'redirect' => route('quotation.show', $quotation->id),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
