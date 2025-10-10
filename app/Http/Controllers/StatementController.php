<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StatementController extends Controller
{
    /**
     * Generate Customer Statement PDF
     */
    public function customerStatement(Request $request, Customer $customer)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date 
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay() 
            : now()->startOfMonth();
        
        $endDate = $request->end_date 
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay() 
            : now()->endOfDay();

        // Get ledger entries for date range
        $ledgerEntries = $customer->ledgerEntries()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Calculate opening balance (balance before start date)
        $openingBalanceEntry = $customer->ledgerEntries()
            ->where('transaction_date', '<', $startDate)
            ->orderBy('id', 'desc')
            ->first();
        
        $openingBalance = $openingBalanceEntry ? $openingBalanceEntry->balance : 0;

        // Get closing balance (last entry in range or current balance)
        $closingBalance = $ledgerEntries->isNotEmpty() 
            ? $ledgerEntries->last()->balance 
            : $openingBalance;

        // Company settings
        $companySettings = [
            'name' => Setting::get('company_name', 'MMP Auto-Meister'),
            'email' => Setting::get('company_email'),
            'phone' => Setting::get('company_phone'),
            'address' => Setting::get('company_address'),
            'city' => Setting::get('company_city'),
            'postal_code' => Setting::get('company_postal_code'),
            'country' => Setting::get('company_country', 'South Africa'),
            'logo' => Setting::get('company_logo'),
            'vat_number' => Setting::get('company_vat_number'),
            'registration' => Setting::get('company_registration'),
        ];

        $pdf = Pdf::loadView('statements.customer', [
            'customer' => $customer,
            'ledgerEntries' => $ledgerEntries,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companySettings' => $companySettings,
            'generatedDate' => now(),
        ]);

        $filename = 'Statement_' . $customer->customer_code . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate Supplier Statement PDF
     */
    public function supplierStatement(Request $request, Supplier $supplier)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date 
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay() 
            : now()->startOfMonth();
        
        $endDate = $request->end_date 
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay() 
            : now()->endOfDay();

        // Get ledger entries for date range
        $ledgerEntries = $supplier->ledgerEntries()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Calculate opening balance
        $openingBalanceEntry = $supplier->ledgerEntries()
            ->where('transaction_date', '<', $startDate)
            ->orderBy('id', 'desc')
            ->first();
        
        $openingBalance = $openingBalanceEntry ? $openingBalanceEntry->balance : 0;

        // Get closing balance
        $closingBalance = $ledgerEntries->isNotEmpty() 
            ? $ledgerEntries->last()->balance 
            : $openingBalance;

        // Company settings
        $companySettings = [
            'name' => Setting::get('company_name', 'MMP Auto-Meister'),
            'email' => Setting::get('company_email'),
            'phone' => Setting::get('company_phone'),
            'address' => Setting::get('company_address'),
            'city' => Setting::get('company_city'),
            'postal_code' => Setting::get('company_postal_code'),
            'country' => Setting::get('company_country', 'South Africa'),
            'logo' => Setting::get('company_logo'),
            'vat_number' => Setting::get('company_vat_number'),
            'registration' => Setting::get('company_registration'),
        ];

        $pdf = Pdf::loadView('statements.supplier', [
            'supplier' => $supplier,
            'ledgerEntries' => $ledgerEntries,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companySettings' => $companySettings,
            'generatedDate' => now(),
        ]);

        $filename = 'Statement_' . $supplier->supplier_code . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show statement preview form
     */
    public function customerStatementForm(Customer $customer)
    {
        return view('statements.customer_form', compact('customer'));
    }

    /**
     * Show supplier statement preview form
     */
    public function supplierStatementForm(Supplier $supplier)
    {
        return view('statements.supplier_form', compact('supplier'));
    }
}

