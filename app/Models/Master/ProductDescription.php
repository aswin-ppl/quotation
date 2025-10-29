<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'key', 'value'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
