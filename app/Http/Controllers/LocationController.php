<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{State, District, City, Pincode};



class LocationController extends Controller
{
    public function getStates()
    {
        return State::select('id', 'name')->orderBy('name')->get();
    }

    public function getDistricts($stateId)
    {
        return District::where('state_id', $stateId)->select('id', 'name')->orderBy('name')->get();
    }

    public function getCities($districtId)
    {
        return City::where('district_id', $districtId)->select('id', 'name')->orderBy('name')->get();
    }

    public function getPincodes($cityId)
    {
        return Pincode::whereHas('cities', fn($q) => $q->where('cities.id', $cityId))
            ->select('id', 'code')->orderBy('code')->get();
    }

    public function searchPincode(Request $request)
    {
        $query = $request->get('q', '');
        return Pincode::where('code', 'LIKE', "%{$query}%")
            ->select('id', 'code')
            ->take(10)
            ->get();
    }

    public function getPincodeDetails($pincodeId)
    {
        $pincode = Pincode::with(['district.state', 'cities'])->findOrFail($pincodeId);

        $cities = $pincode->cities->map(fn($city) => [
            'id' => $city->id,
            'name' => $city->name,
            'district' => $city->district->name,
            'district_id' => $city->district->id,
            'state' => $city->district->state->name,
            'state_id' => $city->district->state->id,
        ]);

        return response()->json([
            'district' => $pincode->district->name ?? null,
            'district_id' => $pincode->district->id ?? null,
            'state' => $pincode->district->state->name ?? null,
            'state_id' => $pincode->district->state->id ?? null,
            'cities' => $cities,
        ]);
    }

    //search
    public function searchDistricts(Request $request)
    {
        $stateId = $request->get('state_id');
        $query = $request->get('q');

        $districts = District::where('state_id', $stateId)
            ->where('name', 'LIKE', "%$query%")
            ->limit(20)
            ->get(['id', 'name']);
            
        logger($districts);
        return response()->json($districts);
    }

    public function searchCities(Request $request)
    {
        $districtId = $request->get('district_id');
        $query = $request->get('q');

        $cities = City::where('district_id', $districtId)
            ->where('name', 'LIKE', "%$query%")
            ->limit(20)
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function searchPincodes(Request $request)
    {
        $cityId = $request->get('district_id');
        $query = $request->get('q');

        $pincodes = Pincode::where('district_id', $cityId)
            ->where('code', 'LIKE', "%$query%")
            ->limit(20)
            ->get(['id', 'code']);

        return response()->json($pincodes);
    }
}