<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\JobCardLabour;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class JobCardController extends Controller
{
    /**
     * Display a listing of job cards
     */
    public function index(Request $request)
    {
        $query = JobCard::with(['customer', 'createdBy', 'items', 'labour']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle make
        if ($request->filled('vehicle_make')) {
            $query->where('vehicle_make', 'like', '%' . $request->vehicle_make . '%');
        }

        // Filter by customer
        if ($request->filled('customer')) {
            $search = $request->customer;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Search in job card number, customer, vehicle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_card_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('vehicle_registration', 'like', "%{$search}%")
                  ->orWhere('vehicle_vin', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobCards = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('job-cards.index', compact('jobCards'));
    }

    /**
     * Show the form for creating a new job card
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->select('id', 'name', 'sku', 'barcode_primary', 'price_normal', 'price_workshop', 'on_hand', 'reserved')
            ->get();
        $technicians = User::where('status', 'active')->get();

        return view('job-cards.partials.create_modal', compact('customers', 'products', 'technicians'))->render();
    }

    /**
     * Store a newly created job card
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_id,null|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_vin' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:20',
            'vehicle_mileage' => 'nullable|string|max:20',
            'engine_code' => 'nullable|string|max:50',
            'vehicle_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'initial_status' => 'nullable|in:pending,booked,in_progress',
            'job_description' => 'required|string|max:1000',
            'customer_complaint' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity_used' => 'required_with:items|numeric|min:0.001',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:500',
            'labour' => 'nullable|array',
            'labour.*.labour_description' => 'required_with:labour|string|max:255',
            'labour.*.detailed_description' => 'nullable|string|max:1000',
            'labour.*.hours_worked' => 'required_with:labour|numeric|min:0',
            'labour.*.hourly_rate' => 'required_with:labour|numeric|min:0',
            'labour.*.labour_type' => 'required_with:labour|in:diagnostic,repair,maintenance,installation,other',
            'labour.*.technician_id' => 'nullable|exists:users,id',
            'labour.*.notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Get initial status (default: pending)
            $initialStatus = $request->input('initial_status', 'pending');
            
            // Create job card with selected status
            $jobCard = JobCard::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_vin' => $request->vehicle_vin,
                'vehicle_registration' => $request->vehicle_registration,
                'vehicle_mileage' => $request->vehicle_mileage,
                'engine_code' => $request->engine_code,
                'vehicle_year' => $request->vehicle_year,
                'job_description' => $request->job_description,
                'customer_complaint' => $request->customer_complaint,
                'notes' => $request->notes,
                'status' => $initialStatus,
                'booked_at' => $initialStatus === 'booked' ? now() : null,
                'started_at' => $initialStatus === 'in_progress' ? now() : null,
                'created_by' => auth()->id(),
            ]);

            // Create job card items
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    $lineTotal = $item['quantity_used'] * $item['unit_price'];

                    JobCardItem::create([
                        'job_card_id' => $jobCard->id,
                        'product_id' => $item['product_id'],
                        'product_sku' => $product->sku,
                        'product_name' => $product->name,
                        'product_barcode' => $product->barcode_primary,
                        'quantity_used' => $item['quantity_used'],
                        'unit_price' => $item['unit_price'],
                        'line_total' => $lineTotal,
                        'notes' => $item['notes'] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // Create job card labour
            if ($request->has('labour')) {
                foreach ($request->labour as $labour) {
                    $totalAmount = $labour['hours_worked'] * $labour['hourly_rate'];
                    $technician = User::find($labour['technician_id']);

                    JobCardLabour::create([
                        'job_card_id' => $jobCard->id,
                        'labour_description' => $labour['labour_description'],
                        'detailed_description' => $labour['detailed_description'] ?? null,
                        'hours_worked' => $labour['hours_worked'],
                        'hourly_rate' => $labour['hourly_rate'],
                        'total_amount' => $totalAmount,
                        'labour_type' => $labour['labour_type'],
                        'technician_id' => $labour['technician_id'],
                        'technician_name' => $technician ? $technician->name : null,
                        'status' => 'pending',
                        'notes' => $labour['notes'] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // Calculate totals
            $jobCard->calculateTotals();

            // Reserve parts if status is booked or in_progress (Requirements!)
            if (in_array($initialStatus, ['booked', 'in_progress']) && $request->has('items')) {
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('reserved', $item['quantity_used']);
                        
                        // Create stock ledger entry for reservation
                        StockLedger::create([
                            'product_id' => $product->id,
                            'document_type' => 'job_card_reserve',
                            'document_id' => $jobCard->id,
                            'qty' => -$item['quantity_used'],
                            'unit_cost' => 0,
                            'total_cost' => 0,
                            'user_id' => auth()->id(),
                            'notes' => "Parts reserved for Job Card {$jobCard->job_card_number} (created as {$initialStatus})",
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job card created successfully',
                'job_card_id' => $jobCard->id,
                'job_card_number' => $jobCard->job_card_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating job card: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified job card
     */
    public function show(JobCard $jobCard)
    {
        $jobCard->load(['customer', 'createdBy', 'items.product', 'labour.technician']);
        
        return view('job-cards.partials.view_modal', compact('jobCard'))->render();
    }

    /**
     * Show the form for editing the specified job card
     */
    public function edit(JobCard $jobCard)
    {
        if ($jobCard->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Completed job cards cannot be edited',
            ], 400);
        }

        $jobCard->load(['customer', 'items.product', 'labour.technician']);
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();
        $technicians = User::where('status', 'active')->get();

        return view('job-cards.partials.edit_modal', compact('jobCard', 'customers', 'products', 'technicians'))->render();
    }

    /**
     * Update the specified job card
     */
    public function update(Request $request, JobCard $jobCard)
    {
        if ($jobCard->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Completed job cards cannot be updated',
            ], 400);
        }

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_id,null|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_vin' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:20',
            'vehicle_mileage' => 'nullable|string|max:20',
            'engine_code' => 'nullable|string|max:50',
            'vehicle_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'job_description' => 'required|string|max:1000',
            'customer_complaint' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Update job card
            $jobCard->update([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_vin' => $request->vehicle_vin,
                'vehicle_registration' => $request->vehicle_registration,
                'vehicle_mileage' => $request->vehicle_mileage,
                'engine_code' => $request->engine_code,
                'vehicle_year' => $request->vehicle_year,
                'job_description' => $request->job_description,
                'customer_complaint' => $request->customer_complaint,
                'notes' => $request->notes,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job card updated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating job card: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change job card status
     */
    public function changeStatus(Request $request, JobCard $jobCard)
    {
        $request->validate([
            'status' => 'required|in:pending,booked,in_progress,completed,cancelled',
        ]);

        $newStatus = $request->status;

        // Validate status transitions
        $validTransitions = [
            'pending' => ['booked', 'cancelled'],
            'booked' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [], // No transitions from completed
            'cancelled' => [], // No transitions from cancelled
        ];

        if (!in_array($newStatus, $validTransitions[$jobCard->status])) {
            return response()->json([
                'success' => false,
                'message' => "Cannot change status from {$jobCard->status} to {$newStatus}",
            ], 400);
        }

        DB::beginTransaction();

        try {
            switch ($newStatus) {
                case 'booked':
                    $jobCard->markAsBooked();
                    
                    // Reserve parts when booking (Requirements: parts reserved when booked)
                    foreach ($jobCard->items as $item) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->increment('reserved', $item->quantity_used);
                            
                            // Create stock ledger entry for reservation
                            StockLedger::create([
                                'product_id' => $product->id,
                                'document_type' => 'job_card_reserve',
                                'document_id' => $jobCard->id,
                                'qty' => -$item->quantity_used,
                                'unit_cost' => 0,
                                'total_cost' => 0,
                                'user_id' => auth()->id(),
                                'notes' => "Parts reserved for Job Card {$jobCard->job_card_number}",
                            ]);
                        }
                    }
                    break;
                    
                case 'in_progress':
                    $oldStatus = $jobCard->status;
                    $jobCard->markAsInProgress();
                    
                    // Reserve parts if not already reserved (coming from pending)
                    if ($oldStatus === 'pending') {
                        foreach ($jobCard->items as $item) {
                            $product = Product::find($item->product_id);
                            if ($product) {
                                $product->increment('reserved', $item->quantity_used);
                                
                                StockLedger::create([
                                    'product_id' => $product->id,
                                    'document_type' => 'job_card_reserve',
                                    'document_id' => $jobCard->id,
                                    'qty' => -$item->quantity_used,
                                    'unit_cost' => 0,
                                    'total_cost' => 0,
                                    'user_id' => auth()->id(),
                                    'notes' => "Parts reserved for Job Card {$jobCard->job_card_number}",
                                ]);
                            }
                        }
                    }
                    break;
                    
                case 'completed':
                    $jobCard->markAsCompleted();
                    
                    // Note: Reserved parts will be released when converted to invoice
                    // No stock movement here, only on invoice conversion
                    break;
                    
                case 'cancelled':
                    $jobCard->cancel();
                    
                    // Release reserved parts if cancelled
                    if (in_array($jobCard->status, ['booked', 'in_progress'])) {
                        foreach ($jobCard->items as $item) {
                            $product = Product::find($item->product_id);
                            if ($product) {
                                $product->decrement('reserved', $item->quantity_used);
                                
                                StockLedger::create([
                                    'product_id' => $product->id,
                                    'document_type' => 'job_card_cancel',
                                    'document_id' => $jobCard->id,
                                    'qty' => $item->quantity_used,
                                    'unit_cost' => 0,
                                    'total_cost' => 0,
                                    'user_id' => auth()->id(),
                                    'notes' => "Parts released from cancelled Job Card {$jobCard->job_card_number}",
                                ]);
                            }
                        }
                    }
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Job card status changed to {$newStatus}",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error changing status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Convert job card to final invoice
     */
    public function convertToInvoice(JobCard $jobCard)
    {
        if (!$jobCard->canConvertToInvoice()) {
            return response()->json([
                'success' => false,
                'message' => 'Job card cannot be converted to invoice',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $jobCard->customer_id,
                'customer_name' => $jobCard->customer_name,
                'customer_phone' => $jobCard->customer_phone,
                'customer_email' => $jobCard->customer_email,
                'vehicle_make' => $jobCard->vehicle_make,
                'vehicle_model' => $jobCard->vehicle_model,
                'vehicle_vin' => $jobCard->vehicle_vin,
                'vehicle_reg' => $jobCard->vehicle_registration,
                'vehicle_mileage' => $jobCard->vehicle_mileage,
                'subtotal' => $jobCard->grand_total,
                'discount_amount' => 0,
                'discount_type' => 'fixed',
                'shipping' => 0,
                'vat_amount' => 0,
                'vat_enabled' => false,
                'vat_rate' => 0,
                'grand_total' => $jobCard->grand_total,
                'payment_status' => 'draft',
                'payment_method' => 'on_account',
                'notes' => "Final invoice for Job Card: {$jobCard->job_card_number}",
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            // Create invoice items from job card items & release reservations
            foreach ($jobCard->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'product_sku' => $item->product_sku,
                    'product_name' => $item->product_name,
                    'product_barcode' => $item->product_barcode,
                    'quantity' => $item->quantity_used,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->line_total,
                    'created_by' => auth()->id(),
                ]);
                
                // Release reservation and consume stock (Requirements: reserved parts released on invoice)
                $product = Product::find($item->product_id);
                if ($product) {
                    // Release from reserved
                    $product->decrement('reserved', $item->quantity_used);
                    
                    // Consume from on_hand (allows negative if product allows)
                    $product->decrement('on_hand', $item->quantity_used);
                    
                    // Stock ledger entry for consumption
                    StockLedger::create([
                        'product_id' => $product->id,
                        'document_type' => 'job_card_invoice',
                        'document_id' => $invoice->id,
                        'qty' => -$item->quantity_used,
                        'unit_cost' => 0, // Can add FIFO cost later
                        'total_cost' => 0,
                        'user_id' => auth()->id(),
                        'notes' => "Stock consumed for Job Card {$jobCard->job_card_number} → Invoice {$invoice->invoice_number}",
                    ]);
                }
            }

            // Create invoice items from labour (as services)
            foreach ($jobCard->labour as $labour) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => null,
                    'product_sku' => 'LABOUR',
                    'product_name' => $labour->labour_description,
                    'product_barcode' => null,
                    'quantity' => $labour->hours_worked,
                    'unit_price' => $labour->hourly_rate,
                    'line_total' => $labour->total_amount,
                    'created_by' => auth()->id(),
                ]);
            }

            // Update job card with final invoice reference
            $jobCard->update([
                'final_invoice_id' => $invoice->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job card converted to invoice successfully',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error converting to invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add item to job card
     */
    public function addItem(Request $request, JobCard $jobCard)
    {
        if ($jobCard->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add items to completed job card',
            ], 400);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_used' => 'required|numeric|min:0.001',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $product = Product::find($request->product_id);
            $lineTotal = $request->quantity_used * $request->unit_price;

            $item = JobCardItem::create([
                'job_card_id' => $jobCard->id,
                'product_id' => $request->product_id,
                'product_sku' => $product->sku,
                'product_name' => $product->name,
                'product_barcode' => $product->barcode_primary,
                'quantity_used' => $request->quantity_used,
                'unit_price' => $request->unit_price,
                'line_total' => $lineTotal,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $jobCard->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'item' => $item,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add labour to job card
     */
    public function addLabour(Request $request, JobCard $jobCard)
    {
        if ($jobCard->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add labour to completed job card',
            ], 400);
        }

        $request->validate([
            'labour_description' => 'required|string|max:255',
            'detailed_description' => 'nullable|string|max:1000',
            'hours_worked' => 'required|numeric|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'labour_type' => 'required|in:diagnostic,repair,maintenance,installation,other',
            'technician_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $totalAmount = $request->hours_worked * $request->hourly_rate;
            $technician = User::find($request->technician_id);

            $labour = JobCardLabour::create([
                'job_card_id' => $jobCard->id,
                'labour_description' => $request->labour_description,
                'detailed_description' => $request->detailed_description,
                'hours_worked' => $request->hours_worked,
                'hourly_rate' => $request->hourly_rate,
                'total_amount' => $totalAmount,
                'labour_type' => $request->labour_type,
                'technician_id' => $request->technician_id,
                'technician_name' => $technician ? $technician->name : null,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $jobCard->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Labour added successfully',
                'labour' => $labour,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding labour: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate job card PDF
     */
    public function generatePDF(JobCard $jobCard)
    {
        $jobCard->load(['customer', 'items.product', 'labour', 'createdBy', 'finalInvoice']);
        
        $pdf = Pdf::loadView('job-cards.pdf', compact('jobCard'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Job-Card-{$jobCard->job_card_number}.pdf");
    }

    /**
     * Delete a job card
     */
    public function destroy(JobCard $jobCard)
    {
        if ($jobCard->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Completed job cards cannot be deleted',
            ], 400);
        }

        try {
            $jobCard->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job card deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting job card: ' . $e->getMessage(),
            ], 500);
        }
    }
}