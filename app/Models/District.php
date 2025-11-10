<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'state_id', 'is_active'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function pincodes()
    {
        return $this->hasMany(Pincode::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
