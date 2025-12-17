@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('libs/quill/dist/quill.snow.css') }}"> --}}
    <style>
        .ql-container {
            height: 150px !important;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        #to-address {
            white-space: pre-line;
        }

        .quotation-preview-wrapper {
            border: 1px solid #1e40af !important;
            border-radius: 10px;
            background: white;
        }
    </style>
@endsection
@section('content')
    @php
        $parent_title = 'Dashboard';
        $page_title = 'Quotation';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="card card-body py-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-sm-0 card-title">Quotation</h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" href="/">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">view</span>
                                        {{-- <a href="{{ url('/quotation/' . $quotation->id . '/pdf') }}"
                                            class="btn btn-primary">
                                            Download PDF
                                        </a> --}}

                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    @if (isset($quotation))
                        <button class="btn btn-primary mt-3" id="generatePreview" data-id="{{ $quotation->id }}">
                            Generate Preview
                        </button>
                    @else
                        <button class="btn btn-primary mt-3" id="generatePreview" data-id="{{ $quotationId ?? '' }}">
                            Generate Preview
                        </button>
                    @endif
                </div>
            </div>
            <div class="card overflow-auto text-dark" id="pdf_content" style="display: none;color: black !important;"></div>
            <!-- Hide till ready -->

            <div class="preview-container d-none">
                <div class="row text-center my-2">
                    <h2>Quotation Preview</h2>
                </div>
                <div class="quotation-preview-wrapper" style="width:100%; height:80vh; border:none;">
                    <iframe id="quotation-preview-container" style="width:100%; height:100%; border:none;"></iframe>
                </div>

                {{-- <div id="quotation-preview-container"></div>    --}}
                <div class="row align-items-center mt-3 justify-content-center">
                    <div class="col-md-6 text-center">
                        <button id="downloadPdf" data-id="" data-address="" class="btn btn-primary">Download
                            PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const STORAGE_URL = '{{ asset('storage/') }}/';
        window.jsPDF = window.jspdf.jsPDF;

        const iframe = document.getElementById('quotation-preview-container');

        iframe.onload = function() {
            // Get iframe's document safely
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            // Make sure the iframe is same-origin, otherwise you can’t touch it
            if (!iframeDoc) {
                console.error("Iframe content not accessible (different origin)");
                return;
            }

            // Select all images *inside the iframe*
            const imgs = iframeDoc.querySelectorAll("img");

            // Remove that cursed absolute path
            imgs.forEach(img => {
                img.src = img.src.replace(/^.*\/storage\//, '/storage/');
            });
        };

        $('#generatePreview').on('click', async function() {
            const quotationId = $(this).data('id');

            if (!quotationId) {
                toastr.error("Quotation ID not found", "Error");
                return;
            }

            // Show loading state
            $(this).prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span>Loading Preview...');

            try {
                const previewUrl = `/quotations/${quotationId}/preview`;

                document.getElementById('downloadPdf').setAttribute('data-id', quotationId);
                document.getElementById('quotation-preview-container').src = previewUrl;
                document.querySelector('.preview-container').classList.remove('d-none');

                toastr.success("Preview loaded successfully", "Success");
            } catch (err) {
                console.error(err);
                toastr.error("Failed to generate quotation preview", "Error");
            } finally {
                $(this).prop('disabled', false).html('Generate Preview');
            }
        });

        $('#downloadPdf').on('click', function() {
            const id = $(this).data('id');
            const address = $(this).data('address') || 1;

            $(this).prop('disabled', true)
                .html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF... (this may take 30-60 seconds)'
                    );

            // Open download in new tab to prevent page blocking
            window.open(`/quotations/${id}/download/${address}`, '_blank');

            // Re-enable button after delay
            setTimeout(() => {
                $('#downloadPdf').prop('disabled', false).html('Download PDF');
            }, 3000);
        });
    </script>
@endsection
