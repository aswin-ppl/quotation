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
                <div class="table-responsive border rounded-4">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Product</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">R/Units</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Price</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Qty</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody"></tbody>
                    </table>
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
            <div class="card overflow-auto text-dark" id="pdf_content" style="display: none;"></div>
            <!-- Hide till ready -->
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

            //products table
            const tableBody = document.getElementById('cartTableBody');

            // Get cart from localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Render the cart
            function renderCart() {
                tableBody.innerHTML = '';

                if (cart.length === 0) {
                    tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">Your cart is empty 🛒</td>
                </tr>`;
                    return;
                }

                cart.forEach((product, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <img src="/storage/${product.image || 'images/no-image.png'}"
                             class="rounded-2" width="42" height="42" alt="${product.name}">
                        <div class="ms-3">
                            <h6 class="fw-semibold mb-1 text-capitalize">${product.name}</h6>
                            <span class="fw-normal">${product.size_mm || ''} mm</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="fw-normal"> ${parseFloat(product.r_units || 0).toFixed(2)}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="fw-normal">₹ ${parseFloat(product.product_price || 0).toFixed(2)}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="fw-normal"> ${parseFloat(product.qty || 0)}</span>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm bg-danger-subtle text-danger btn-delete" data-id="${product.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524" />
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.34 1.181-5.246l.267-4.187c.1-1.577.15-2.366-.303-2.866c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.289-.303 2.866l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886m-1.35-9.811c-.04-.434-.408-.75-.82-.707c-.413.043-.713.43-.672.864l.5 5.263c.04.434.408.75.82.707c.413-.044.713-.43.672-.864zm4.329-.707c.412.043.713.43.671.864l-.5 5.263c-.04.434-.409.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.04-.433.409-.75.82-.707"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </td>
            `;
                    tableBody.appendChild(row);
                });
            }

            // Delete item
            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.btn-delete')) {
                    const id = e.target.closest('.btn-delete').dataset.id;
                    cart = cart.filter(item => item.id != id);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                    updateCartCount(cart.length);
                }
            });

            // Cart count updater (if you have a badge)
            function updateCartCount(count) {
                document.querySelectorAll('.cartCount').forEach(el => {
                    el.textContent = count;
                    el.style.display = count > 0 ? 'inline-block' : 'none';
                });
            }

            // Initial render
            renderCart();
            updateCartCount(cart.length);


            //generate pdf
            document.getElementById('generatePdf').addEventListener('click', function() {
                var addressHtml = quill.root.innerHTML;
                var cartStr = localStorage.getItem('cart');
                var cart = cartStr ? JSON.parse(cartStr) : [];

                if (cart.length === 0) {
                    alert('Cart empty, genius—add products first!');
                    return;
                }

                //  Build HTML with inline styles only
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
                        .product_price || 0) * parseFloat(product.qty || 0) ;
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

            //  DIRECT CANVAS → PDF APPROACH
            document.getElementById('downloadPdf').addEventListener('click', function() {
                var element = document.getElementById('pdf_content');

                // --- ADD THIS: Temporarily force desktop mode
                element.classList.add('pdf-desktop-mode');

                // Force scroll to top
                window.scrollTo(0, 0);

                // Show loading state
                this.disabled = true;
                this.textContent = 'Generating PDF...';

                //  Use html2canvas directly
                html2canvas(element, {
                    scale: 2, // Higher quality
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    scrollY: -window.scrollY, //  Fixes scroll offset
                    scrollX: -window.scrollX,
                    // windowWidth: element.scrollWidth,
                    windowWidth: 900, // same as our forced width
                    windowHeight: element.scrollHeight
                }).then(function(canvas) {

                    // Get canvas dimensions
                    var imgWidth = 210; // A4 width in mm
                    var pageHeight = 297; // A4 height in mm
                    var imgHeight = (canvas.height * imgWidth) / canvas.width;
                    var heightLeft = imgHeight;

                    // Convert canvas to image data
                    var imgData = canvas.toDataURL('image/jpeg', 1.0);

                    //  Create PDF with exact dimensions
                    var pdf = new jsPDF('p', 'mm', 'a4');
                    var position = 0;

                    // Add first page
                    pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    //  Handle multiple pages if content is long
                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // Save the PDF
                    pdf.save('quotation.pdf');

                    // REMOVE THE CLASS after rendering
                    element.classList.remove('pdf-desktop-mode');

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
