<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
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

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->only([
            'company_name',
            'company_mobile',
            'company_email',
            'company_pincode',
            'company_city',
            'company_district',
            'company_state',
            'company_address',
            'bank_name',
            'bank_account_number',
            'bank_ifsc_code',
            'bank_account_name'
        ]);

        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = 'company_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/settings', $filename, 'public');

            // Save logo path in settings
            Setting::set('company_logo', $path);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}