<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $query = Supplier::query();
        
        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        // Filter by type
        if (request('type')) {
            $query->where('supplier_type', request('type'));
        }
        
        // Filter by balance
        if (request('balance') == 'overdue') {
            $query->where('balance', '>', 0);
        } elseif (request('balance') == 'positive') {
            $query->where('balance', '>', 0);
        } elseif (request('balance') == 'zero') {
            $query->where('balance', '=', 0);
        }
        
        $suppliers = $query->orderByDesc('id')->paginate(10);
        
        // If AJAX request, return JSON
        if (request()->ajax()) {
            $tableHtml = view('suppliers.partials.table', compact('suppliers'))->render();
            $paginationHtml = view('suppliers.partials.pagination', compact('suppliers'))->render();
            
            return response()->json([
                'success' => true,
                'table' => $tableHtml,
                'pagination' => $paginationHtml
            ]);
        }
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(SupplierRequest $request)
    {
        try {
            $supplier = Supplier::create($request->validated());
            
            // Return JSON for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier created successfully!',
                    'supplier' => $supplier
                ]);
            }
            
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create supplier: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('suppliers.index')->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'lead_time' => 'nullable|integer|min:0|max:365',
                'payment_terms' => 'required|string|max:100',
                'tax_number' => 'nullable|string|max:100',
                'bank_details' => 'nullable|string|max:1000',
                'contact_person' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'supplier_type' => 'required|in:individual,company',
                'credit_limit' => 'nullable|numeric|min:0',
                'balance' => 'nullable|numeric',
                'status' => 'required|in:active,inactive',
            ]);
            
            $supplier->update($validated);
            
            // Return JSON for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier updated successfully!',
                    'supplier' => $supplier
                ]);
            }
            
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update supplier: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('suppliers.index')->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }

    // Restore and Force Delete methods commented out for future use
    // public function restore($id)
    // {
    //     try {
    //         $supplier = Supplier::withTrashed()->findOrFail($id);
    //         $supplier->restore();
    //         return redirect()->route('suppliers.index')->with('success', 'Supplier restored successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('suppliers.index')->with('error', 'Failed to restore supplier: ' . $e->getMessage());
    //     }
    // }

    // public function forceDelete($id)
    // {
    //     try {
    //         $supplier = Supplier::withTrashed()->findOrFail($id);
    //         $supplier->forceDelete();
    //         return redirect()->route('suppliers.index')->with('success', 'Supplier permanently deleted.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('suppliers.index')->with('error', 'Failed to permanently delete supplier: ' . $e->getMessage());
    //     }
    // }

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
        $supplier->load(['purchaseOrders', 'grns', 'supplierInvoices', 'ledgerEntries']);
        return view('suppliers.partials.view_modal', compact('supplier'));
    }

    public function editModal(Supplier $supplier)
    {
        return view('suppliers.partials.edit_modal', compact('supplier'));
    }
}
