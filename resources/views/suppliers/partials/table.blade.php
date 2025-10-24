@forelse($suppliers as $supplier)
    <tr class="supplier-row" data-id="{{ $supplier->id }}" style="cursor: pointer;">
        <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
        <td>
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                    <i class="ri-truck-line text-primary"></i>
                </div>
                <div>
                    <div class="fw-semibold">{{ $supplier->name }}</div>
                    @if($supplier->contact_person)
                        <small class="text-muted">{{ $supplier->contact_person }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td>
            <span class="badge bg-{{ $supplier->supplier_type === 'company' ? 'primary' : 'secondary' }}-transparent">
                {{ ucfirst($supplier->supplier_type) }}
            </span>
        </td>
        <td>
            <div>
                @if($supplier->email)
                    <div class="d-flex align-items-center mb-1">
                        <i class="ri-mail-line me-2 text-muted"></i>
                        <small>{{ $supplier->email }}</small>
                    </div>
                @endif
                @if($supplier->phone)
                    <div class="d-flex align-items-center">
                        <i class="ri-phone-line me-2 text-muted"></i>
                        <small>{{ $supplier->phone }}</small>
                    </div>
                @endif
            </div>
        </td>
        <td>
            <span class="badge bg-warning-transparent">{{ $supplier->payment_terms ?? 'N/A' }}</span>
        </td>
        <td>
            @if($supplier->credit_limit > 0)
                <span class="text-success fw-semibold">R{{ number_format($supplier->credit_limit, 2) }}</span>
            @else
                <span class="text-muted">No limit</span>
            @endif
        </td>
        <td>
            <span class="{{ $supplier->balance < 0 ? 'text-danger' : ($supplier->balance > 0 ? 'text-success' : 'text-muted') }} fw-semibold">
                R{{ number_format($supplier->balance, 2) }}
            </span>
            @if($supplier->isOverCreditLimit())
                <br><small class="text-danger">Over limit!</small>
            @endif
        </td>
                                        <td>
                                            @if($supplier->status == 'active')
                                                <span class="badge rounded-pill bg-success-transparent">Active</span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                            @endif
                                        </td>
        <td class="text-end" onclick="event.stopPropagation();">
            <div class="btn-list">
                <!-- Toggle Status -->
                <form method="POST" action="{{ route('suppliers.toggle.status', $supplier->id) }}" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $supplier->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon" title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                        <i class="ri-toggle-{{ $supplier->status === 'active' ? 'line' : 'fill' }}"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon openViewSupplierModal" data-id="{{ $supplier->id }}" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon openEditSupplierModal" data-id="{{ $supplier->id }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon openDeleteSupplierModal" data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-truck-line fs-48 text-muted mb-2"></i>
                <h6>No suppliers found</h6>
                <p class="text-muted mb-0">Start by adding your first supplier</p>
            </div>
        </td>
    </tr>
@endforelse
