<?php

namespace App\Models;

use App\Models\Master\Product;
use App\Models\Master\Customer;
use App\Models\DownloadedQuotation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_number',
        'product_id',
        'user_id',
        'discount',
        'remarks'
    ];

    /**
     * Boot the model - auto-generate quotation number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->quotation_number) {
                $model->quotation_number = static::generateQuotationNumber();
            }
        });

        static::creating(function ($quotation) {
            $quotation->user_id = $quotation->user_id ?? auth()->id();
        });
    }

    /**
     * Generate unique quotation number in format QT-YYYY-0001
     */
    public static function generateQuotationNumber()
    {
        $year = now()->year;
        $prefix = 'QT-' . $year . '-';

        // Get the latest quotation number for this year
        $latestQuotation = static::where('quotation_number', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(quotation_number, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if ($latestQuotation) {
            // Extract number and increment
            $number = (int) substr($latestQuotation->quotation_number, strlen($prefix));
            $nextNumber = $number + 1;
        } else {
            // First quotation of the year
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the products associated with this quotation
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function downloads()
    {
        return $this->hasMany(DownloadedQuotation::class);
    }
    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            Product::class,
            'quotation_id',  // products.quotation_id
            'id',            // customers.id
            'id',            // quotations.id
            'customer_id'    // products.customer_id
        );
    }

    public function getCustomerAttribute()
    {
        // return $this->product?->customer;
        return $this->products->map(fn($p) => $p->customer)->unique('id');
    }

    // /**
    //  * Get customer from first product (all products share same customer)
    //  */
    // public function customer()
    // {
    //     return $this->products()->first()?->customer();
    // }

    /**
     * Get address from first product (all products share same address)
     */
    public function address()
    {
        return $this->products()->first()?->address();
    }
}