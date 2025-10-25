<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            ['key' => 'company_name', 'value' => 'MMP Auto-Meister', 'type' => 'string', 'group' => 'company', 'label' => 'Company Name'],
            ['key' => 'company_email', 'value' => 'info@mmpautomeister.co.za', 'type' => 'string', 'group' => 'company', 'label' => 'Company Email'],
            ['key' => 'company_phone', 'value' => '+27 11 123 4567', 'type' => 'string', 'group' => 'company', 'label' => 'Company Phone'],
            ['key' => 'company_address', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Company Address'],
            ['key' => 'company_city', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'City'],
            ['key' => 'company_postal_code', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Postal Code'],
            ['key' => 'company_country', 'value' => 'South Africa', 'type' => 'string', 'group' => 'company', 'label' => 'Country'],
            ['key' => 'company_registration', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Registration Number'],
            ['key' => 'company_vat_number', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'VAT Number'],
            ['key' => 'company_logo', 'value' => '', 'type' => 'image', 'group' => 'company', 'label' => 'Company Logo'],
            ['key' => 'bank_name', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Bank Name'],
            ['key' => 'bank_account_name', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Account Name'],
            ['key' => 'bank_account_number', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Account Number'],
            ['key' => 'bank_branch_code', 'value' => '', 'type' => 'string', 'group' => 'company', 'label' => 'Branch Code'],
            
            // VAT Settings
            ['key' => 'vat_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'vat', 'label' => 'VAT Enabled'],
            ['key' => 'vat_rate', 'value' => '15.00', 'type' => 'decimal', 'group' => 'vat', 'label' => 'VAT Rate (%)'],
            ['key' => 'vat_inclusive', 'value' => 'false', 'type' => 'boolean', 'group' => 'vat', 'label' => 'VAT Inclusive'],
            
            // Payment Fees
            ['key' => 'card_fee_percentage', 'value' => '2.5', 'type' => 'decimal', 'group' => 'fees', 'label' => 'Card Fee (%)'],
            ['key' => 'cash_deposit_fee', 'value' => '1.5', 'type' => 'decimal', 'group' => 'fees', 'label' => 'Cash Deposit Fee (per R100)'],
            ['key' => 'eft_fee', 'value' => '0', 'type' => 'decimal', 'group' => 'fees', 'label' => 'EFT Fee'],
            
            // Document Numbering
            ['key' => 'invoice_prefix', 'value' => 'MMP', 'type' => 'string', 'group' => 'numbering', 'label' => 'Invoice Prefix'],
            ['key' => 'invoice_start_number', 'value' => '10000', 'type' => 'integer', 'group' => 'numbering', 'label' => 'Invoice Start Number'],
            ['key' => 'quote_prefix', 'value' => 'QT', 'type' => 'string', 'group' => 'numbering', 'label' => 'Quote Prefix'],
            ['key' => 'quote_start_number', 'value' => '10000', 'type' => 'integer', 'group' => 'numbering', 'label' => 'Quote Start Number'],
            ['key' => 'job_card_prefix', 'value' => 'WS', 'type' => 'string', 'group' => 'numbering', 'label' => 'Job Card Prefix'],
            ['key' => 'job_card_start_number', 'value' => '10000', 'type' => 'integer', 'group' => 'numbering', 'label' => 'Job Card Start Number'],
            
            // POS Settings
            ['key' => 'default_price_tier', 'value' => 'normal', 'type' => 'string', 'group' => 'pos', 'label' => 'Default Price Tier'],
            ['key' => 'allow_out_of_stock_sale', 'value' => 'true', 'type' => 'boolean', 'group' => 'pos', 'label' => 'Allow Out of Stock Sales'],
            ['key' => 'invoice_footer', 'value' => 'Thank you for your business!', 'type' => 'string', 'group' => 'pos', 'label' => 'Invoice Footer'],
            ['key' => 'show_bank_on_quotes', 'value' => 'false', 'type' => 'boolean', 'group' => 'pos', 'label' => 'Show Bank Details on Quotes'],
            ['key' => 'auto_merge_scans', 'value' => 'true', 'type' => 'boolean', 'group' => 'pos', 'label' => 'Auto-merge Duplicate Scans'],
            ['key' => 'discount_type', 'value' => 'flat', 'type' => 'string', 'group' => 'pos', 'label' => 'Discount Type'],
            ['key' => 'admin_max_discount', 'value' => '100', 'type' => 'decimal', 'group' => 'pos', 'label' => 'Admin Max Discount (%)'],
            ['key' => 'manager_max_discount', 'value' => '25', 'type' => 'decimal', 'group' => 'pos', 'label' => 'Manager Max Discount (%)'],
            ['key' => 'staff_max_discount', 'value' => '10', 'type' => 'decimal', 'group' => 'pos', 'label' => 'Staff Max Discount (%)'],
            
            // Email Settings
            ['key' => 'email_provider', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'label' => 'Email Provider'],
            ['key' => 'smtp_host', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'SMTP Host'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'integer', 'group' => 'email', 'label' => 'SMTP Port'],
            ['key' => 'smtp_username', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'SMTP Username'],
            ['key' => 'smtp_password', 'value' => '', 'type' => 'string', 'group' => 'email', 'label' => 'SMTP Password'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email', 'label' => 'SMTP Encryption'],
            ['key' => 'email_from_address', 'value' => 'noreply@mmpautomeister.co.za', 'type' => 'string', 'group' => 'email', 'label' => 'From Email'],
            ['key' => 'email_from_name', 'value' => 'MMP Auto-Meister', 'type' => 'string', 'group' => 'email', 'label' => 'From Name'],
            
            // WhatsApp Settings
            ['key' => 'whatsapp_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'whatsapp', 'label' => 'WhatsApp Enabled'],
            ['key' => 'whatsapp_share_type', 'value' => 'web', 'type' => 'string', 'group' => 'whatsapp', 'label' => 'WhatsApp Share Type'],
            ['key' => 'whatsapp_provider', 'value' => '', 'type' => 'string', 'group' => 'whatsapp', 'label' => 'WhatsApp Provider'],
            ['key' => 'whatsapp_api_key', 'value' => '', 'type' => 'string', 'group' => 'whatsapp', 'label' => 'API Key'],
            ['key' => 'whatsapp_phone_number', 'value' => '', 'type' => 'string', 'group' => 'whatsapp', 'label' => 'WhatsApp Number'],
            
            // Security Settings
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'integer', 'group' => 'security', 'label' => 'Session Timeout (minutes)'],
            ['key' => 'force_password_change', 'value' => 'true', 'type' => 'boolean', 'group' => 'security', 'label' => 'Force Password Change on First Login'],
            ['key' => 'password_expiry_days', 'value' => '90', 'type' => 'integer', 'group' => 'security', 'label' => 'Password Expiry (days)'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Max Login Attempts'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

