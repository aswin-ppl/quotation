<?php

namespace App\Http\Controllers\Master;

use App\Models\City;
use App\Models\State;
use App\Models\Pincode;
use App\Models\District;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    /**
     * Restore a soft-deleted customer
     */
    public function restore($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        if ($customer->trashed()) {
            $customer->restore();
            return redirect()->route('customers.index')->with('success', 'Customer restored successfully.');
        }

        return redirect()->route('customers.index')->with('info', 'Customer is not deleted.');
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

    /**
     * Return customers as JSON for DataTables AJAX loading
     */
    public function data()
    {
        try {
            // eager load addresses and their city relation to avoid N+1 and allow safe access
            $customers = Customer::withTrashed()->with(['addresses.city'])->get();

            $data = $customers->map(function ($c) {
                $actionHtml = '';
                $status = '';

                // Determine city name from the default address if present, otherwise first address
                $city = null;
                $address = null;
                if ($c->relationLoaded('addresses')) {
                    $address = $c->addresses->where('is_default', true)->first() ?? $c->addresses->first();
                } else {
                    $address = $c->addresses()->where('is_default', true)->first() ?? $c->addresses()->first();
                }

                if ($address && isset($address->city) && $address->city) {
                    $city = $address->city->name;
                }
                if ($c->status) {
                    if ($c->status == 'active') {
                        $status .= '<span class="mb-1 badge text-bg-success">Active</span>';
                    } else {
                        $status .= '<span class="mb-1 badge text-bg-danger">Inactive</span>';
                    }
                }
                if ($c->trashed()) {
                    if (auth()->user()->can('restore-customers')) {
                        $actionHtml .= '<a href="' . route('customers.restore', $c->id) . '" class="btn btn-sm bg-warning-subtle text-warning">'
                            . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">'
                            . '<path fill="currentColor" d="M12.079 2.25c-4.794 0-8.734 3.663-9.118 8.333H2a.75.75 0 0 0-.528 1.283l1.68 1.666a.75.75 0 0 0 1.056 0l1.68-1.666a.75.75 0 0 0-.528-1.283h-.893c.38-3.831 3.638-6.833 7.612-6.833a7.66 7.66 0 0 1 6.537 3.643a.75.75 0 1 0 1.277-.786A9.16 9.16 0 0 0 12.08 2.25m8.761 8.217a.75.75 0 0 0-1.054 0L18.1 12.133a.75.75 0 0 0 .527 1.284h.899c-.382 3.83-3.651 6.833-7.644 6.833a7.7 7.7 0 0 1-6.565-3.644a.75.75 0 1 0-1.277.788a9.2 9.2 0 0 0 7.842 4.356c4.808 0 8.765-3.66 9.15-8.333H22a.75.75 0 0 0 .527-1.284z" />'
                            . '</svg></a>';
                    }
                } else {
                    if (auth()->user()->can('edit-customers')) {
                        $actionHtml .= '<a href="' . route('customers.edit', $c->id) . '" class="btn btn-sm bg-primary-subtle text-primary">'
                            . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">'
                            . '<path fill="currentColor" d="M20.849 8.713a3.932 3.932 0 0 0-5.562-5.561l-.887.887l.038.111a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13z" opacity="0.5" />'
                            . '<path fill="currentColor" d="m14.439 4l-.039.038l.038.112a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13l-8.56 8.56c-.578.577-.867.866-1.185 1.114a6.6 6.6 0 0 1-1.211.748c-.364.174-.751.303-1.526.561l-4.083 1.361a1.06 1.06 0 0 1-1.342-1.341l1.362-4.084c.258-.774.387-1.161.56-1.525q.309-.646.749-1.212c.248-.318.537-.606 1.114-1.183z" />'
                            . '</svg></a>';
                    }
                    if (auth()->user()->can('delete-customers')) {
                        $actionHtml .= '<form action="' . route('customers.destroy', $c->id) . '" method="POST" class="delete-form d-inline" style="display:inline-block;margin:0 2px;">';
                        $actionHtml .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                        $actionHtml .= '<input type="hidden" name="_method" value="DELETE">';
                        $actionHtml .= '<button type="button" class="btn btn-sm bg-danger-subtle text-danger btn-delete">';
                        $actionHtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">';
                        $actionHtml .= '<path fill="currentColor" d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524" />';
                        $actionHtml .= '<path fill="currentColor" fill-rule="evenodd" d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.34 1.181-5.246l.267-4.187c.1-1.577.15-2.366-.303-2.866c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.289-.303 2.866l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886m-1.35-9.811c-.04-.434-.408-.75-.82-.707c-.413.043-.713.43-.672.864l.5 5.263c.04.434.408.75.82.707c.413-.044.713-.43.672-.864zm4.329-.707c.412.043.713.43.671.864l-.5 5.263c-.04.434-.409.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.04-.433.409-.75.82-.707" clip-rule="evenodd" />';
                        $actionHtml .= '</svg></button></form>';
                    }
                }

                return [
                    'id' => $c->id,
                    'name' => e($c->name),
                    'email' => e($c->email),
                    'mobile' => e($c->mobile),
                    'city' => e($city),
                    'status' => $status,
                    'action' => $actionHtml,
                    'trashed' => $c->trashed() ? true : false,
                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            Log::error('Customer data AJAX error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['data' => [], 'error' => 'Failed to load customers'], 500);
        }
    }
}