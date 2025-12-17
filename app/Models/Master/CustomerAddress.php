<?php

namespace App\Models\Master;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\District;
use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'address_line_1',
        'address_line_2',
        'city_id',
        'district_id',
        'state_id',
        'pincode_id',
        'country',
        'type',
        'is_default',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}