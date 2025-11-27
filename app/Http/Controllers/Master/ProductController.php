<?php

namespace App\Http\Controllers\Master;

use DB;
use Illuminate\Http\Request;
use App\Models\Master\Product;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
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

    public function store(ProductRequest $request)
    {
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

    public function update(ProductRequest $request, Product $product)
    {
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

    /**
     * Return products as JSON for DataTables AJAX loading
     */
    public function data(Request $request)
    {
    try {
        $this->authorize('view-products');

        $products = Product::withTrashed()->with('descriptions')->get();

        $data = $products->map(function ($p) {
            $imageUrl = $p->image ? asset('storage/' . $p->image) : asset('images/no-image.png');

            $productHtml = '<div class="d-flex align-items-center">'
                . '<img src="' . $imageUrl . '" class="rounded-2" width="42" height="42" alt="' . e($p->name) . '">'
                . '<div class="ms-3">'
                . '<h6 class="fw-semibold mb-1">' . e($p->name) . '</h6>'
                . '<span class="fw-normal">' . e($p->size_mm) . ' mm</span>'
                . '</div></div>';

            $price = '₹ ' . number_format($p->product_price, 2);

            $created_at = $p->created_at->format('d-m-Y');

            $trashed = $p->trashed() ? true : false;

            $actionHtml = '';

            if ($trashed) {
                if (auth()->user()->can('restore-products')) {
                    $actionHtml .= '<a href="' . route('products.restore', $p->id) . '" class="btn btn-sm bg-warning-subtle text-warning">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">'
                        . '<path fill="currentColor" d="M12.079 2.25c-4.794 0-8.734 3.663-9.118 8.333H2a.75.75 0 0 0-.528 1.283l1.68 1.666a.75.75 0 0 0 1.056 0l1.68-1.666a.75.75 0 0 0-.528-1.283h-.893c.38-3.831 3.638-6.833 7.612-6.833a7.66 7.66 0 0 1 6.537 3.643a.75.75 0 1 0 1.277-.786A9.16 9.16 0 0 0 12.08 2.25m8.761 8.217a.75.75 0 0 0-1.054 0L18.1 12.133a.75.75 0 0 0 .527 1.284h.899c-.382 3.83-3.651 6.833-7.644 6.833a7.7 7.7 0 0 1-6.565-3.644a.75.75 0 1 0-1.277.788a9.2 9.2 0 0 0 7.842 4.356c4.808 0 8.765-3.66 9.15-8.333H22a.75.75 0 0 0 .527-1.284z" />'
                        . '</svg></a>';
                }
            } else {
                if (auth()->user()->can('edit-products')) {
                    $actionHtml .= '<a href="' . route('products.edit', $p->id) . '" class="btn btn-sm bg-primary-subtle text-primary">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">'
                        . '<path fill="currentColor" d="M20.849 8.713a3.932 3.932 0 0 0-5.562-5.561l-.887.887l.038.111a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13z" opacity="0.5" />'
                        . '<path fill="currentColor" d="m14.439 4l-.039.038l.038.112a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13l-8.56 8.56c-.578.577-.867.866-1.185 1.114a6.6 6.6 0 0 1-1.211.748c-.364.174-.751.303-1.526.561l-4.083 1.361a1.06 1.06 0 0 1-1.342-1.341l1.362-4.084c.258-.774.387-1.161.56-1.525q.309-.646.749-1.212c.248-.318.537-.606 1.114-1.183z" />'
                        . '</svg></a>';
                }

                if (auth()->user()->can('delete-products')) {
                    $actionHtml .= '<form action="' . route('products.destroy', $p->id) . '" method="POST" class="delete-form d-inline" style="display:inline-block;margin:0 2px;">';
                    $actionHtml .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $actionHtml .= '<input type="hidden" name="_method" value="DELETE">';
                    $actionHtml .= '<button type="button" class="btn btn-sm bg-danger-subtle text-danger btn-delete">';
                    $actionHtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">';
                    $actionHtml .= '<path fill="currentColor" d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524" />';
                    $actionHtml .= '<path fill="currentColor" fill-rule="evenodd" d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.34 1.181-5.246l.267-4.187c.1-1.577.15-2.366-.303-2.866c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.289-.303 2.866l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886m-1.35-9.811c-.04-.434-.408-.75-.82-.707c-.413.043-.713.43-.672.864l.5 5.263c.04.434.408.75.82.707c.413-.044.713-.43.672-.864zm4.329-.707c.412.043.713.43.671.864l-.5 5.263c-.04.434-.409.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.04-.433.409-.75.82-.707" clip-rule="evenodd" />';
                    $actionHtml .= '</svg></button></form>';
                }
            }

            return [
                'id' => $p->id,
                'product' => $productHtml,
                'price' => $price,
                'created_at' => $created_at,
                'action' => $actionHtml,
                'trashed' => $trashed,
            ];
            });

            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            // Log and return a safe JSON error so the client can inspect it
            Log::error('Product data AJAX error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'data' => [],
                'error' => 'Failed to load products'
            ], 500);
        }
    }

}
