<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
