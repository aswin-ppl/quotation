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
}