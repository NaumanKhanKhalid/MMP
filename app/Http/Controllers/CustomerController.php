<?php

namespace App\Http\Controllers;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\CustomerVehicle;
use App\Models\Engine;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::query();

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (request('status')) {
            $query->where('customer_status', request('status'));
        }

        // Filter by terms
        if (request('type')) {
            $query->where('terms', request('type'));
        }

        // Filter by category
        if (request('category')) {
            $query->where('customer_category', request('category'));
        }

        $customers = $query->orderByDesc('id')->paginate(request('per_page', 10));

        // AJAX Request
        if (request()->ajax() || request('ajax')) {
            $tableHtml = view('customers.partials.table', compact('customers'))->render();
            $paginationHtml = view('customers.partials.pagination', compact('customers'))->render();

            return response()->json([
                'table' => $tableHtml,
                'pagination' => $paginationHtml,
            ]);
        }

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        // Check if this is a quick add request (from quote modal)
        // Use a flag to distinguish between quick add and full form (both use AJAX)
        $isQuickAdd = $request->has('quick_add') && $request->quick_add == '1';

        if ($isQuickAdd) {
            // Simplified validation for quick add
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'terms' => 'nullable|in:cash,credit,mixed',
                'credit_limit' => 'nullable|numeric|min:0',
                'price_tier' => 'nullable|in:normal,online,workshop',
            ]);

            // Set defaults for quick add
            $data['terms'] = $data['terms'] ?? 'cash';
            $data['credit_limit'] = $data['credit_limit'] ?? 0;
            $data['customer_category'] = 'individual';
            $data['customer_status'] = 'active';
            $data['price_tier'] = $data['price_tier'] ?? 'normal';
        } else {
            // Full validation for normal customer creation
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'customer_code' => 'nullable|string|max:255|unique:customers,customer_code',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'vehicle_make' => 'nullable|string|max:100',
                'vehicle_model' => 'nullable|string|max:100',
                'vehicle_vin' => 'nullable|string|max:100',
                'vehicle_reg' => 'nullable|string|max:20',
                'vehicle_mileage' => 'nullable|string|max:20',
                'terms' => 'required|in:cash,credit,mixed',
                'credit_limit' => 'nullable|numeric|min:0',
                'price_tier' => 'required|in:normal,online,workshop',
                'company_name' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'customer_category' => 'required|in:individual,business',
                'customer_status' => 'required|in:active,inactive',
                'marketing_consent' => 'boolean',
                'sms_consent' => 'boolean',
            ]);
        }

        try {
            // Customer code will be auto-generated in the model if not provided
            $customer = Customer::create($data);

            // Handle vehicles if provided
            if ($request->has('vehicles') && is_array($request->vehicles)) {
                foreach ($request->vehicles as $vehicleData) {
                    // Skip empty vehicle entries
                    if (empty(array_filter($vehicleData))) {
                        continue;
                    }

                    // If this vehicle is marked as primary, unset other primary vehicles
                    if (isset($vehicleData['is_primary']) && $vehicleData['is_primary']) {
                        $customer->vehicles()->update(['is_primary' => false]);
                    }

                    $customer->vehicles()->create([
                        'make_id' => $vehicleData['make_id'] ?? null,
                        'model_id' => $vehicleData['model_id'] ?? null,
                        'engine' => $vehicleData['engine'] ?? null,
                        'registration_number' => $vehicleData['registration_number'] ?? null,
                        'vin_number' => $vehicleData['vin_number'] ?? null,
                        'year' => $vehicleData['year'] ?? null,
                        'color' => $vehicleData['color'] ?? null,
                        'mileage' => $vehicleData['mileage'] ?? null,
                        'is_primary' => isset($vehicleData['is_primary']) && $vehicleData['is_primary'] ? true : false,
                    ]);
                }
            }

            // Return JSON for AJAX requests (both quick add and full form)
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully!',
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'customer_code' => $customer->customer_code,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'address' => $customer->address,
                        'price_tier' => $customer->price_tier,
                    ],
                ]);
            }

            return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create customer: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('customers.index')->with('error', 'Failed to create customer: '.$e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'customer_code' => 'nullable|string|max:255|unique:customers,customer_code,'.$customer->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_vin' => 'nullable|string|max:100',
            'vehicle_reg' => 'nullable|string|max:20',
            'vehicle_mileage' => 'nullable|string|max:20',
            'terms' => 'required|in:cash,credit,mixed',
            'credit_limit' => 'nullable|numeric|min:0',
            'price_tier' => 'required|in:normal,online,workshop',
            'tax_number' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'customer_category' => 'required|in:individual,business',
            'customer_status' => 'required|in:active,inactive',
            'marketing_consent' => 'boolean',
            'sms_consent' => 'boolean',
        ]);

        try {
            $customer->update($data);

            // Handle deleted vehicles
            if ($request->has('deleted_vehicles') && is_array($request->deleted_vehicles)) {
                foreach ($request->deleted_vehicles as $vehicleId) {
                    CustomerVehicle::where('id', $vehicleId)
                        ->where('customer_id', $customer->id)
                        ->delete();
                }
            }

            // Handle existing vehicles updates
            if ($request->has('existing_vehicles') && is_array($request->existing_vehicles)) {
                foreach ($request->existing_vehicles as $vehicleId => $vehicleData) {
                    $vehicle = $customer->vehicles()->find($vehicleId);
                    if ($vehicle) {
                        // If this vehicle is marked as primary, unset other primary vehicles
                        if (isset($vehicleData['is_primary']) && $vehicleData['is_primary']) {
                            $customer->vehicles()->where('id', '!=', $vehicleId)->update(['is_primary' => false]);
                        }

                        $vehicle->update([
                            'make_id' => $vehicleData['make_id'] ?? null,
                            'model_id' => $vehicleData['model_id'] ?? null,
                            'engine' => $vehicleData['engine'] ?? null,
                            'registration_number' => $vehicleData['registration_number'] ?? null,
                            'vin_number' => $vehicleData['vin_number'] ?? null,
                            'year' => $vehicleData['year'] ?? null,
                            'color' => $vehicleData['color'] ?? null,
                            'mileage' => $vehicleData['mileage'] ?? null,
                            'is_primary' => isset($vehicleData['is_primary']) && $vehicleData['is_primary'] ? true : false,
                        ]);
                    }
                }
            }

            // Handle new vehicles
            if ($request->has('new_vehicles') && is_array($request->new_vehicles)) {
                foreach ($request->new_vehicles as $vehicleData) {
                    // Skip empty vehicle entries
                    if (empty(array_filter($vehicleData))) {
                        continue;
                    }

                    // If this vehicle is marked as primary, unset other primary vehicles
                    if (isset($vehicleData['is_primary']) && $vehicleData['is_primary']) {
                        $customer->vehicles()->update(['is_primary' => false]);
                    }

                    $customer->vehicles()->create([
                        'make_id' => $vehicleData['make_id'] ?? null,
                        'model_id' => $vehicleData['model_id'] ?? null,
                        'engine' => $vehicleData['engine'] ?? null,
                        'registration_number' => $vehicleData['registration_number'] ?? null,
                        'vin_number' => $vehicleData['vin_number'] ?? null,
                        'year' => $vehicleData['year'] ?? null,
                        'color' => $vehicleData['color'] ?? null,
                        'mileage' => $vehicleData['mileage'] ?? null,
                        'is_primary' => isset($vehicleData['is_primary']) && $vehicleData['is_primary'] ? true : false,
                    ]);
                }
            }

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Failed to update customer: '.$e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            // Check if customer has any related records
            if ($customer->quotes()->count() > 0) {
                return redirect()->route('customers.index')->with('error', 'Cannot delete customer with associated quotes.');
            }

            if ($customer->invoices()->count() > 0) {
                return redirect()->route('customers.index')->with('error', 'Cannot delete customer with invoices.');
            }

            if ($customer->payments()->count() > 0) {
                return redirect()->route('customers.index')->with('error', 'Cannot delete customer with payments.');
            }

            $customer->delete();

            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Failed to delete customer: '.$e->getMessage());
        }
    }

    // Additional methods for enhanced functionality
    public function show(Customer $customer)
    {
        $customer->load(['quotes', 'invoices', 'payments', 'creditNotes']);

        return view('customers.show', compact('customer'));
    }

    public function getCustomerDetails($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'customer_code' => $customer->customer_code,
            'name' => $customer->name,
            'display_name' => $customer->display_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'full_address' => $customer->full_address,
            'contact_person' => $customer->contact_person,
            'terms' => $customer->terms,
            'credit_limit' => $customer->credit_limit,
            'balance' => $customer->balance,
            'available_credit' => $customer->available_credit,
            'price_tier' => $customer->price_tier,
            'customer_status' => $customer->customer_status,
        ]);
    }

    public function toggleStatus(Customer $customer)
    {
        try {
            // Toggle between active and inactive only
            // If suspended, clicking toggle will activate them
            if ($customer->customer_status === 'active') {
                $customer->customer_status = 'inactive';
                $message = 'Customer deactivated successfully.';
                $icon = 'info';
            } else {
                // Both inactive and suspended will be set to active
                $customer->customer_status = 'active';
                $message = 'Customer activated successfully.';
                $icon = 'success';
            }
            
            $customer->save();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update customer status: '.$e->getMessage());
        }
    }

    /**
     * Search customers for quote/invoice selection
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['customers' => []]);
        }

        $customers = Customer::where(function ($q) use ($query) {
            // Search by name
            $q->where('name', 'like', "%{$query}%")
              // Search by customer code
                ->orWhere('customer_code', 'like', "%{$query}%")
              // Search by email
                ->orWhere('email', 'like', "%{$query}%")
              // Search by phone
                ->orWhere('phone', 'like', "%{$query}%")
              // Search by company name
                ->orWhere('company_name', 'like', "%{$query}%")
              // Search by contact person
                ->orWhere('contact_person', 'like', "%{$query}%");
        })
            ->where('customer_status', 'active')
            ->limit(10)
            ->get();

        return response()->json([
            'customers' => $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'customer_code' => $customer->customer_code,
                    'name' => $customer->name,
                    'display_name' => $customer->display_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'company_name' => $customer->company_name,
                    'contact_person' => $customer->contact_person,
                    'full_address' => $customer->full_address,
                    'terms' => $customer->terms,
                    'credit_limit' => $customer->credit_limit,
                    'balance' => $customer->balance,
                    'available_credit' => $customer->available_credit,
                    'price_tier' => $customer->price_tier,
                    'customer_status' => $customer->customer_status,
                    'vehicle_make' => $customer->vehicle_make,
                    'vehicle_model' => $customer->vehicle_model,
                    'vehicle_reg' => $customer->vehicle_reg,
                ];
            }),
        ]);
    }

    /**
     * Get all car makes for Select2
     */
    public function getMakes(Request $request)
    {
        $search = $request->get('q', '');

        $makes = CarMake::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name']);

        return response()->json([
            'results' => $makes->map(function ($make) {
                return [
                    'id' => $make->id,
                    'text' => $make->name,
                ];
            }),
        ]);
    }

    /**
     * Get car models by make for Select2
     */
    public function getModels(Request $request)
    {
        $makeId = $request->get('make_id');
        $search = $request->get('q', '');

        $models = CarModel::when($makeId, function ($query) use ($makeId) {
            return $query->where('make_id', $makeId);
        })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'make_id']);

        return response()->json([
            'results' => $models->map(function ($model) {
                return [
                    'id' => $model->id,
                    'text' => $model->name,
                ];
            }),
        ]);
    }

    /**
     * Get engines by model for Select2
     */
    public function getEngines(Request $request)
    {
        $modelId = $request->get('model_id');
        $search = $request->get('q', '');

        $query = Engine::query();

        if ($modelId) {
            $query->whereHas('models', function ($q) use ($modelId) {
                $q->where('car_models.id', $modelId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $engines = $query->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'code']);

        return response()->json([
            'results' => $engines->map(function ($engine) {
                $text = $engine->name;
                if ($engine->code) {
                    $text .= " ({$engine->code})";
                }

                return [
                    'id' => $engine->id,
                    'text' => $text,
                ];
            }),
        ]);
    }

    /**
     * Store customer vehicle
     */
    public function storeVehicle(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'make_id' => 'nullable|exists:car_makes,id',
            'model_id' => 'nullable|exists:car_models,id',
            'engine_id' => 'nullable|exists:engines,id',
            'registration_number' => 'nullable|string|max:255',
            'vin_number' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
            'color' => 'nullable|string|max:50',
            'mileage' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean',
        ]);

        // If this is set as primary, unset other primary vehicles
        if ($request->is_primary) {
            $customer->vehicles()->update(['is_primary' => false]);
        }

        $vehicle = $customer->vehicles()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully!',
            'vehicle' => $vehicle->load(['make', 'model', 'engine']),
        ]);
    }

    /**
     * Delete customer vehicle
     */
    public function deleteVehicle(Customer $customer, CustomerVehicle $vehicle)
    {
        if ($vehicle->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found for this customer',
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully!',
        ]);
    }
    
    /**
     * Get customer vehicles for POS
     */
    public function getVehicles($id)
    {
        $customer = Customer::with(['vehicles.make', 'vehicles.model', 'vehicles.engine'])->findOrFail($id);
        
        $vehicles = $customer->vehicles->map(function($vehicle) {
            return [
                'id' => $vehicle->id,
                'make_id' => $vehicle->make_id,
                'model_id' => $vehicle->model_id,
                'make_name' => $vehicle->make->name ?? 'Unknown',
                'model_name' => $vehicle->model->name ?? 'Unknown',
                'engine' => $vehicle->engine->code ?? $vehicle->engine ?? '',
                'registration_number' => $vehicle->registration_number ?? '',
                'year' => $vehicle->year ?? '',
                'color' => $vehicle->color ?? '',
                'mileage' => $vehicle->mileage ?? '',
                'vin_number' => $vehicle->vin_number ?? '',
                'is_primary' => $vehicle->is_primary ?? false,
                'display_name' => sprintf('%s %s - %s', 
                    $vehicle->make->name ?? 'Unknown',
                    $vehicle->model->name ?? 'Unknown',
                    $vehicle->registration_number ?? 'No Reg'
                )
            ];
        });
        
        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }
}
