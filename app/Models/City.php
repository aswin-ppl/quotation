<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'district_id', 'pincode_id', 'is_active'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}
