<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $companySettings = Setting::getGroup('company');
        $vatSettings = Setting::getGroup('vat');
        $feeSettings = Setting::getGroup('fees');
        $numberingSettings = Setting::getGroup('numbering');
        $posSettings = Setting::getGroup('pos');
        $emailSettings = Setting::getGroup('email');
        $whatsappSettings = Setting::getGroup('whatsapp');
        $securitySettings = Setting::getGroup('security');

        return view('settings.index', compact(
            'companySettings',
            'vatSettings',
            'feeSettings',
            'numberingSettings',
            'posSettings',
            'emailSettings',
            'whatsappSettings',
            'securitySettings'
        ));
    }

    /**
     * Update company settings
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'company_address' => 'nullable|string',
            'company_city' => 'nullable|string',
            'company_postal_code' => 'nullable|string',
            'company_country' => 'nullable|string',
            'company_registration' => 'nullable|string',
            'company_vat_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_branch_code' => 'nullable|string',
            'company_logo' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            
            // Delete old logo if exists
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $validated['company_logo'] = $logoPath;
        }

        // Update all settings
        foreach ($validated as $key => $value) {
            if ($key !== 'company_logo' || $request->hasFile('company_logo')) {
                Setting::set($key, $value, 'string', 'company', ucwords(str_replace('_', ' ', $key)));
            }
        }

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Company settings updated successfully!',
        ]);
    }

    /**
     * Update VAT settings
     */
    public function updateVat(Request $request)
    {
        $validated = $request->validate([
            'vat_enabled' => 'required|boolean',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'vat_inclusive' => 'required|boolean',
        ]);

        Setting::set('vat_enabled', $validated['vat_enabled'], 'boolean', 'vat', 'VAT Enabled');
        Setting::set('vat_rate', $validated['vat_rate'], 'decimal', 'vat', 'VAT Rate (%)');
        Setting::set('vat_inclusive', $validated['vat_inclusive'], 'boolean', 'vat', 'VAT Inclusive');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'VAT settings updated successfully!',
        ]);
    }

    /**
     * Update payment fee settings
     */
    public function updateFees(Request $request)
    {
        $validated = $request->validate([
            'card_fee_percentage' => 'required|numeric|min:0|max:100',
            'cash_deposit_fee' => 'required|numeric|min:0',
            'eft_fee' => 'nullable|numeric|min:0',
        ]);

        Setting::set('card_fee_percentage', $validated['card_fee_percentage'], 'decimal', 'fees', 'Card Fee (%)');
        Setting::set('cash_deposit_fee', $validated['cash_deposit_fee'], 'decimal', 'fees', 'Cash Deposit Fee (per R100)');
        Setting::set('eft_fee', $validated['eft_fee'] ?? 0, 'decimal', 'fees', 'EFT Fee');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Payment fee settings updated successfully!',
        ]);
    }

    /**
     * Update document numbering settings
     */
    public function updateNumbering(Request $request)
    {
        $validated = $request->validate([
            'invoice_prefix' => 'required|string|max:10',
            'invoice_start_number' => 'required|integer|min:1',
            'quote_prefix' => 'required|string|max:10',
            'quote_start_number' => 'required|integer|min:1',
            'job_card_prefix' => 'required|string|max:10',
            'job_card_start_number' => 'required|integer|min:1',
        ]);

        Setting::set('invoice_prefix', $validated['invoice_prefix'], 'string', 'numbering', 'Invoice Prefix');
        Setting::set('invoice_start_number', $validated['invoice_start_number'], 'integer', 'numbering', 'Invoice Start Number');
        Setting::set('quote_prefix', $validated['quote_prefix'], 'string', 'numbering', 'Quote Prefix');
        Setting::set('quote_start_number', $validated['quote_start_number'], 'integer', 'numbering', 'Quote Start Number');
        Setting::set('job_card_prefix', $validated['job_card_prefix'], 'string', 'numbering', 'Job Card Prefix');
        Setting::set('job_card_start_number', $validated['job_card_start_number'], 'integer', 'numbering', 'Job Card Start Number');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Document numbering settings updated successfully!',
        ]);
    }

    /**
     * Update POS settings
     */
    public function updatePos(Request $request)
    {
        $validated = $request->validate([
            'default_price_tier' => 'required|in:normal,online,workshop',
            'allow_out_of_stock_sale' => 'required|boolean',
            'invoice_footer' => 'nullable|string',
            'show_bank_on_quotes' => 'required|boolean',
            'auto_merge_scans' => 'required|boolean',
        ]);

        Setting::set('default_price_tier', $validated['default_price_tier'], 'string', 'pos', 'Default Price Tier');
        Setting::set('allow_out_of_stock_sale', $validated['allow_out_of_stock_sale'], 'boolean', 'pos', 'Allow Out of Stock Sales');
        Setting::set('invoice_footer', $validated['invoice_footer'], 'string', 'pos', 'Invoice Footer');
        Setting::set('show_bank_on_quotes', $validated['show_bank_on_quotes'], 'boolean', 'pos', 'Show Bank on Quotes');
        Setting::set('auto_merge_scans', $validated['auto_merge_scans'], 'boolean', 'pos', 'Auto-merge Scans');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'POS settings updated successfully!',
        ]);
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email_provider' => 'required|string',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'email_from_address' => 'nullable|email',
            'email_from_name' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['smtp_port']) ? 'integer' : 'string';
            Setting::set($key, $value, $type, 'email', ucwords(str_replace('_', ' ', $key)));
        }

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Email settings updated successfully!',
        ]);
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsApp(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_enabled' => 'required|boolean',
            'whatsapp_provider' => 'nullable|string',
            'whatsapp_api_key' => 'nullable|string',
            'whatsapp_phone_number' => 'nullable|string',
        ]);

        Setting::set('whatsapp_enabled', $validated['whatsapp_enabled'], 'boolean', 'whatsapp', 'WhatsApp Enabled');
        Setting::set('whatsapp_provider', $validated['whatsapp_provider'] ?? '', 'string', 'whatsapp', 'Provider');
        Setting::set('whatsapp_api_key', $validated['whatsapp_api_key'] ?? '', 'string', 'whatsapp', 'API Key');
        Setting::set('whatsapp_phone_number', $validated['whatsapp_phone_number'] ?? '', 'string', 'whatsapp', 'Phone Number');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp settings updated successfully!',
        ]);
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'session_timeout' => 'required|integer|min:5|max:1440',
            'force_password_change' => 'required|boolean',
            'password_expiry_days' => 'nullable|integer|min:0',
            'max_login_attempts' => 'required|integer|min:3|max:10',
        ]);

        Setting::set('session_timeout', $validated['session_timeout'], 'integer', 'security', 'Session Timeout');
        Setting::set('force_password_change', $validated['force_password_change'], 'boolean', 'security', 'Force Password Change');
        Setting::set('password_expiry_days', $validated['password_expiry_days'] ?? 90, 'integer', 'security', 'Password Expiry Days');
        Setting::set('max_login_attempts', $validated['max_login_attempts'], 'integer', 'security', 'Max Login Attempts');

        Setting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Security settings updated successfully!',
        ]);
    }

    /**
     * Remove company logo
     */
    public function removeLogo()
    {
        $logo = Setting::get('company_logo');
        
        if ($logo) {
            Storage::disk('public')->delete($logo);
            Setting::set('company_logo', '', 'image', 'company', 'Company Logo');
            Setting::clearCache();
        }

        return response()->json([
            'success' => true,
            'message' => 'Company logo removed successfully!',
        ]);
    }
}

