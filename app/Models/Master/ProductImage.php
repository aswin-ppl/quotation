<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'type', 'original_name', 'path'];

    protected $casts = [
        'type' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
