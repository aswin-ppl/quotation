<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Master\CustomerAddress;


class CustomerAddressController extends Controller
{

    public function store(Request $request)
    {
        if (!$request->has('addresses')) {
            Log::error('❌ No addresses array in request');
            return back()->withInput()->with('error', 'No addresses provided');
        }

        // Validation with logging
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'mobile' => 'required|string|max:15',
                'status' => 'required|in:active,inactive',
                'addresses' => 'required|array|min:1',
                'addresses.*.address_line_1' => 'required|string|max:500',
                'addresses.*.state_id' => 'required|exists:states,id',
                'addresses.*.district_id' => 'required|exists:districts,id',
                'addresses.*.city_id' => 'required|exists:cities,id',
                'addresses.*.pincode_id' => 'required|exists:pincodes,id',
                'addresses.*.type' => 'required|in:home,work,billing,shipping',
                'default_address' => 'required|integer'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        }

        try {
            DB::beginTransaction();
            // Create customer
            $customerData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'status' => $validated['status']
            ];

            $customer = Customer::create($customerData);

            if (!$customer) {
                throw new \Exception('Failed to create customer');
            }

            Log::info('Customer created successfully:', [
                'id' => $customer->id,
                'name' => $customer->name
            ]);

            // Create addresses
            $addressCount = 0;
            foreach ($validated['addresses'] as $index => $addressData) {
                Log::info("Creating address #{$index}:", $addressData);

                $addressToCreate = [
                    'address_line_1' => $addressData['address_line_1'],
                    'address_line_2' => null,
                    'city_id' => $addressData['city_id'],
                    'district_id' => $addressData['district_id'],
                    'state_id' => $addressData['state_id'],
                    'pincode_id' => $addressData['pincode_id'],
                    'type' => $addressData['type'],
                    'country' => 'India',
                    'is_default' => ($index == $validated['default_address'])
                ];

                Log::info("Address data to insert:", $addressToCreate);

                $address = $customer->addresses()->create($addressToCreate);

                if (!$address) {
                    throw new \Exception("Failed to create address at index {$index}");
                }

                $addressCount++;
            }

            DB::commit();

            return redirect()->route('customers.index')
                ->with('success', "Customer created with {$addressCount} address(es)!");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exception caught during customer creation:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()
                ->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'type' => 'in:home,work,billing,shipping',
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
