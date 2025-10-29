<?php

namespace App\Http\Controllers\Master;

use DB;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\ProductDescription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('descriptions')->get();
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
                $imagePath = $path; // This will be 'images/product/1761740072.jpeg'
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

        return response()->json(['status' => true, 'message' => 'Product saved successfully!']);
    }


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

        return response()->json(['status' => true, 'message' => 'Product updated successfully!']);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['status' => true, 'message' => 'Product deleted successfully!']);
    }
}
