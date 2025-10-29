<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'size_mm', 'r_units', 'qty', 'product_price'];

    public function descriptions() {
        return $this->hasMany(ProductDescription::class);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                $product->descriptions()->forceDelete();
            } else {
                $product->descriptions()->delete();
            }
        });

        static::restoring(function ($product) {
            $product->descriptions()->restore();
        });
    }
}
