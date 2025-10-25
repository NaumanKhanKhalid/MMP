<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        // Use cache to avoid repeated database queries
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $group = null, $label = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
                'label' => $label,
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup($group)
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            $settings = self::where('group', $group)->get();
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $result;
        });
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::flush();
    }

    /**
     * Convenience methods for common settings
     */
    
    // Company Settings
    public static function companyName()
    {
        return self::get('company_name', 'MMP Auto-Meister');
    }

    public static function companyEmail()
    {
        return self::get('company_email');
    }

    public static function companyPhone()
    {
        return self::get('company_phone');
    }

    public static function companyAddress()
    {
        return self::get('company_address');
    }

    public static function companyLogo()
    {
        return self::get('company_logo');
    }

    // VAT Settings
    public static function vatEnabled()
    {
        return self::get('vat_enabled', false);
    }

    public static function vatRate()
    {
        return self::get('vat_rate', 15.00);
    }

    public static function vatInclusive()
    {
        return self::get('vat_inclusive', false);
    }

    // Payment Fees
    public static function cardFeePercentage()
    {
        return self::get('card_fee_percentage', 2.5);
    }

    public static function cashDepositFee()
    {
        return self::get('cash_deposit_fee', 1.5); // Per R100
    }

    // Document Numbering
    public static function invoicePrefix()
    {
        return self::get('invoice_prefix', 'MMP');
    }

    public static function invoiceStartNumber()
    {
        return self::get('invoice_start_number', 10000);
    }

    public static function quotePrefix()
    {
        return self::get('quote_prefix', 'QT');
    }

    public static function quoteStartNumber()
    {
        return self::get('quote_start_number', 10000);
    }

    public static function jobCardPrefix()
    {
        return self::get('job_card_prefix', 'WS');
    }

    public static function jobCardStartNumber()
    {
        return self::get('job_card_start_number', 10000);
    }

    // POS Settings
    public static function defaultPriceTier()
    {
        return self::get('default_price_tier', 'normal');
    }

    public static function allowOutOfStockSale()
    {
        return self::get('allow_out_of_stock_sale', true);
    }

    public static function invoiceFooter()
    {
        return self::get('invoice_footer', 'Thank you for your business!');
    }

    public static function showBankOnQuotes()
    {
        return self::get('show_bank_on_quotes', false);
    }

    public static function showBankOnInvoices()
    {
        return self::get('show_bank_on_invoices', false);
    }

    // Discount Settings
    public static function discountType()
    {
        return self::get('discount_type', 'flat');
    }

    public static function adminMaxDiscount()
    {
        return self::get('admin_max_discount', 100);
    }

    public static function managerMaxDiscount()
    {
        return self::get('manager_max_discount', 25);
    }

    public static function staffMaxDiscount()
    {
        return self::get('staff_max_discount', 10);
    }

    // Banking Settings
    public static function bankName()
    {
        return self::get('bank_name', 'First National Bank');
    }

    public static function bankAccountName()
    {
        return self::get('bank_account_name', 'Ubunye Products & Services');
    }

    public static function bankAccountType()
    {
        return self::get('bank_account_type', 'Business Cheque Account');
    }

    public static function bankAccountNumber()
    {
        return self::get('bank_account_number', '6310 9803 155');
    }

    public static function bankBranchCode()
    {
        return self::get('bank_branch_code', '250 655');
    }

    public static function bankReference()
    {
        return self::get('bank_reference', 'Your Quotation Number & Name');
    }

    // Security Settings
    public static function sessionTimeout()
    {
        return self::get('session_timeout', 120); // minutes
    }

    public static function twoFactorEnabled()
    {
        return self::get('two_factor_enabled', false);
    }
}
