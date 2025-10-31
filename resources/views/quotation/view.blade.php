@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('libs/quill/dist/quill.snow.css') }}">
    <style>
        .ql-container {
            height: 150px !important;
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
                                        <a class="text-muted text-decoration-none d-flex" href="../main/index.html">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">view</span>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <!-- Quill Editor for To Address -->
                    <div id="editor" class="ql-container ql-snow">
                        <div class="ql-editor" data-gramm="false" contenteditable="true">
                            <p>To,</p>
                        </div>
                        <div class="ql-clipboard" contenteditable="true" tabindex="-1"></div>
                        <div class="ql-tooltip ql-hidden">
                            <a class="ql-preview" rel="noopener noreferrer" target="_blank" href="about:blank"></a>
                            <input type="text" data-formula="e=mc^2" data-link="https://quilljs.com"
                                data-video="Embed URL">
                            <a class="ql-action"></a><a class="ql-remove"></a>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" id="generatePdf">Generate Preview</button>
                    <!-- Renamed for clarity -->
                </div>
            </div>
            <div class="card" id="pdf_content" style="display: none;"></div> <!-- Hide till ready -->
            <button id="downloadPdf" class="btn btn-primary" style="display: none;">Download PDF</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('libs/quill/dist/quill.min.js') }}"></script>
    <script src="{{ asset('js/forms/quill-init.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        const STORAGE_URL = '{{ asset('storage/') }}/';
        window.jsPDF = window.jspdf.jsPDF;

        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('generatePdf').addEventListener('click', function() {
                var addressHtml = quill.root.innerHTML;
                var cartStr = localStorage.getItem('cart');
                var cart = cartStr ? JSON.parse(cartStr) : [];

                if (cart.length === 0) {
                    alert('Cart empty, genius—add products first!');
                    return;
                }

                // ✅ Build HTML with inline styles only
                var html =
                    '<div style="text-align: center; margin: 20px 0;"><h3 style="margin: 0;">Quotation</h3></div>';
                html += '<div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">' +
                    addressHtml + '</div>';
                html += '<table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">';
                html += '<thead><tr style="background: #f0f0f0;">';
                html += '<th style="border: 1px solid #000; padding: 8px;">S.No</th>';
                html += '<th style="border: 1px solid #000; padding: 8px;">Image</th>';
                html += '<th style="border: 1px solid #000; padding: 8px;">Description</th>';
                html += '<th style="border: 1px solid #000; padding: 8px;">Size/Mm</th>';
                html += '<th style="border: 1px solid #000; padding: 8px;">R/Units</th>';
                html += '<th style="border: 1px solid #000; padding: 8px;">Total Amount</th>';
                html += '</tr></thead><tbody>';

                var grandTotal = 0;
                cart.forEach(function(product, index) {
                    var total = parseFloat(product.r_units || 0) * parseFloat(product
                        .product_price || 0);
                    grandTotal += total;
                    var imgSrc = product.image ? STORAGE_URL + product.image.replace(/\\/g, '/') :
                        '';

                    html += '<tr>';
                    html +=
                        '<td style="border: 1px solid #000; padding: 8px; text-align: center;">' + (
                            index + 1) + '</td>';
                    html +=
                        '<td style="border: 1px solid #000; padding: 8px; text-align: center;"><img src="' +
                        imgSrc +
                        '" style="max-width: 80px; max-height: 80px; object-fit: contain;" alt="Product"></td>';
                    html += '<td style="border-top: 1px solid black;padding: 0px;display: flex;">';
                    html += '<table style="border-collapse: collapse; width: 100%;">';
                    (product.descriptions || []).forEach(function(desc) {
                        html +=
                            '<tr><td style="border: 1px solid #ccc; padding: 4px; font-weight: bold; width: 35%;">' +
                            (desc.key || '') + '</td>';
                        html +=
                            '<td style="border: 1px solid #ccc; padding: 4px; width: 65%;">' +
                            (desc.value || '') + '</td></tr>';
                    });
                    html += '</table></td>';
                    html +=
                        '<td style="border: 1px solid #000; padding: 8px; text-align: center;">' + (
                            product.size_mm || '') + '</td>';
                    html +=
                        '<td style="border: 1px solid #000; padding: 8px; text-align: center;">₹' +
                        (product.r_units || '') + '</td>';
                    html +=
                        '<td style="border: 1px solid #000; padding: 8px; text-align: right;">₹' +
                        total.toFixed(2) + '</td>';
                    html += '</tr>';
                });

                html += '</tbody><tfoot>';
                html += '<tr style="background: #f0f0f0; font-weight: bold;">';
                html +=
                    '<td colspan="5" style="border: 1px solid #000; padding: 8px; text-align: right;">Grand Total:</td>';
                html += '<td style="border: 1px solid #000; padding: 8px; text-align: right;">₹' +
                    grandTotal.toFixed(2) + '</td>';
                html += '</tr></tfoot></table>';

                var contentDiv = document.getElementById('pdf_content');
                contentDiv.innerHTML = '<div style="padding: 20px; background: #fff; width: 100%;">' +
                    html + '</div>';
                contentDiv.style.display = 'block';
                document.getElementById('downloadPdf').style.display = 'inline-block';
                contentDiv.scrollIntoView({
                    behavior: 'smooth'
                });
            });

            // ✅ DIRECT CANVAS → PDF APPROACH
            document.getElementById('downloadPdf').addEventListener('click', function() {
                var element = document.getElementById('pdf_content');

                // Force scroll to top
                window.scrollTo(0, 0);

                // Show loading state
                this.disabled = true;
                this.textContent = 'Generating PDF...';

                // ✅ Use html2canvas directly
                html2canvas(element, {
                    scale: 2, // Higher quality
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    scrollY: -window.scrollY, // ✅ Fixes scroll offset
                    scrollX: -window.scrollX,
                    windowWidth: element.scrollWidth,
                    windowHeight: element.scrollHeight
                }).then(function(canvas) {

                    // Get canvas dimensions
                    var imgWidth = 210; // A4 width in mm
                    var pageHeight = 297; // A4 height in mm
                    var imgHeight = (canvas.height * imgWidth) / canvas.width;
                    var heightLeft = imgHeight;

                    // Convert canvas to image data
                    var imgData = canvas.toDataURL('image/jpeg', 1.0);

                    // ✅ Create PDF with exact dimensions
                    var pdf = new jsPDF('p', 'mm', 'a4');
                    var position = 0;

                    // Add first page
                    pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    // ✅ Handle multiple pages if content is long
                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // Save the PDF
                    pdf.save('quotation.pdf');

                    // Reset button
                    document.getElementById('downloadPdf').disabled = false;
                    document.getElementById('downloadPdf').textContent = 'Download PDF';

                }).catch(function(error) {
                    console.error('Canvas generation failed:', error);
                    alert('PDF generation failed! Check console.');
                    document.getElementById('downloadPdf').disabled = false;
                    document.getElementById('downloadPdf').textContent = 'Download PDF';
                });
            });
        });
    </script>
    <script>
        // const STORAGE_URL = '{{ asset('storage/') }}/';

        // document.addEventListener('DOMContentLoaded', function() {
        // Assume quill-init.js sets 'quill' global var; if not, add: var quill = new Quill('#editor', { theme: 'snow' });

        // document.getElementById('generatePdf').addEventListener('click', function() {
        //     var addressHtml = quill.root.innerHTML; // Gets full HTML from Quill
        //     var cartStr = localStorage.getItem('cart');
        //     var cart = cartStr ? JSON.parse(cartStr) : []; // Parse array of products or empty [web:8][web:11]

        //     if (cart.length === 0) {
        //         alert('Cart empty, genius—add products first!');
        //         return;
        //     }

        //     var html = '<div class="d-flex justify-content-center my-3"><h3>Quotation</h3></div>';

        //     html += '<div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">' + addressHtml + '</div>';
        //     html += '<table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">';
        //     html += '<thead><tr style="background: #f0f0f0;">';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">S.No</th>';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">Image</th>';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">Description</th>';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">Size/Mm</th>';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">R/Units</th>';
        //     html += '<th style="border: 1px solid #000; padding: 8px;">Total Amount</th>';
        //     html += '</tr></thead><tbody>';

        //     var grandTotal = 0;
        //     cart.forEach(function(product, index) {
        //         var total = parseFloat(product.r_units || 0) * parseFloat(product.product_price || 0);
        //         grandTotal += total;

        //         var imgSrc = product.image ? STORAGE_URL + product.image.replace(/\\/g, '/') : '';

        //         html += '<tr>';
        //         html += '<td style="border: 1px solid #000; padding: 8px; text-align: center;">' + (index + 1) + '</td>';
        //         html += '<td style="border: 1px solid #000; padding: 8px; text-align: center;"><img src="' + imgSrc + '" style="width: 100px; height: 100px; object-fit: cover;" alt="Product"></td>';
        //         html += '<td style="border-top: 1px solid #000; padding: 0px !important;align-items: start;display: flex;">';
        //         html += '<table style="border-collapse: collapse; width: 100%;">';
        //         (product.descriptions || []).forEach(function(desc) {
        //             html += '<tr><td style="border: 1px solid #ccc; padding: 4px; font-weight: bold;">' + (desc.key || '') + '</td>';
        //             html += '<td style="border: 1px solid #ccc; padding: 4px;">' + (desc.value || '') + '</td></tr>';
        //         });
        //         html += '</table>';
        //         html += '</td>';
        //         html += '<td style="border: 1px solid #000; padding: 8px; text-align: center;">' + (product.size_mm || '') + '</td>';
        //         html += '<td style="border: 1px solid #000; padding: 8px; text-align: center;">₹' + (product.r_units || '') + '</td>';
        //         html += '<td style="border: 1px solid #000; padding: 8px; text-align: right;">₹' + total.toFixed(2) + '</td>'; // Assume INR [web:2]
        //         html += '</tr>';
        //     });

        //     html += '</tbody><tfoot>';
        //     html += '<tr style="background: #f0f0f0; font-weight: bold;">';
        //     html += '<td colspan="5" style="border: 1px solid #000; padding: 8px; text-align: right;">Grand Total:</td>'; // Spans S.No to R/Units [web:2]
        //     html += '<td style="border: 1px solid #000; padding: 8px; text-align: right;">₹' + grandTotal.toFixed(2) + '</td>';
        //     html += '</tr></tfoot></table>';

        //     var contentDiv = document.getElementById('pdf_content');
        //     contentDiv.innerHTML = '<div class="card-body p-4">' + html + '</div>'; // Wrap for card styling
        //     contentDiv.style.display = 'block';
        //     document.getElementById('downloadPdf').style.display = 'inline-block';

        //     // Optional: Scroll to preview
        //     contentDiv.scrollIntoView({ behavior: 'smooth' });
        // });

        // document.getElementById('downloadPdf').addEventListener('click', function() {
        //     var element = document.getElementById('pdf_content');
        //     var opt = {
        //         margin: 1,
        //         filename: 'quotation.pdf',
        //         image: {
        //             type: 'jpeg',
        //             quality: 0.98
        //         },
        //         html2canvas: {
        //             scale: 2
        //         }, // Crisp images/tables [web:3][web:9]
        //         jsPDF: {
        //             unit: 'in',
        //             format: 'a4',
        //             orientation: 'portrait'
        //         }
        //     };
        //     html2pdf().set(opt).from(element).save(); // Generates & downloads [web:3][web:6]
        // });
        // });
    </script>
@endsection
