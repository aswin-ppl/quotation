@if ($quotations->count() === 0)
    <div class="col-12 text-center text-muted py-5" id="no-products">
        <p class="mb-0">No quotations found.</p>
    </div>
@else
    <div class="row g-4">
        @foreach ($quotations as $dq)
            @php
                $quotation = $dq->quotation;
                $firstProduct = $quotation->products->first();
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden hover-lift transition">

                    {{-- Card Header with Quotation Number --}}
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-light">{{ $quotation->quotation_number }}</h5>
                            <span class="badge bg-white text-primary">Quotation</span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h6 class="text-muted text-uppercase small mb-2">Customer</h6>
                            <p class="mb-0 fw-semibold fs-6">
                                {{ $firstProduct?->customer?->name ?? 'Unknown Customer' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted text-uppercase small mb-2">Products</h6>
                            <p class="mb-0">
                                <i class="ti ti-package fs-5 me-2"></i>
                                {{ $quotation->products->count() }} {{ $quotation->products->count() === 1 ? 'Item' : 'Items' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted text-uppercase small mb-2">Downloaded</h6>
                            <p class="mb-0">
                                <i class="ti ti-calendar fs-5 me-2"></i>
                                {{ $dq->downloaded_at?->format('d M Y, h:iA') }}
                            </p>
                        </div>
                    </div>

                    {{-- Card Footer with Actions --}}
                    <div class="card-footer bg-light border-0 p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('quotation.view', $dq->id) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye me-2"></i>View Quotation
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-end pagination-wrapper">
                <nav aria-label="Quotations pagination">
                    {{ $quotations->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
@endif

{{-- Add this CSS to your stylesheet --}}
<style>
    .hover-lift {
        transition: all 0.3s ease-in-out;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }

    .transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
