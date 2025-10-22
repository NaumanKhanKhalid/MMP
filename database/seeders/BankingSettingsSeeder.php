<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class BankingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Banking Settings
        Setting::set('bank_name', 'First National Bank', 'string', 'banking', 'Bank Name');
        Setting::set('bank_account_name', 'Ubunye Products & Services', 'string', 'banking', 'Account Name');
        Setting::set('bank_account_type', 'Business Cheque Account', 'string', 'banking', 'Account Type');
        Setting::set('bank_account_number', '6310 9803 155', 'string', 'banking', 'Account Number');
        Setting::set('bank_branch_code', '250 655', 'string', 'banking', 'Branch Code');
        Setting::set('bank_reference', 'Your Quotation Number & Name', 'string', 'banking', 'Payment Reference');
        Setting::set('show_bank_on_quotes', true, 'boolean', 'banking', 'Show Banking Details on Quotes');

        $this->command->info('Banking settings seeded successfully!');
    }
}