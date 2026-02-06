<?php

namespace App\Http\Controllers\Master;

use DB;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\Master\ProductImage;
use App\Models\Master\Customer;
use App\Models\Quotation;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('view-products');

        $products = Product::withTrashed()->with('descriptions')->get();
        return view('master.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create-products');
        $customers = Customer::with('addresses')->latest()->get();
        return view('master.products.create', compact('customers'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create-products');

        // Validation is automatically handled by StoreProductRequest
        $validated = $request->validated();

        // Log incoming data for debugging
        Log::info('Product store request received', [
            'customer' => $validated['customer'],
            'products_count' => count($validated['products']),
        ]);

        // Get customer and address from the form (common for all products)
        $customerId = $validated['customer'];
        $addressId = $validated['addressSelect'] ?? null;
        $discount = $validated['discount'] ?? null;
        $remarks = $validated['remarks'] ?? null;
        
        $quotationId = null;

        DB::transaction(function () use ($validated, $customerId, $addressId, $discount, $remarks, &$quotationId) {
            $quotation = Quotation::create([
                'discount' => $discount,
                'remarks' => $remarks
            ]);
            $quotationId = $quotation->id;

            Log::info('Quotation created', ['id' => $quotation->id, 'number' => $quotation->quotation_number]);

            // Get the products array from the validated data
            $productsData = $validated['products'];

            foreach ($productsData as $productId => $productData) {
                $product = Product::create([
                    'name' => $productData['name'] ?? null,
                    'size_mm' => $productData['size_mm'] ?? null,
                    'cost_per_units' => $productData['cost_per_units'] ?? 0,
                    'quantity' => $productData['quantity'] ?? 0,
                    'product_price' => $productData['product_price'] ?? 0,
                    'customer_id' => $customerId,
                    'address_id' => $addressId,
                    'quotation_id' => $quotation->id,
                ]);

                if (isset($productData['descriptions']) && is_array($productData['descriptions'])) {
                    foreach ($productData['descriptions'] as $desc) {
                        $product->descriptions()->create([
                            'key' => $desc['key'] ?? '',
                            'value' => $desc['value'] ?? '',
                        ]);
                    }
                }

                if (isset($productData['autocad_uploaded_name'])) {
                    $storedName = $productData['autocad_uploaded_name'];
                    $originalName = $productData['autocad_original_name'] ?? $storedName;
                    
                    // Move from temp to permanent storage
                    $tempPath = 'temp/' . $storedName;
                    $permPath = 'products/' . $product->id . '/cad/' . $storedName;

                    if (Storage::exists($tempPath)) {
                        Storage::move($tempPath, $permPath);
                    }

                    // Save image record to DB
                    $product->images()->create([
                        'type' => 'cad',
                        'original_name' => $originalName,
                        'path' => $permPath,
                    ]);
                }

                if (isset($productData['extra_uploaded_names']) && is_array($productData['extra_uploaded_names'])) {
                    $extraNames = $productData['extra_uploaded_names'];
                    $extraOriginalNames = $productData['extra_original_names'] ?? [];

                    foreach ($extraNames as $idx => $storedName) {
                        $originalName = $extraOriginalNames[$idx] ?? $storedName;
                        
                        // Move from temp to permanent storage
                        $tempPath = 'temp/' . $storedName;
                        $permPath = 'products/' . $product->id . '/extras/' . $storedName;

                        if (Storage::exists($tempPath)) {
                            Storage::move($tempPath, $permPath);
                        }

                        // Save image record to DB
                        $product->images()->create([
                            'type' => 'extras',
                            'original_name' => $originalName,
                            'path' => $permPath,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('quotations.show', $quotationId)
            ->with('success', 'Quotation created successfully with all products!');
    }

    public function show(Product $product)
    {
        $this->authorize('view-products');
        return view('master.products.show', compact('product'));
    }

    // public function destroy(Product $product)
    // {
    //     $this->authorize('delete-products');

    //     try {
    //         $product->delete();
    //         return back()->with('success', 'Product deleted successfully.');
    //     } catch (\Throwable $e) {
    //         \Log::error($e);
    //         return back()->with('error', 'Failed to delete product.');
    //     }
    // }

    // public function restore($id)
    // {
    //     $this->authorize('restore-products');

    //     $product = Product::onlyTrashed()->findOrFail($id);
    //     $product->restore();

    //     return back()->with('success', 'Product restored successfully.');
    // }

    public function getCartProducts(Request $request)
    {
        $ids = $request->query('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([]);
        }

        $products = Product::with('descriptions')
            ->whereIn('id', $ids)
            ->get();

        return response()->json($products);
    }
}
