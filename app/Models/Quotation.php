<?php

namespace App\Models;

use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'expiry',
        'customer_id',
        'sub_total',
        'discount',
        'tax',
        'grand_total',
        'status',
        'notes',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}