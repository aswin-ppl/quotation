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

    /**
     * Serve company logo image
     */
    public function getCompanyLogo()
    {
        try {
            $settings = Setting::getCompanyDetails();
            
            if (!$settings || !isset($settings['company_logo']) || !$settings['company_logo']) {
                return response()->noContent(404);
            }

            $logoPath = public_path('storage/' . $settings['company_logo']);
            
            if (!file_exists($logoPath)) {
                return response()->noContent(404);
            }

            $mimeType = mime_content_type($logoPath);
            $imageData = file_get_contents($logoPath);

            return response($imageData)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400');
        } catch (\Exception $e) {
            Log::error('Error serving company logo: ' . $e->getMessage());
            return response()->noContent(500);
        }
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
            $imageMap = $this->prepareImagesForPdf($quotation, $settings);
            Log::info('Images prepared: ' . json_encode($imageMap));

            // Load the preview view and generate PDF from it
            Log::info('Rendering view...');
            $html = view('quotation.preview', compact('quotation', 'settings', 'defaultAddress', 'imageMap') + ['pdfMode' => true])->render();
            Log::info('View rendered, HTML length: ' . strlen($html));

            Log::info('Loading HTML to PDF...');
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', true)
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 15)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 15)
                ->setOption('isRemoteEnabled', true);

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
            $imageMap = $this->prepareImagesForPdf($quotation, $settings);

            // Render view to HTML
            $html = view('quotation.preview', [
                'quotation' => $quotation,
                'settings' => $settings,
                'defaultAddress' => $defaultAddress,
                'imageMap' => $imageMap,
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
        
        // For preview, we don't need imageMap since we're using URLs
        $imageMap = [];

        return view('quotation.preview', [
            'quotation' => $quotation,
            'settings' => $settings,
            'defaultAddress' => $defaultAddress,
            'imageMap' => $imageMap,
            'pdfMode' => false,
        ]);
    }

    /**
     * Pre-convert WebP images to JPG for faster PDF rendering
     * Returns a map of original image paths to their base64 encoded data URLs
     */
    private function prepareImagesForPdf($quotation, $settings = null)
    {
        $tempDir = storage_path('app/temp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $imageMap = []; // Map of original paths to base64 data URLs

        // Process company logo first if available
        if ($settings && isset($settings['company_logo']) && $settings['company_logo']) {
            $logoPath = public_path('storage/' . $settings['company_logo']);
            if (file_exists($logoPath)) {
                try {
                    $imageData = file_get_contents($logoPath);
                    $mimeType = mime_content_type($logoPath);
                    $base64 = base64_encode($imageData);
                    $imageMap['company_logo'] = "data:{$mimeType};base64,{$base64}";
                    Log::info("Encoded company logo to base64 for PDF");
                } catch (\Exception $e) {
                    Log::error("Failed to encode company logo to base64: " . $e->getMessage());
                }
            } else {
                Log::warning("Company logo file not found: $logoPath");
            }
        }

        foreach ($quotation->products as $product) {
            if (!$product->images)
                continue;

            foreach ($product->images as $image) {
                $rawPath = storage_path('app/private/' . $image->path);

                if (!file_exists($rawPath)) {
                    Log::warning("Image not found: $rawPath");
                    $imageMap[$image->path] = null;
                    continue;
                }

                $filename = pathinfo($image->path, PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($image->path, PATHINFO_EXTENSION));
                $finalPath = $rawPath;

                // Convert WebP to JPG
                if ($extension === 'webp') {
                    $jpgPath = $tempDir . '/' . $filename . '.jpg';

                    if (!file_exists($jpgPath)) {
                        try {
                            \Intervention\Image\Facades\Image::make($rawPath)
                                ->encode('jpg', 85)
                                ->save($jpgPath);

                            Log::info("Converted WebP to JPG: $jpgPath");
                            $finalPath = $jpgPath;
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed for $rawPath: " . $e->getMessage());
                        }
                    } else {
                        $finalPath = $jpgPath;
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
                            $finalPath = $jpgPath;
                        } catch (\Exception $e) {
                            Log::error("Image conversion failed for $rawPath: " . $e->getMessage());
                        }
                    } else {
                        $finalPath = $jpgPath;
                    }
                }

                // Convert final image to base64 data URL for PDF
                try {
                    if (file_exists($finalPath)) {
                        $imageData = file_get_contents($finalPath);
                        $mimeType = mime_content_type($finalPath);
                        $base64 = base64_encode($imageData);
                        $imageMap[$image->path] = "data:{$mimeType};base64,{$base64}";
                        Log::info("Encoded image to base64 for PDF: " . $image->path);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to encode image to base64: " . $e->getMessage());
                    $imageMap[$image->path] = null;
                }
            }
        }
        
        return $imageMap;
    }
}
