<?php

namespace App\Http\Controllers\Master;

use DB;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        return view('master.products.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create-products');

        DB::transaction(function () use ($request) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Store in storage
                $path = $image->storeAs('images/product', $imageName, 'public');

                // Save just the path
                $imagePath = $path;
            }

            $product = Product::create([
                'name' => $request->name,
                'image' => $imagePath,
                'size_mm' => $request->size_mm,
                'r_units' => $request->r_units,
                'product_price' => $request->product_price,
            ]);

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

    public function show(Product $product)
    {
        $this->authorize('view-products');
        return view('master.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('edit-products');

        $product->load('descriptions');
        return view('master.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update-products');

        DB::transaction(function () use ($request, $product) {

            $imagePath = $product->image; // keep the old one by default

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                // Store the new image
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $imagePath = $image->storeAs('images/product', $imageName, 'public');
            }

            $product->update([
                'name' => $request->name,
                'image' => $imagePath,
                'size_mm' => $request->size_mm,
                'r_units' => $request->r_units,
                'product_price' => $request->product_price,
            ]);

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
        $this->authorize('delete-products');

        try {
            $product->delete();
            return back()->with('success', 'Product deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error($e);
            return back()->with('error', 'Failed to delete product.');
        }
    }

    public function restore($id)
    {
        $this->authorize('restore-products');

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return back()->with('success', 'Product restored successfully.');
    }

}
