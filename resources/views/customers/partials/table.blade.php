@forelse ($customers as $customer)
    <tr class="clickable-row" style="cursor: pointer;" data-id="{{ $customer->id }}">
        <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
        <td>
            <span class="badge bg-info-transparent">{{ $customer->customer_code }}</span>
        </td>
        <td>
            <div>
                <strong>{{ $customer->display_name }}</strong>
                @if($customer->is_walk_in)
                    <span class="badge bg-info-transparent ms-1" title="Walk-in Customer">
                        <i class="ri-walk-line me-1"></i>Walk-in
                    </span>
                @endif
                @if($customer->isBusiness() && $customer->company_name)
                    <br><small class="text-muted">{{ $customer->name }}</small>
                @endif
            </div>
        </td>
        <td>
            <span class="badge bg-{{ $customer->customer_category === 'business' ? 'primary' : 'secondary' }}-transparent">
                {{ ucfirst($customer->customer_category) }}
            </span>
            <br>
                {{-- <small class="text-muted">
                    <span class="badge bg-{{ $customer->customer_type === 'credit' ? 'warning' : 'success' }}-transparent">
                        {{ ucfirst($customer->customer_type) }}
                    </span>
                </small> --}}
        </td>
        <td>
            <div>
                @if($customer->email)
                    <span class="d-block mb-1"><i class="ri-mail-line me-2 align-middle fs-14 text-muted"></i>{{ $customer->email }}</span>
                @endif
                @if($customer->phone)
                    <span class="d-block"><i class="ri-phone-line me-2 align-middle fs-14 text-muted"></i>{{ $customer->phone }}</span>
                @endif
                @if($customer->contact_person)
                    <small class="text-muted">{{ $customer->contact_person }}</small>
                @endif
            </div>
        </td>
        <td>
            <span class="badge bg-{{ $customer->terms === 'credit' ? 'warning' : 'success' }}-transparent">
                {{ ucfirst(str_replace('_', ' ', $customer->terms)) }}
            </span>
        </td>
        <td>
            @if($customer->credit_limit > 0)
                <span class="text-success">R{{ number_format($customer->credit_limit, 2) }}</span>
            @else
                <span class="text-muted">No limit</span>
            @endif
        </td>
        <td>
            <span class="{{ $customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted') }}">
                R{{ number_format($customer->balance, 2) }}
            </span>
            @if($customer->isOverCreditLimit())
                <br><small class="text-danger">Over limit!</small>
            @endif
        </td>
        <td>
            @if($customer->customer_status === 'active')
                <span class="badge rounded-pill bg-success-transparent">Active</span>
            @elseif($customer->customer_status === 'inactive')
                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
            @elseif($customer->customer_status === 'suspended')
                <span class="badge rounded-pill bg-danger-transparent">Suspended</span>
            @endif
        </td>
        <td class="text-end">
            <div class="btn-list">
                <!-- Toggle Status -->
                <form action="{{ route('customers.toggle-status', $customer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $customer->customer_status === 'active' ? 'btn-warning-light' : ($customer->customer_status === 'inactive' ? 'btn-success-light' : 'btn-danger-light') }} btn-icon" title="{{ $customer->customer_status === 'active' ? 'Deactivate' : ($customer->customer_status === 'inactive' ? 'Activate' : 'Unsuspend') }}">
                        <i class="ri-toggle-{{ $customer->customer_status === 'active' ? 'line' : 'fill' }}"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon openViewCustomerModal" data-id="{{ $customer->id }}" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon openEditCustomerModal" data-id="{{ $customer->id }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon openDeleteCustomerModal" data-id="{{ $customer->id }}" data-name="{{ $customer->display_name }}" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-user-unfollow-line fs-48 mb-2"></i>
                <p class="mb-0">No customers found</p>
            </div>
        </td>
    </tr>
@endforelse


