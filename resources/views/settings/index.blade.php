@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">System Settings</h4>
            <p class="fs-13 text-muted mb-0">Configure your MMP Auto-Meister system</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#company-tab">
                                <i class="ri-building-line me-2"></i> Company
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#vat-tab">
                                <i class="ri-percent-line me-2"></i> VAT
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#fees-tab">
                                <i class="ri-money-dollar-circle-line me-2"></i> Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#numbering-tab">
                                <i class="ri-hashtag me-2"></i> Numbering
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pos-tab">
                                <i class="ri-store-2-line me-2"></i> POS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#email-tab">
                                <i class="ri-mail-line me-2"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#security-tab">
                                <i class="ri-shield-check-line me-2"></i> Security
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Company Settings Tab -->
                        <div class="tab-pane fade show active" id="company-tab">
                            <form id="companyForm" enctype="multipart/form-data">
                                @csrf
                                <h5 class="mb-4">Company Information</h5>
                                
                                <div class="row g-3">
                                    <!-- Company Logo -->
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-semibold">Company Logo</label>
                                        <div class="d-flex align-items-start gap-3">
                                            <div>
                                                @if(\App\Models\Setting::get('company_logo'))
                                                    <img src="{{ asset(\App\Models\Setting::get('company_logo')) }}" 
                                                         alt="Company Logo" 
                                                         class="rounded border" 
                                                         style="max-height: 100px;" 
                                                         id="logoPreview">
                                                @else
                                                    <div class="border rounded p-4 text-center" style="width: 200px;" id="logoPreview">
                                                        <i class="ri-image-line fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0 small">No logo uploaded</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <input type="file" name="company_logo" class="form-control mb-2" accept="image/*" id="logoInput">
                                                <small class="text-muted">Max 2MB. Recommended: 300x100px PNG with transparent background</small>
                                                @if(\App\Models\Setting::get('company_logo'))
                                                    <br><button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeLogo()">
                                                        <i class="ri-delete-bin-line me-1"></i> Remove Logo
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Info -->
                                    <div class="col-md-6">
                                        <label class="form-label">Company Name *</label>
                                        <input type="text" name="company_name" class="form-control" 
                                               value="{{ $companySettings['company_name'] ?? 'MMP Auto-Meister' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="company_email" class="form-control" 
                                               value="{{ $companySettings['company_email'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="company_phone" class="form-control" 
                                               value="{{ $companySettings['company_phone'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Registration Number</label>
                                        <input type="text" name="company_registration" class="form-control" 
                                               value="{{ $companySettings['company_registration'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">VAT Number</label>
                                        <input type="text" name="company_vat_number" class="form-control" 
                                               value="{{ $companySettings['company_vat_number'] ?? '' }}">
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="company_address" class="form-control" rows="2">{{ $companySettings['company_address'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">City</label>
                                        <input type="text" name="company_city" class="form-control" 
                                               value="{{ $companySettings['company_city'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="company_postal_code" class="form-control" 
                                               value="{{ $companySettings['company_postal_code'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="company_country" class="form-control" 
                                               value="{{ $companySettings['company_country'] ?? 'South Africa' }}">
                                    </div>

                                    <!-- Bank Details -->
                                    <div class="col-md-12 mt-4">
                                        <h6 class="border-bottom pb-2">Bank Details</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" 
                                               value="{{ $companySettings['bank_name'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Account Name</label>
                                        <input type="text" name="bank_account_name" class="form-control" 
                                               value="{{ $companySettings['bank_account_name'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Account Number</label>
                                        <input type="text" name="bank_account_number" class="form-control" 
                                               value="{{ $companySettings['bank_account_number'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Branch Code</label>
                                        <input type="text" name="bank_branch_code" class="form-control" 
                                               value="{{ $companySettings['bank_branch_code'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Account Type</label>
                                        <input type="text" name="bank_account_type" class="form-control" 
                                               value="{{ $companySettings['bank_account_type'] ?? '' }}"
                                               placeholder="e.g., Business Cheque Account">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Payment Reference</label>
                                        <input type="text" name="bank_reference" class="form-control" 
                                               value="{{ $companySettings['bank_reference'] ?? '' }}"
                                               placeholder="e.g., Your Quotation Number & Name">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_bank_on_quotes" 
                                                   id="showBankOnQuotes" value="1" 
                                                   {{ ($companySettings['show_bank_on_quotes'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="showBankOnQuotes">
                                                Show Banking Details on Quotes
                                            </label>
                                        </div>
                                        <small class="text-muted">When enabled, banking details will be displayed on printed quotations</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_bank_on_invoices" 
                                                   id="showBankOnInvoices" value="1" 
                                                   {{ ($companySettings['show_bank_on_invoices'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="showBankOnInvoices">
                                                Show Banking Details on Invoices
                                            </label>
                                        </div>
                                        <small class="text-muted">When enabled, banking details will be displayed on printed invoices</small>
                                    </div>
                                </div>

                                <!-- Terms & Conditions PDF -->
                                <div class="col-md-12 mt-4">
                                    <h6 class="border-bottom pb-2">Terms & Conditions PDF</h6>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Terms & Conditions PDF</label>
                                    <div class="d-flex align-items-start gap-3">
                                        <div>
                                            @if(\App\Models\Setting::get('terms_pdf'))
                                                <div class="border rounded p-3 text-center" style="width: 200px;">
                                                    <i class="ri-file-pdf-line fs-1 text-danger"></i>
                                                    <p class="text-muted mb-0 small">Terms PDF uploaded</p>
                                                    <a href="{{ asset(\App\Models\Setting::get('terms_pdf')) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                        <i class="ri-eye-line me-1"></i> View PDF
                                                    </a>
                                                </div>
                                            @else
                                                <div class="border rounded p-4 text-center" style="width: 200px;">
                                                    <i class="ri-file-pdf-line fs-1 text-muted"></i>
                                                    <p class="text-muted mb-0 small">No PDF uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <input type="file" name="terms_pdf" class="form-control mb-2" accept=".pdf" id="termsPdfInput">
                                            <small class="text-muted">Upload Terms & Conditions PDF (max 5MB)</small>
                                            @if(\App\Models\Setting::get('terms_pdf'))
                                                <br><button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeTermsPdf()">
                                                    <i class="ri-delete-bin-line me-1"></i> Remove PDF
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save Company Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- VAT Settings Tab -->
                        <div class="tab-pane fade" id="vat-tab">
                            <form id="vatForm">
                                @csrf
                                <h5 class="mb-4">VAT Configuration</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="vat_enabled" 
                                                   id="vatEnabled" value="1" 
                                                   {{ ($vatSettings['vat_enabled'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="vatEnabled">
                                                Enable VAT
                                            </label>
                                        </div>
                                        <small class="text-muted">When enabled, VAT will be calculated on all sales</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">VAT Rate (%)</label>
                                        <input type="number" name="vat_rate" class="form-control" 
                                               value="{{ $vatSettings['vat_rate'] ?? 15.00 }}" 
                                               step="0.01" min="0" max="100">
                                        <small class="text-muted">Standard VAT rate (usually 15% in South Africa)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">VAT Type</label>
                                        <select name="vat_inclusive" class="form-select">
                                            <option value="0" {{ !($vatSettings['vat_inclusive'] ?? false) ? 'selected' : '' }}>
                                                VAT Exclusive (Add VAT on top of price)
                                            </option>
                                            <option value="1" {{ ($vatSettings['vat_inclusive'] ?? false) ? 'selected' : '' }}>
                                                VAT Inclusive (Price includes VAT)
                                            </option>
                                        </select>
                                        <small class="text-muted">
                                            <strong>Exclusive:</strong> If price = R100, total = R100 + R15 VAT = R115<br>
                                            <strong>Inclusive:</strong> If price = R100, VAT = R13.04 (already included), total = R100
                                        </small>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save VAT Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Fees Settings Tab -->
                        <div class="tab-pane fade" id="fees-tab">
                            <form id="feesForm">
                                @csrf
                                <h5 class="mb-4">Payment Fees Configuration</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Card Fee (%)</label>
                                        <input type="number" name="card_fee_percentage" class="form-control" 
                                               value="{{ $feeSettings['card_fee_percentage'] ?? 2.5 }}" 
                                               step="0.01" min="0" max="100">
                                        <small class="text-muted">Percentage fee charged by card processor</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Cash Deposit Fee (per R100)</label>
                                        <input type="number" name="cash_deposit_fee" class="form-control" 
                                               value="{{ $feeSettings['cash_deposit_fee'] ?? 1.5 }}" 
                                               step="0.01" min="0">
                                        <small class="text-muted">Bank fee for cash deposits (e.g., R1.50 per R100)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">EFT Fee (flat amount)</label>
                                        <input type="number" name="eft_fee" class="form-control" 
                                               value="{{ $feeSettings['eft_fee'] ?? 0 }}" 
                                               step="0.01" min="0">
                                        <small class="text-muted">Fixed fee for EFT transactions (usually R0)</small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <h6><i class="ri-information-line me-2"></i> How Fees Work</h6>
                                            <ul class="mb-0">
                                                <li><strong>Card:</strong> Gross Amount × (Fee % / 100) = Fee</li>
                                                <li><strong>Cash:</strong> (Gross Amount / 100) × Fee Rate = Fee</li>
                                                <li><strong>EFT:</strong> Flat fee (if any)</li>
                                            </ul>
                                            <p class="mb-0 mt-2"><strong>Net Amount = Gross Amount - Fee</strong></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save Fee Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Document Numbering Tab -->
                        <div class="tab-pane fade" id="numbering-tab">
                            <form id="numberingForm">
                                @csrf
                                <h5 class="mb-4">Document Numbering</h5>
                                
                                <div class="alert alert-warning">
                                    <i class="ri-alert-line me-2"></i> <strong>Warning:</strong> Changing these settings will only affect NEW documents. Existing documents will keep their current numbers.
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Invoice Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control" 
                                               value="{{ $numberingSettings['invoice_prefix'] ?? 'MMP' }}" maxlength="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Invoice Start Number</label>
                                        <input type="number" name="invoice_start_number" class="form-control" 
                                               value="{{ $numberingSettings['invoice_start_number'] ?? 10000 }}" min="1">
                                        <small class="text-muted">Example: MMP10000, MMP10001...</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Quote Prefix</label>
                                        <input type="text" name="quote_prefix" class="form-control" 
                                               value="{{ $numberingSettings['quote_prefix'] ?? 'QT' }}" maxlength="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Quote Start Number</label>
                                        <input type="number" name="quote_start_number" class="form-control" 
                                               value="{{ $numberingSettings['quote_start_number'] ?? 10000 }}" min="1">
                                        <small class="text-muted">Example: QT10000, QT10001...</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Job Card Prefix</label>
                                        <input type="text" name="job_card_prefix" class="form-control" 
                                               value="{{ $numberingSettings['job_card_prefix'] ?? 'WS' }}" maxlength="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Job Card Start Number</label>
                                        <input type="number" name="job_card_start_number" class="form-control" 
                                               value="{{ $numberingSettings['job_card_start_number'] ?? 10000 }}" min="1">
                                        <small class="text-muted">Example: WS10000, WS10001...</small>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save Numbering Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- POS Settings Tab -->
                        <div class="tab-pane fade" id="pos-tab">
                            <form id="posForm">
                                @csrf
                                <h5 class="mb-4">Point of Sale Settings</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Default Price Tier</label>
                                        <select name="default_price_tier" class="form-select">
                                            <option value="normal" {{ ($posSettings['default_price_tier'] ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="online" {{ ($posSettings['default_price_tier'] ?? 'normal') === 'online' ? 'selected' : '' }}>Online</option>
                                            <option value="workshop" {{ ($posSettings['default_price_tier'] ?? 'normal') === 'workshop' ? 'selected' : '' }}>Workshop</option>
                                        </select>
                                        <small class="text-muted">Default price tier for cash sales</small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="allow_out_of_stock_sale" 
                                                   id="allowOutOfStock" value="1" 
                                                   {{ ($posSettings['allow_out_of_stock_sale'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allowOutOfStock">
                                                Allow Out of Stock Sales
                                            </label>
                                        </div>
                                        <small class="text-muted">Permit sales even when product stock is zero or negative</small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="auto_merge_scans" 
                                                   id="autoMergeScans" value="1" 
                                                   {{ ($posSettings['auto_merge_scans'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="autoMergeScans">
                                                Auto-merge Duplicate Scans
                                            </label>
                                        </div>
                                        <small class="text-muted">Automatically increase quantity when same barcode is scanned multiple times</small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_bank_on_quotes" 
                                                   id="showBankOnQuotes" value="1" 
                                                   {{ ($posSettings['show_bank_on_quotes'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="showBankOnQuotes">
                                                Show Bank Details on Quotes
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Discount Settings -->
                                    <div class="col-md-12 mt-4">
                                        <h6 class="border-bottom pb-2">
                                            <i class="ri-percent-line me-2"></i>Discount Settings
                                        </h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Discount Type</label>
                                        <select name="discount_type" class="form-select">
                                            <option value="flat" {{ ($posSettings['discount_type'] ?? 'flat') === 'flat' ? 'selected' : '' }}>Flat Amount (R)</option>
                                            <option value="percentage" {{ ($posSettings['discount_type'] ?? 'flat') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        </select>
                                        <small class="text-muted">How discounts will be calculated in POS</small>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <h6 class="text-muted small">Role-Based Discount Limits (%)</h6>
                                        <p class="text-muted small mb-3">Maximum discount percentage each role can apply</p>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Admin Maximum Discount (%)</label>
                                        <input type="number" name="admin_max_discount" class="form-control" 
                                               value="{{ $posSettings['admin_max_discount'] ?? 100 }}" min="0" max="100" step="0.01">
                                        <small class="text-muted">Default: 100% (unlimited)</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Manager Maximum Discount (%)</label>
                                        <input type="number" name="manager_max_discount" class="form-control" 
                                               value="{{ $posSettings['manager_max_discount'] ?? 25 }}" min="0" max="100" step="0.01">
                                        <small class="text-muted">Default: 25%</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Staff Maximum Discount (%)</label>
                                        <input type="number" name="staff_max_discount" class="form-control" 
                                               value="{{ $posSettings['staff_max_discount'] ?? 10 }}" min="0" max="100" step="0.01">
                                        <small class="text-muted">Default: 10%</small>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Invoice Footer Text</label>
                                        <textarea name="invoice_footer" class="form-control" rows="3">{{ $posSettings['invoice_footer'] ?? 'Thank you for your business!' }}</textarea>
                                        <small class="text-muted">Text shown at the bottom of invoices and quotes</small>
                                    </div>

                                    <!-- WhatsApp Settings -->
                                    <div class="col-md-12 mt-4">
                                        <h6 class="border-bottom pb-2">
                                            <i class="ri-whatsapp-line me-2 text-success"></i>WhatsApp Share Settings
                                        </h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">WhatsApp Share Type</label>
                                        <select name="whatsapp_share_type" class="form-select">
                                            <option value="web" {{ ($posSettings['whatsapp_share_type'] ?? 'web') === 'web' ? 'selected' : '' }}>
                                                WhatsApp Web (Browser) - Recommended
                                            </option>
                                            <option value="desktop" {{ ($posSettings['whatsapp_share_type'] ?? 'web') === 'desktop' ? 'selected' : '' }}>
                                                WhatsApp Desktop Application
                                            </option>
                                        </select>
                                        <small class="text-muted">
                                            <strong>Web:</strong> Opens in browser, message pre-filled reliably<br>
                                            <strong>Desktop:</strong> Opens desktop app, may not pre-fill message
                                        </small>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save POS Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Email Settings Tab -->
                        <div class="tab-pane fade" id="email-tab">
                            <form id="emailForm">
                                @csrf
                                <h5 class="mb-4">Email Configuration</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email Provider</label>
                                        <select name="email_provider" class="form-select">
                                            <option value="smtp" {{ ($emailSettings['email_provider'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="sendgrid" {{ ($emailSettings['email_provider'] ?? 'smtp') === 'sendgrid' ? 'selected' : '' }}>SendGrid</option>
                                            <option value="mailgun" {{ ($emailSettings['email_provider'] ?? 'smtp') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <h6 class="border-bottom pb-2">SMTP Configuration</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Host</label>
                                        <input type="text" name="smtp_host" class="form-control" 
                                               value="{{ $emailSettings['smtp_host'] ?? '' }}" placeholder="smtp.gmail.com">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="number" name="smtp_port" class="form-control" 
                                               value="{{ $emailSettings['smtp_port'] ?? 587 }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Encryption</label>
                                        <select name="smtp_encryption" class="form-select">
                                            <option value="tls" {{ ($emailSettings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ ($emailSettings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="none" {{ ($emailSettings['smtp_encryption'] ?? 'tls') === 'none' ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" name="smtp_username" class="form-control" 
                                               value="{{ $emailSettings['smtp_username'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="password" name="smtp_password" class="form-control" 
                                               value="{{ $emailSettings['smtp_password'] ?? '' }}" autocomplete="new-password">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <h6 class="border-bottom pb-2">From Address</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">From Email</label>
                                        <input type="email" name="email_from_address" class="form-control" 
                                               value="{{ $emailSettings['email_from_address'] ?? 'noreply@mmpautomeister.co.za' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">From Name</label>
                                        <input type="text" name="email_from_name" class="form-control" 
                                               value="{{ $emailSettings['email_from_name'] ?? 'MMP Auto-Meister' }}">
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save Email Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security Settings Tab -->
                        <div class="tab-pane fade" id="security-tab">
                            <form id="securityForm">
                                @csrf
                                <h5 class="mb-4">Security Configuration</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Session Timeout (minutes)</label>
                                        <input type="number" name="session_timeout" class="form-control" 
                                               value="{{ $securitySettings['session_timeout'] ?? 120 }}" min="5" max="1440">
                                        <small class="text-muted">Auto logout after inactivity (5-1440 minutes)</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Max Login Attempts</label>
                                        <input type="number" name="max_login_attempts" class="form-control" 
                                               value="{{ $securitySettings['max_login_attempts'] ?? 5 }}" min="3" max="10">
                                        <small class="text-muted">Lock account after failed attempts</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password Expiry (days)</label>
                                        <input type="number" name="password_expiry_days" class="form-control" 
                                               value="{{ $securitySettings['password_expiry_days'] ?? 90 }}" min="0">
                                        <small class="text-muted">Force password change after X days (0 = never)</small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="force_password_change" 
                                                   id="forcePasswordChange" value="1" 
                                                   {{ ($securitySettings['force_password_change'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="forcePasswordChange">
                                                Force Password Change on First Login
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Save Security Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Company Form
document.getElementById('companyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm(this, '{{ route('settings.update-company') }}', 'Company');
});

// VAT Form
document.getElementById('vatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('vat_enabled', document.getElementById('vatEnabled').checked ? 1 : 0);
    formData.append('vat_rate', this.vat_rate.value);
    formData.append('vat_inclusive', this.vat_inclusive.value); // Now it's a dropdown, not checkbox
    
    submitFormData(formData, '{{ route('settings.update-vat') }}', 'VAT');
});

// Fees Form
document.getElementById('feesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm(this, '{{ route('settings.update-fees') }}', 'Fee');
});

// Numbering Form
document.getElementById('numberingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm(this, '{{ route('settings.update-numbering') }}', 'Numbering');
});

// POS Form
document.getElementById('posForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('default_price_tier', this.default_price_tier.value);
    formData.append('allow_out_of_stock_sale', document.getElementById('allowOutOfStock').checked ? 1 : 0);
    formData.append('auto_merge_scans', document.getElementById('autoMergeScans').checked ? 1 : 0);
    formData.append('show_bank_on_quotes', document.getElementById('showBankOnQuotes').checked ? 1 : 0);
    formData.append('invoice_footer', this.invoice_footer.value);
    formData.append('discount_type', this.discount_type.value);
    formData.append('admin_max_discount', this.admin_max_discount.value);
    formData.append('manager_max_discount', this.manager_max_discount.value);
    formData.append('staff_max_discount', this.staff_max_discount.value);
    formData.append('whatsapp_share_type', this.whatsapp_share_type.value);
    
    submitFormData(formData, '{{ route('settings.update-pos') }}', 'POS');
});

// Email Form
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm(this, '{{ route('settings.update-email') }}', 'Email');
});

// Security Form
document.getElementById('securityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('session_timeout', this.session_timeout.value);
    formData.append('max_login_attempts', this.max_login_attempts.value);
    formData.append('password_expiry_days', this.password_expiry_days.value);
    formData.append('force_password_change', document.getElementById('forcePasswordChange').checked ? 1 : 0);
    
    submitFormData(formData, '{{ route('settings.update-security') }}', 'Security');
});

// Helper functions
function submitForm(form, url, name) {
    const formData = new FormData(form);
    submitFormData(formData, url, name);
}

function submitFormData(formData, url, name) {
    const submitBtn = event.submitter;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Error updating settings');
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Save ' + name + ' Settings';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating settings');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Save ' + name + ' Settings';
    });
}

// Logo preview
document.getElementById('logoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').innerHTML = 
                `<img src="${e.target.result}" class="rounded border" style="max-height: 100px;">`;
        };
        reader.readAsDataURL(file);
    }
});

function removeLogo() {
    if (!confirm('Are you sure you want to remove the company logo?')) {
        return;
    }

    fetch('{{ route('settings.remove-logo') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error removing logo');
        }
    });
}
</script>
@endpush