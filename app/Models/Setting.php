<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\District;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function get($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set($key, $value, $type = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }


    public static function getCompanyDetails()
    {
        // Get all settings as key => value
        $settings = self::pluck('value', 'key')->toArray();

        // Resolve foreign IDs (city/state/district/pincode) into names
        if (!empty($settings['company_city'])) {
            $settings['company_city_name'] = City::find($settings['company_city'])->name ?? null;
        }

        if (!empty($settings['company_district'])) {
            $settings['company_district_name'] = District::find($settings['company_district'])->name ?? null;
        }

        if (!empty($settings['company_state'])) {
            $settings['company_state_name'] = State::find($settings['company_state'])->name ?? null;
        }

        if (!empty($settings['company_pincode'])) {
            $settings['company_pincode_value'] = Pincode::find($settings['company_pincode'])->code ?? null;
        }

        return $settings;
    }
}
