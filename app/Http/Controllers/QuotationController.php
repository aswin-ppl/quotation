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
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        $this->authorize('create-products');
        $customers = Customer::with('addresses')->latest()->get();
        return view('quotation.create', compact('customers'));
    }

    public function generatePdf($id, $defaultAddress = 1)
    {
        try {
            Log::info('PDF generation started for quotation: ' . $id);

            $quotation = Quotation::with(['products.customer', 'products.address', 'products.descriptions', 'products.images'])
                ->findOrFail($id);
            Log::info('Quotation loaded');
            
            // Debug: Log image counts
            foreach ($quotation->products as $product) {
                $extraCount = $product->images->where('type', 'extras')->count();
                Log::info("Product {$product->id} has {$extraCount} extra images");
            }

            $settings = Setting::getCompanyDetails();
            Log::info('Settings loaded');

            // Prepare images for PDF (convert WebP/PNG to JPG)
            Log::info('Preparing images for PDF...');
            $this->prepareImagesForPdf($quotation);
            Log::info('Images prepared');

            // Load the preview view and generate PDF from it
            Log::info('Rendering view...');
            $html = view('quotation.preview', compact('quotation', 'settings', 'defaultAddress') + ['pdfMode' => true])->render();
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
            $this->prepareImagesForPdf($quotation);

            // Render view to HTML
            $html = view('quotation.preview', [
                'quotation' => $quotation,
                'settings' => $settings,
                'defaultAddress' => $defaultAddress,
                'pdfMode' => true
            ])->render();

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isRemoteEnabled', true)
                ->setOption('defaultFont', 'DejaVu Sans');

            // Generate file name + path
            $fileName = 'Quotation_' . $quotation->quotation_number . '_' . time() . '.pdf';
            $filePath = 'quotations/' . $fileName;
            
            // Ensure directory exists
            $dirPath = storage_path('app/private/quotations');
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            // Save PDF to storage
            Storage::disk('local')->put($filePath, $pdf->output());
            
            // Verify file was saved
            $fullPath = storage_path('app/private/quotations/' . $fileName);
            if (!file_exists($fullPath)) {
                throw new \Exception('PDF file was not saved successfully');
            }

            // Log download
            DownloadedQuotation::create([
                'quotation_id' => $quotation->id,
                'downloaded_by' => auth()->id(),
                'download_count' => 1,
                'download_ip' => request()->ip(),
                'downloaded_at' => now(),
                'file_path' => $filePath,
                'file_format' => 'pdf',
                'remarks' => 'Quotation downloaded'
            ]);

            // Stream PDF for download
            return response()->download(
                $fullPath,
                $fileName,
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            Log::error('PDF download failed: ' . $e->getMessage());
            return back()->with('error', 'PDF download failed: ' . $e->getMessage());
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

                $filename = pathinfo($image->path, PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($image->path, PATHINFO_EXTENSION));

                // Convert WebP to JPG
                if ($extension === 'webp') {
                    $jpgPath = $tempDir . '/' . $filename . '.jpg';

                    if (!file_exists($jpgPath)) {
                        try {
                            \Intervention\Image\Facades\Image::make($rawPath)
                                ->encode('jpg', 85) // Lower quality = faster
                                ->save($jpgPath);

                            Log::info("Converted WebP to JPG: $jpgPath");
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed for $rawPath: " . $e->getMessage());
                        }
                    }
                }
                // Optimize PNG (remove transparency)
                elseif ($extension === 'png') {
                    $jpgPath = $tempDir . '/' . $filename . '.jpg';

                    if (!file_exists($jpgPath)) {
                        try {
                            \Intervention\Image\Facades\Image::make($rawPath)
                                ->encode('jpg', 85)
                                ->save($jpgPath);

                            Log::info("Converted PNG to JPG: $jpgPath");
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed for $rawPath: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    }
}
