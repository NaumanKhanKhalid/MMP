<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['customer', 'supplier', 'user']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('payment_type', $request->type);
        }

        // Filter by payment method
        if ($request->has('method') && $request->method) {
            $query->where('payment_method', $request->method);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show form for creating new payment
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'customer'); // customer or supplier
        
        if ($type === 'customer') {
            $customers = Customer::active()->orderBy('name')->get();
            return view('payments.partials.create_customer_modal', compact('customers'))->render();
        } else {
            $suppliers = Supplier::active()->orderBy('name')->get();
            return view('payments.partials.create_supplier_modal', compact('suppliers'))->render();
        }
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $rules = [
            'payment_type' => 'required|in:customer,supplier',
            'payment_method' => 'required|in:cash,card,eft',
            'payment_date' => 'required|date',
            'gross_amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];

        if ($request->payment_type === 'customer') {
            $rules['customer_id'] = 'required|exists:customers,id';
        } else {
            $rules['supplier_id'] = 'required|exists:suppliers,id';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Calculate fees based on payment method
            $feeAmount = $this->calculateFees($validated['gross_amount'], $validated['payment_method']);
            $netAmount = $validated['gross_amount'] - $feeAmount;

            // Create payment
            $payment = Payment::create([
                'payment_type' => $validated['payment_type'],
                'customer_id' => $validated['customer_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'] ?? null,
                'payment_date' => $validated['payment_date'],
                'gross_amount' => $validated['gross_amount'],
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'unallocated_amount' => $netAmount,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
                'status' => 'posted',
            ]);

            // Create ledger entry
            if ($payment->isCustomerPayment()) {
                CustomerLedger::createEntry(
                    $payment->customer_id,
                    'payment',
                    $payment->id,
                    $payment->payment_number,
                    $payment->payment_date,
                    0, // debit
                    $payment->net_amount, // credit (they paid us)
                    'Payment received - ' . $payment->payment_method
                );

                // Update customer balance
                $payment->customer->updateBalance();
            } else {
                SupplierLedger::createEntry(
                    $payment->supplier_id,
                    'payment',
                    $payment->id,
                    $payment->payment_number,
                    $payment->payment_date,
                    $payment->net_amount, // debit (we paid them)
                    0, // credit
                    'Payment made - ' . $payment->payment_method
                );

                // Update supplier balance
                $payment->supplier->updateBalance();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'payment_id' => $payment->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error recording payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * View payment details
     */
    public function viewModal($id)
    {
        $payment = Payment::with(['customer', 'supplier', 'user', 'allocations.invoice', 'allocations.supplierInvoice'])
            ->findOrFail($id);

        return view('payments.partials.view_modal', compact('payment'))->render();
    }

    /**
     * Show allocation modal
     */
    public function allocateModal($id)
    {
        $payment = Payment::with(['customer', 'supplier'])->findOrFail($id);

        if ($payment->isFullyAllocated()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is fully allocated',
            ], 400);
        }

        if ($payment->isCustomerPayment()) {
            // Get unpaid/partially paid invoices for this customer
            $invoices = Invoice::where('customer_id', $payment->customer_id)
                ->whereIn('payment_status', ['posted', 'paid'])
                ->where('balance_due', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            return view('payments.partials.allocate_customer_modal', compact('payment', 'invoices'))->render();
        } else {
            // Get unpaid/partially paid supplier invoices
            $supplierInvoices = SupplierInvoice::where('supplier_id', $payment->supplier_id)
                ->whereIn('status', ['posted', 'paid'])
                ->where('balance_due', '>', 0)
                ->orderBy('invoice_date', 'asc')
                ->get();

            return view('payments.partials.allocate_supplier_modal', compact('payment', 'supplierInvoices'))->render();
        }
    }

    /**
     * Allocate payment to invoices
     */
    public function allocate(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'allocations' => 'required|array|min:1',
            'allocations.*.invoice_id' => 'required_without:allocations.*.supplier_invoice_id|exists:invoices,id',
            'allocations.*.supplier_invoice_id' => 'required_without:allocations.*.invoice_id|exists:supplier_invoices,id',
            'allocations.*.amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $totalAllocated = 0;

            foreach ($validated['allocations'] as $allocation) {
                $amount = $allocation['amount'];
                $totalAllocated += $amount;

                // Check if we have enough unallocated amount
                if ($totalAllocated > $payment->unallocated_amount) {
                    throw new \Exception('Allocation amount exceeds unallocated payment amount');
                }

                // Create allocation
                $paymentAllocation = PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'invoice_id' => $allocation['invoice_id'] ?? null,
                    'supplier_invoice_id' => $allocation['supplier_invoice_id'] ?? null,
                    'allocated_amount' => $amount,
                    'allocation_date' => now(),
                ]);

                // Update invoice balance
                if (isset($allocation['invoice_id'])) {
                    $invoice = Invoice::findOrFail($allocation['invoice_id']);
                    $invoice->amount_paid += $amount;
                    $invoice->balance_due -= $amount;
                    
                    if ($invoice->balance_due <= 0) {
                        $invoice->payment_status = 'paid';
                    }
                    $invoice->save();

                    // Create ledger entry
                    CustomerLedger::createEntry(
                        $payment->customer_id,
                        'allocation',
                        $paymentAllocation->id,
                        "Allocation to {$invoice->invoice_number}",
                        now(),
                        $amount, // debit (allocated)
                        0,
                        "Payment {$payment->payment_number} allocated to invoice"
                    );
                } else {
                    $supplierInvoice = SupplierInvoice::findOrFail($allocation['supplier_invoice_id']);
                    $supplierInvoice->paid_amount += $amount;
                    $supplierInvoice->balance_due -= $amount;
                    
                    if ($supplierInvoice->balance_due <= 0) {
                        $supplierInvoice->status = 'paid';
                    }
                    $supplierInvoice->save();

                    // Create ledger entry
                    SupplierLedger::createEntry(
                        $payment->supplier_id,
                        'allocation',
                        $paymentAllocation->id,
                        "Allocation to {$supplierInvoice->supplier_invoice_number}",
                        now(),
                        0,
                        $amount, // credit (allocated)
                        "Payment {$payment->payment_number} allocated to invoice"
                    );
                }
            }

            // Update payment allocation amounts
            $payment->allocated_amount += $totalAllocated;
            $payment->unallocated_amount -= $totalAllocated;
            $payment->save();

            // Update balance
            if ($payment->isCustomerPayment()) {
                $payment->customer->updateBalance();
            } else {
                $payment->supplier->updateBalance();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment allocated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error allocating payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Void a payment
     */
    public function void($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'voided') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is already voided',
            ], 400);
        }

        if ($payment->allocated_amount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot void a payment with allocations. Please remove allocations first.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create reversing ledger entry
            if ($payment->isCustomerPayment()) {
                CustomerLedger::createEntry(
                    $payment->customer_id,
                    'payment_void',
                    $payment->id,
                    $payment->payment_number . ' (VOID)',
                    now(),
                    $payment->net_amount, // debit (reverse the credit)
                    0,
                    'Payment voided'
                );

                $payment->customer->updateBalance();
            } else {
                SupplierLedger::createEntry(
                    $payment->supplier_id,
                    'payment_void',
                    $payment->id,
                    $payment->payment_number . ' (VOID)',
                    now(),
                    0,
                    $payment->net_amount, // credit (reverse the debit)
                    'Payment voided'
                );

                $payment->supplier->updateBalance();
            }

            $payment->update(['status' => 'voided']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment voided successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error voiding payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get outstanding invoices for customer/supplier
     */
    public function getOutstandingInvoices(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type === 'customer') {
            $invoices = Invoice::where('customer_id', $id)
                ->whereIn('payment_status', ['posted'])
                ->where('balance_due', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get(['id', 'invoice_number', 'grand_total', 'balance_due', 'created_at']);

            return response()->json(['invoices' => $invoices]);
        } else {
            $invoices = SupplierInvoice::where('supplier_id', $id)
                ->whereIn('status', ['posted'])
                ->where('balance_due', '>', 0)
                ->orderBy('invoice_date', 'asc')
                ->get(['id', 'supplier_invoice_number', 'total_amount', 'balance_due', 'invoice_date']);

            return response()->json(['invoices' => $invoices]);
        }
    }

    /**
     * Calculate fees based on payment method and settings
     */
    private function calculateFees($amount, $method)
    {
        // Get fees from settings
        $cardFeePercentage = \App\Models\Setting::cardFeePercentage();
        $cashDepositFee = \App\Models\Setting::cashDepositFee();
        $eftFee = \App\Models\Setting::get('eft_fee', 0);

        if ($method === 'card') {
            return ($amount * $cardFeePercentage) / 100;
        } elseif ($method === 'cash') {
            return ($amount / 100) * $cashDepositFee;
        } elseif ($method === 'eft') {
            return $eftFee;
        }

        return 0;
    }
}
