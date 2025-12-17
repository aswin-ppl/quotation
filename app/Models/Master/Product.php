<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ProductDescription;
use App\Models\Master\ProductImage;
use App\Models\Master\Customer;
use App\Models\Master\CustomerAddress;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image', 'size_mm', 'cost_per_units', 'quantity', 'product_price', 'customer_id', 'address_id', 'quotation_id'];

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Add this helper method
    public function descriptionsWithTrashed()
    {
        return $this->hasMany(ProductDescription::class)->withTrashed();
    }
}