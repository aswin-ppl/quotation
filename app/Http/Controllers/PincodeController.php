<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use Illuminate\Http\Request;

class PincodeController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $pincodes = Pincode::where('code', 'LIKE', "%{$query}%")
            ->with('district.state')
            ->take(10)
            ->get();

        return response()->json($pincodes);
    }

    public function details($id)
    {
        $pincode = Pincode::with(['district.state', 'cities'])->findOrFail($id);

        return response()->json([
            'district' => $pincode->district->name ?? null,
            'state' => $pincode->district->state->name ?? null,
            'cities' => $pincode->cities->pluck('name', 'id'),
        ]);
    }
}
