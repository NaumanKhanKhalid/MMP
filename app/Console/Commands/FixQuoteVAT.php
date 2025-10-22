<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;

class FixQuoteVAT extends Command
{
    protected $signature = 'fix:quote-vat {quote_number=QT10015}';
    protected $description = 'Fix VAT calculation for a specific quote';

    public function handle()
    {
        $quoteNumber = $this->argument('quote_number');
        
        $quote = Quote::where('quote_number', $quoteNumber)->first();
        
        if (!$quote) {
            $this->error("Quote {$quoteNumber} not found!");
            return 1;
        }
        
        // Calculate correct VAT
        $subtotal = $quote->items->sum('total');
        $vatAmount = $subtotal * 0.15; // 15% VAT
        $grandTotal = $subtotal + $vatAmount;
        
        // Update the quote
        $quote->update([
            'vat' => $vatAmount,
            'grand_total' => $grandTotal
        ]);
        
        $this->info("Quote {$quoteNumber} VAT calculation fixed!");
        $this->line("Subtotal: R " . number_format($subtotal, 2));
        $this->line("VAT (15%): R " . number_format($vatAmount, 2));
        $this->line("Grand Total: R " . number_format($grandTotal, 2));
        
        return 0;
    }
}