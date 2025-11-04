<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ProductDescription;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'size_mm', 'r_units', 'qty', 'product_price'];

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    // Add this helper method
    public function descriptionsWithTrashed()
    {
        return $this->hasMany(ProductDescription::class)->withTrashed();
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($product) {
    //         // Always force delete product descriptions
    //         $product->descriptions()->forceDelete();
    //     });
    // }


    // protected static function booted()
    // {
    //     static::deleting(function ($product) {
    //         \Log::info('Deleting product: ' . $product->id);
    //         if ($product->isForceDeleting()) {
    //             \Log::info('Force deleting descriptions');
    //             $product->descriptions()->forceDelete();
    //         } else {
    //             \Log::info('Soft deleting descriptions');
    //             $product->descriptions()->delete();
    //         }
    //     });

    //     static::restoring(function ($product) {
    //         \Log::info('Restoring product: ' . $product->id);
    //         $trashedDescriptions = $product->descriptions()->onlyTrashed()->get();
    //         \Log::info('Found trashed descriptions: ' . $trashedDescriptions->count());

    //         if ($trashedDescriptions->isNotEmpty()) {
    //             foreach ($trashedDescriptions as $desc) {
    //                 \Log::info('Restoring description ID: ' . $desc->id);
    //                 $desc->restore();
    //             }
    //         }
    //     });
    // }
}