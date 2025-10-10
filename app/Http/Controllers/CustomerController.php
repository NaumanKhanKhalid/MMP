<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
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
            'terms' => 'required|in:cash,on_account',
            'credit_limit' => 'nullable|numeric|min:0',
            'price_tier' => 'required|in:normal,online,workshop',
            'statement_delivery' => 'required|in:email,whatsapp,pdf',
            'tax_number' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'customer_type' => 'required|in:individual,business',
            'customer_status' => 'required|in:active,inactive,suspended',
            'marketing_consent' => 'boolean',
            'sms_consent' => 'boolean',
        ]);

        try {
        Customer::create($data);
            return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Failed to create customer: ' . $e->getMessage());
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
            'customer_code' => 'nullable|string|max:255|unique:customers,customer_code,' . $customer->id,
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
            'terms' => 'required|in:cash,on_account',
            'credit_limit' => 'nullable|numeric|min:0',
            'price_tier' => 'required|in:normal,online,workshop',
            'statement_delivery' => 'required|in:email,whatsapp,pdf',
            'tax_number' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'customer_type' => 'required|in:individual,business',
            'customer_status' => 'required|in:active,inactive,suspended',
            'marketing_consent' => 'boolean',
            'sms_consent' => 'boolean',
        ]);

        try {
        $customer->update($data);
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Failed to update customer: ' . $e->getMessage());
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
            return redirect()->route('customers.index')->with('error', 'Failed to delete customer: ' . $e->getMessage());
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
            $statuses = ['active', 'inactive', 'suspended'];
            $currentIndex = array_search($customer->customer_status, $statuses);
            $nextIndex = ($currentIndex + 1) % count($statuses);
            $customer->customer_status = $statuses[$nextIndex];
            $customer->save();
            
            return redirect()->back()->with('success', 'Customer status updated to ' . ucfirst($customer->customer_status) . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update customer status: ' . $e->getMessage());
        }
    }
}
