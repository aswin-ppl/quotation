<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\Setting;
use App\Models\District;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (!empty($settings['company_state'])) {
            $state = State::find($settings['company_state']);
            $settings['company_state_name'] = $state?->name;
        }

        if (!empty($settings['company_district'])) {
            $district = District::find($settings['company_district']);
            $settings['company_district_name'] = $district?->name;
        }

        if (!empty($settings['company_city'])) {
            $city = City::find($settings['company_city']);
            $settings['company_city_name'] = $city?->name;
        }

        if (!empty($settings['company_pincode'])) {
            $pincode = Pincode::find($settings['company_pincode']);
            $settings['company_pincode_value'] = $pincode?->code;
        }
        return view('settings.create', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_mobile' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_pincode' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:255',
            'company_district' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['company_name', 'company_mobile', 'company_email', 'company_pincode', 'company_city', 'company_district', 'company_state', 'company_address']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    // public function getCompanyDetails()
    // {
    //     $company = Setting::with(['pincode', 'city', 'district', 'state'])->first();

    //     return response()->json([
    //         'company_name' => $company->company_name,
    //         'company_mobile' => $company->company_mobile,
    //         'company_email' => $company->company_email,
    //         'company_address' => $company->company_address,
    //         'company_pincode' => $company->pincode->name ?? null,
    //         'company_city' => $company->city->name ?? null,
    //         'company_district' => $company->district->name ?? null,
    //         'company_state' => $company->state->name ?? null,
    //     ]);
    // }
}