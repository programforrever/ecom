<!-- Sales List Content -->
<div class="table-responsive c-scrollbar-light" style="max-height: 600px; overflow-y: auto; overflow-x: auto;">
    <table class="table table-hover fs-14 pos-sales-table">
        <thead class="sticky-top bg-white">
            <tr>
                <th class="text-center" style="min-width: 80px;">{{translate('Código')}}</th>
                <th style="min-width: 120px;">{{translate('Cliente')}}</th>
                <th class="text-right" style="min-width: 100px;">{{translate('Total')}}</th>
                <th class="text-center" style="min-width: 110px;">{{translate('Estado')}}</th>
                <th class="text-center" style="min-width: 130px;">{{translate('Fecha')}}</th>
                <th class="text-center sticky-col" style="width: 60px; position: sticky; right: 0; z-index: 10;">{{translate('Imprimir')}}</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($orders) && $orders->count() > 0)
                @forelse($orders as $order)
                    <tr class="hover-row" style="cursor: pointer;">
                        <td class="text-center">
                            <strong>{{ $order->code ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            @if($order->user)
                                <span class="badge badge-soft-info">{{ $order->user->name ?? 'Guest' }}</span>
                            @else
                                <span class="badge badge-soft-light">{{ translate('Walk-in Customer') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <strong class="text-accent" style="color: #f97316;">{{ single_price($order->grand_total ?? 0) }}</strong>
                        </td>
                        <td class="text-center">
                            @php
                                $status = $order->delivery_status ?? 'pending';
                            @endphp
                            @if($status == 'pending')
                                <span class="badge badge-soft-warning">{{ translate('Pending') }}</span>
                            @elseif($status == 'confirmed')
                                <span class="badge badge-soft-info">{{ translate('Confirmed') }}</span>
                            @elseif($status == 'processing')
                                <span class="badge badge-soft-primary">{{ translate('Processing') }}</span>
                            @elseif($status == 'shipped')
                                <span class="badge badge-soft-secondary">{{ translate('Shipped') }}</span>
                            @elseif($status == 'delivered')
                                <span class="badge badge-soft-success">{{ translate('Delivered') }}</span>
                            @elseif($status == 'cancelled')
                                <span class="badge badge-soft-danger">{{ translate('Cancelled') }}</span>
                            @else
                                <span class="badge badge-soft-secondary">{{ $status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                        </td>
                        <td class="text-center sticky-col" style="position: sticky; right: 0; z-index: 9; background-color: #fff;">
                            <a href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank" class="btn btn-sm btn-soft-primary print-btn" title="{{translate('Print')}}">
                                <i class="las la-print"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="las la-inbox la-3x text-muted mb-3" style="display: block;"></i>
                            <p class="text-muted">{{ translate('No sales found') }}</p>
                        </td>
                    </tr>
                @endforelse
            @else
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="las la-inbox la-3x text-muted mb-3" style="display: block;"></i>
                        <p class="text-muted">{{ translate('No sales found') }}</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(isset($orders) && $orders->lastPage() > 1)
    <div class="d-flex justify-content-center mt-3">
        <nav>
            <ul class="pagination pagination-sm">
                @if($orders->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="javascript:;" onclick="loadPageSalesList({{ $orders->currentPage() - 1 }})">{{ translate('Previous') }}</a></li>
                @endif

                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page == $orders->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="javascript:;" onclick="loadPageSalesList({{ $page }})">{{ $page }}</a></li>
                    @endif
                @endforeach

                @if($orders->hasMorePages())
                    <li class="page-item"><a class="page-link" href="javascript:;" onclick="loadPageSalesList({{ $orders->currentPage() + 1 }})">{{ translate('Next') }}</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
@endif

<style>
.hover-row:hover {
    background-color: rgba(249, 115, 22, 0.05);
}

.pos-sales-table {
    margin-bottom: 0 !important;
}

.sticky-col {
    background-color: #fff;
    box-shadow: -2px 0 4px rgba(0, 0, 0, 0.05);
}

.hover-row:hover .sticky-col {
    background-color: rgba(249, 115, 22, 0.05);
}

.print-btn {
    padding: 5px 8px !important;
    font-size: 12px !important;
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 768px) {
    .pos-sales-table th,
    .pos-sales-table td {
        padding: 8px 6px !important;
        font-size: 11px !important;
    }
    
    .print-btn {
        padding: 6px 6px !important;
        font-size: 11px !important;
    }
    
    .table-responsive {
        max-height: 500px !important;
    }
}

@media (max-width: 576px) {
    .pos-sales-table th,
    .pos-sales-table td {
        padding: 6px 4px !important;
        font-size: 10px !important;
    }
    
    .badge {
        font-size: 8px !important;
        padding: 2px 4px !important;
    }
    
    .print-btn {
        padding: 5px 5px !important;
        font-size: 10px !important;
    }
    
    .table-responsive {
        max-height: 400px !important;
    }
}
</style>
