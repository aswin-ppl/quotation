<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Customer;
use App\Models\Master\CustomerAddress;
use App\Http\Controllers\Controller;


class CustomerAddressController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'state'          => 'nullable|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:100',
            'type'           => 'in:home,work,billing,shipping',
        ]);

        $address = $customer->addresses()->create($validated);
        logger('ddrss');
        return redirect()->back()->with('success', 'Address added successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'state'          => 'nullable|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:100',
            'type'           => 'in:home,work,billing,shipping',
        ]);

        $address = $customer->addresses()->update($validated);

        return redirect()->back()->with('success', 'Address Updated successfully.');
    }

    public function destroy(CustomerAddress $address)
    {
        $address->delete();
        return redirect()->back()->with('success', 'Address deleted successfully.');
    }

    public function setDefault(CustomerAddress $address)
    {
        // Unset all others
        CustomerAddress::where('customer_id', $address->customer_id)
            ->update(['is_default' => false]);

        // Set this one
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Default address updated.');
    }
}
