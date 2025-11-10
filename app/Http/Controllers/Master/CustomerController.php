<?php

namespace App\Http\Controllers\Master;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\District;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->latest()->get();
        return view('master.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('master.customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Create customer
            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'status' => $validated['status'],
            ]);

            $customer->addresses()->create([
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'],
                'state_id' => $validated['state_id'],
                'pincode_id' => $validated['pincode_id'],
                'country' => 'India',
                'type' => 'home',
                'is_default' => true,
            ]);

            DB::commit();

            return redirect()->route('customers.create')
                ->with('success', 'Customer created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            logger('Customer store failed:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function edit(Customer $customer)
    {
        $address = $customer->addresses()->where('is_default', true)->first();

        $state = null;
        $district = null;
        $city = null;
        $pincode = null;

        if ($address) {
            $state = State::find($address->state_id);
            $district = District::find($address->district_id);
            $city = City::find($address->city_id);
            $pincode = Pincode::find($address->pincode_id);
        }

        return view('master.customer.edit', compact('customer', 'address', 'state', 'district', 'city', 'pincode'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'mobile' => $validated['mobile'],
                'status' => $validated['status'],
            ]);

            $address = $customer->defaultAddress()->firstOrCreate(['is_default' => true]);

            $address->update([
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'],
                'state_id' => $validated['state_id'],
                'pincode_id' => $validated['pincode_id'],
                'country' => $validated['country'] ?? 'Unknown',
            ]);

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger('Customer update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function getAddresses($id)
    {
        $customer = Customer::with(['addresses.city', 'addresses.district', 'addresses.state'])->findOrFail($id);

        $addresses = $customer->addresses->map(function ($address) {

            return [
                'id' => $address->id,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city->name ?? null,
                'district' => $address->district->name ?? null,
                'state' => $address->state->name ?? null,
                'pincode' => $address->pincode->code,
                'country' => $address->country,
            ];
        });

        return response()->json($addresses);
    }
}