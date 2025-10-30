<?php

namespace App\Http\Controllers\Master;

use DB;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\ProductDescription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withTrashed()->with('descriptions')->get();
        return view('master.products.index', compact('products'));
    }

    public function create()
    {
        return view('master.products.create');
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Store in storage/app/public/images/product/
                $path = $image->storeAs('images/product', $imageName, 'public');

                // Save just the path (without 'public/' prefix)
                $imagePath = $path;
            }


            // Step 2: create product
            $product = Product::create([
                'name' => $request->name,
                'image' => $imagePath,
                'size_mm' => $request->size_mm,
                'r_units' => $request->r_units,
                'product_price' => $request->product_price,
            ]);

            // Step 3: create related descriptions
            foreach ($request->descriptions as $desc) {
                $product->descriptions()->create([
                    'key' => $desc['key'],
                    'value' => $desc['value'],
                ]);
            }
        });

        return redirect()->route('products.create')
            ->with('success', 'Product created successfully');
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'size_mm' => 'nullable|string|max:255',
    //         'r_units' => 'nullable|integer|min:0',
    //         'product_price' => 'nullable|numeric|min:0',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //         'descriptions' => 'nullable|array',
    //         'descriptions.*.key' => 'nullable|string|max:255',
    //         'descriptions.*.value' => 'nullable|string|max:255',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($request) {
    //             //  Handle image upload
    //             $imagePath = $request->hasFile('image')
    //                 ? $request->file('image')->storeAs(
    //                     'images/product',
    //                     time() . '.' . $request->file('image')->getClientOriginalExtension(),
    //                     'public'
    //                 )
    //                 : null;

    //             // ✅ Create Product
    //             $product = Product::create([
    //                 'name' => $request->name,
    //                 'image' => $imagePath,
    //                 'size_mm' => $request->size_mm,
    //                 'r_units' => $request->r_units ?? 0,
    //                 'product_price' => $request->product_price ?? 0,
    //             ]);

    //             // ✅ Create Descriptions
    //             if ($request->filled('descriptions')) {
    //                 foreach ($request->descriptions as $desc) {
    //                     if (!empty($desc['key']) || !empty($desc['value'])) {
    //                         $product->descriptions()->create($desc);
    //                     }
    //                 }
    //             }
    //         });

    //         return redirect()->route('products.index')
    //             ->with('success', 'Product created successfully!');
    //     } catch (\Throwable $e) {
    //         Log::error('Product creation failed: ' . $e->getMessage());
    //         return back()->with('error', 'Failed to create product 😬 Try again.');
    //     }
    // }


    public function show(Product $product)
    {
        return view('master.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('descriptions');
        return view('master.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            // Step 1: handle new image upload (if provided)
            $imagePath = $product->image; // keep the old one by default

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                // Store the new image
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Store using the public disk
                $imagePath = $image->storeAs('images/product', $imageName, 'public');
                // This returns 'images/product/1761740072.jpeg'
            }

            // Step 2: update product info
            $product->update([
                'name' => $request->name,
                'image' => $imagePath,
                'size_mm' => $request->size_mm,
                'r_units' => $request->r_units,
                'product_price' => $request->product_price,
            ]);

            // Step 3: refresh descriptions
            $product->descriptions()->delete();

            foreach ($request->descriptions as $desc) {
                $product->descriptions()->create([
                    'key' => $desc['key'],
                    'value' => $desc['value'],
                ]);
            }
        });


        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }


    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return back()->with('success', 'Product deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error($e);
            return back()->with('error', 'Failed to delete product.');
        }
    }

    // Your restore method should look like this:
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore(); // This should trigger the restoring event

        return back()->with('success', 'Product restored successfully.');
    }

}
