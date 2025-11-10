<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pincode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'district_id', 'is_active'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
