<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(SupplierRequest $request)
    {
        try {
            Supplier::create($request->validated());
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $supplier->update($request->validated());
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has any related records
            if ($supplier->products()->count() > 0) {
                return redirect()->route('suppliers.index')->with('error', 'Cannot delete supplier with associated products.');
            }
            
            if ($supplier->grns()->count() > 0) {
                return redirect()->route('suppliers.index')->with('error', 'Cannot delete supplier with goods receipts.');
            }
            
            if ($supplier->purchaseOrders()->count() > 0) {
                return redirect()->route('suppliers.index')->with('error', 'Cannot delete supplier with purchase orders.');
            }

            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Supplier $supplier)
    {
        try {
            $supplier->status = $supplier->status === 'active' ? 'inactive' : 'active';
            $supplier->save();
            return redirect()->back()->with('success', 'Supplier status updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update supplier status: ' . $e->getMessage());
        }
    }

    // Additional methods for enhanced functionality
    public function show(Supplier $supplier)
    {
        $supplier->load(['products', 'grns', 'purchaseOrders']);
        return view('suppliers.show', compact('supplier'));
    }

    public function getSupplierDetails($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json([
            'supplier_code' => $supplier->supplier_code,
            'name' => $supplier->name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'address' => $supplier->address,
            'contact_person' => $supplier->contact_person,
            'payment_terms' => $supplier->payment_terms,
            'credit_limit' => $supplier->credit_limit,
            'balance' => $supplier->balance,
            'available_credit' => $supplier->available_credit,
        ]);
    }

    public function createModal()
    {
        return view('suppliers.partials.create_modal');
    }

    public function viewModal(Supplier $supplier)
    {
        return view('suppliers.partials.view_modal', compact('supplier'));
    }

    public function editModal(Supplier $supplier)
    {
        return view('suppliers.partials.edit_modal', compact('supplier'));
    }
}
