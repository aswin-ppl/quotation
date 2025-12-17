<!DOCTYPE html>
<html lang="en">

<head>
    @php
        // Set default pdfMode if not provided
        $pdfMode = $pdfMode ?? false;
    @endphp
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quotation #{{ $quotation->quotation_number }}</title>
    <style type="text/css">
        @page {
            size: A4;
            margin: 2mm 0mm;
        }

        @page :first {
            margin: 2mm 0mm;
        }

        @page :left {
            margin: 2mm 0mm;
        }

        @page :right {
            margin: 2mm 0mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .page-container {
            background: white;
            padding: 2mm 5mm;
            margin: 0;
        }

        .page-break {
            page-break-after: always;
            margin: 0;
            padding: 0;
        }

        .product-page-break {
            page-break-after: always;
            margin: 0;
            padding: 0;
        }

        /* ... rest of your CSS ... */


        /* Prevent breaking inside products */
        .product-card {
            page-break-inside: avoid;
        }

        .totals-section {
            page-break-inside: avoid;
        }

        .footer {
            page-break-inside: avoid;
        }

        /* Professional Header */
        .header-wrapper {
            border-bottom: 3px solid #044b26;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .company-info img {
            max-width: 150px;
            height: auto;
            margin-bottom: 5px;
        }

        .company-info h1 {
            font-size: 24px;
            color: #044b26;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .company-tagline {
            font-size: 9px;
            color: #64748b;
            font-style: italic;
            margin: 0;
        }

        .quotation-info {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .quotation-badge {
            display: inline-block;
            background: #044b26;
            color: white;
            padding: 5px 10px;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            border-radius: 4px;
        }

        .quotation-meta {
            background: #f1f5f9;
            padding: 8px 12px;
            border-left: 4px solid #044b26;
            font-size: 10px;
            text-align: left;
            display: inline-block;
        }

        .quotation-meta p {
            margin: 2px 0;
        }

        .quotation-meta strong {
            color: #044b26;
            font-weight: bold;
        }

        /* Address Section */
        .address-section {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
        }

        .address-box {
            display: table-cell;
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }

        .address-box:first-child {
            border-right: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .address-box h3 {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-weight: bold;
            border-bottom: 2px solid #044b26;
            padding-bottom: 5px;
            display: inline-block;
        }

        .address-box .company-name {
            font-size: 14px;
            color: #044b26;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        .address-box p {
            font-size: 10px;
            color: #475569;
            line-height: 1.3;
        }

        /* Product Card */
        .product-card {
            margin-bottom: 10px;
            background: white;
        }

        .product-header {
            background: #044b26;
            color: white;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .product-content {
            page-break-inside: avoid;
        }

        /* Image Gallery */
        .image-gallery {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .gallery-title {
            font-size: 11px;
            font-weight: bold;
            color: #044b26;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .gallery-grid {
            width: 100%;
            margin-top: 2%;
            overflow: auto;
            clear: both;
        }

        .gallery-item {
            float: left;
            width: 33.333%;
            text-align: center;
            padding: 5px;
            box-sizing: border-box;
            font-size: 11px;
        }

        .gallery-item img {
            max-width: 100%;
            height: auto;
            max-height: 120px;
            border: 2px solid #e2e8f0;
            padding: 5px;
            display: block;
            margin: 0 auto;
        }

        .gallery-item p {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Clearfix for gallery grid */
        .gallery-grid::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Add clearfix after gallery */
        .gallery-grid::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Totals */
        .totals-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .totals-left {
            display: table-cell;
            width: 55%;
            padding-right: 20px;
            vertical-align: top;
        }

        .totals-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #044b26;
            padding: 15px;
        }

        .notes-box h4 {
            font-size: 11px;
            color: #044b26;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .notes-box p {
            font-size: 9px;
            color: #475569;
            line-height: 1.6;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        .totals-table tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-table tr.grand-total {
            background: #044b26;
            border: none;
        }

        .totals-table tr.grand-total td {
            color: white;
            font-size: 13px;
            font-weight: bold;
            padding: 12px;
        }

        .totals-table td {
            padding: 10px 12px;
            border: none;
            font-size: 10px;
        }

        .totals-table td:first-child {
            font-weight: bold;
            color: #475569;
            width: 65%;
        }

        .totals-table td:last-child {
            text-align: right;
            color: #044b26;
            font-weight: bold;
        }

        .totals-section {
            page-break-before: auto;
            /* Only break if content doesn't fit */
            page-break-inside: avoid;
            /* Keep totals together */
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .footer h4 {
            font-size: 10px;
            color: #044b26;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .footer p {
            font-size: 9px;
            color: #64748b;
            line-height: 1.6;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 2px solid #044b26;
            width: 200px;
            padding-top: 5px;
            text-align: center;
            float: right;
        }

        .signature-line p {
            font-size: 10px;
            color: #475569;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* Flowchart Image */
        .flowchart-section {
            page-break-before: always;
            text-align: center;
            margin: 0;
            padding: 5mm;
        }

        .flowchart-section h3 {
            font-size: 16px;
            color: #044b26;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .flowchart-section img {
            max-width: 100%;
            height: auto;
            max-height: 90mm;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Professional Header -->
        <div class="header-wrapper">
            <div class="header-top">
                <div class="company-info">
                    @if ($settings && isset($settings['company_logo']) && $settings['company_logo'])
                        @php
                            if ($pdfMode) {
                                // For PDF: use absolute file path
                                $logoPath = public_path('storage/' . $settings['company_logo']);
                                $logoSrc = file_exists($logoPath) ? $logoPath : '';
                            } else {
                                // For browser preview: use URL
                                $logoSrc = url('/storage/' . $settings['company_logo']);
                            }
                        @endphp

                        @if ($logoSrc)
                            <img src="{{ $logoSrc }}" alt="company-logo" style="max-width: 100px; height: auto;">
                        @endif
                    @endif

                    <h1>{{ $settings['company_name'] ?? 'Your Company' }}</h1>
                    <p class="company-tagline">Your Trusted Business Partner</p>
                </div>
                <div class="quotation-info">
                    <div class="quotation-badge">{{ $quotation->quotation_number }}</div>
                    <div class="quotation-meta">
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}
                        </p>
                        <p><strong>Valid Until:</strong>
                            {{ \Carbon\Carbon::parse($quotation->created_at)->addDays(30)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="address-section">
            <div class="address-box">
                <h3>Bill To</h3>
                @php
                    $firstProduct = $quotation->products->first();
                    $customer = $firstProduct?->customer;
                    $address = $firstProduct?->address;

                    if (!$address && $customer) {
                        $address =
                            $customer->addresses()->where('is_default', 1)->first() ?? $customer->addresses()->first();
                    }
                @endphp
                <span class="company-name">{{ $customer?->name ?? 'Unknown Customer' }}</span>
                <p>
                    @if ($address)
                        {{ $address->address_line_1 ?? '' }}<br>
                        {{ $address->city?->name ?? '' }}, {{ $address->district?->name ?? '' }}<br>
                        {{ $address->state?->name ?? '' }} - {{ $address->pincode?->code ?? '' }}
                    @else
                        <em>No address found</em>
                    @endif
                </p>
            </div>

            <div class="address-box">
                <h3>From</h3>
                <span class="company-name">{{ $settings['company_name'] ?? 'Your Company' }}</span>
                <p>
                    {{ $settings['company_address'] ?? 'Company Street' }}<br>
                    {{ $settings['company_city_name'] ?? 'City' }},
                    {{ $settings['company_district_name'] ?? 'District' }}<br>
                    {{ $settings['company_state_name'] ?? 'State' }} -
                    {{ $settings['company_pincode_value'] ?? '000000' }}<br>
                    <strong>Email:</strong> {{ $settings['company_email'] ?? 'info@company.com' }}<br>
                    <strong>Phone:</strong> {{ $settings['company_mobile'] ?? '+91 XXXXX XXXXX' }}
                </p>
            </div>
        </div>
        <!-- Products Section -->
        @php
            $serialNumber = 1;
        @endphp

        @forelse ($quotation->products as $index => $product)
            <div class="product-card" style="@if($index > 0) page-break-before: always; @endif margin-bottom: 10px;">
                <div class="product-content" style="page-break-inside: avoid;">
                    <table
                        style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1; table-layout: fixed;">
                        <thead>
                            <tr style="background: #044b26; border-bottom: 2px solid #e2e8f0;">
                                <th
                                    style="padding: 8px; text-align: center; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 4%;">
                                    S.No</th>
                                <th
                                    style="padding: 8px; text-align: center; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 25%;">
                                    CAD Image</th>
                                <th
                                    style="padding: 8px; text-align: left; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 35%;">
                                    Description</th>
                                <th
                                    style="padding: 8px; text-align: center; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 4%;">
                                    Qty</th>
                                <th
                                    style="padding: 8px; text-align: center; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 10%;">
                                    Size (mm)</th>
                                <th
                                    style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 8%;">
                                    Cost/Unit</th>
                                <th
                                    style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 10%;">
                                    Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border: 1px solid #e2e8f0;">
                                <!-- Serial Number -->
                                <td
                                    style="padding: 8px; text-align: center; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    {{ $serialNumber++ }}
                                </td>

                                <!-- Image Cell -->
                                <td
                                    style="padding: 8px; text-align: center; border: 1px solid #e2e8f0; vertical-align: top;">
                                    @php
                                        $cadImage = $product->images?->where('type', 'cad')->first();
                                    @endphp

                                    @if ($cadImage)
                                        @php
                                            $rawPath = storage_path('app/private/' . $cadImage->path);
                                            $extension = strtolower(pathinfo($rawPath, PATHINFO_EXTENSION));

                                            if ($pdfMode) {
                                                if (in_array($extension, ['webp', 'png'])) {
                                                    $tempJpg = storage_path(
                                                        'app/temp/' .
                                                            pathinfo($cadImage->path, PATHINFO_FILENAME) .
                                                            '.jpg',
                                                    );
                                                    $src = file_exists($tempJpg)
                                                        ? 'file://' . $tempJpg
                                                        : 'file://' . $rawPath;
                                                } else {
                                                    $src = 'file://' . $rawPath;
                                                }
                                            } else {
                                                $src = url('/private-image/' . $cadImage->path);
                                            }
                                        @endphp

                                        <img src="{{ $src }}" alt="CAD Image"
                                            style="max-width: 100%; height: auto; max-height: 250px; border: 1px solid #e2e8f0; padding: 2px;">
                                    @endif
                                </td>

                                <!-- Description Cell with Nested Table -->
                                <td style="border: 1px solid #e2e8f0; vertical-align: top; word-wrap: break-word;">
                                    <div style="font-weight: bold; color: #044b26; font-size: 11px;">
                                        {{ $product->name }}
                                    </div>

                                    @if ($product->descriptions && $product->descriptions->count() > 0)
                                        <!-- Nested Sub-Table for Specifications -->
                                        <table
                                            style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;">
                                            <tbody>
                                                @foreach ($product->descriptions as $desc)
                                                    <tr>
                                                        <td
                                                            style="padding: 4px 6px; font-size: 8px; color: #475569; font-weight: bold; border: 1px solid #e2e8f0; background: #fafafa; width: 40%;">
                                                            {{ ucfirst(str_replace('_', ' ', $desc->key)) }}
                                                        </td>
                                                        <td
                                                            style="padding: 4px 6px; font-size: 8px; color: #334155; border: 1px solid #e2e8f0; width: 60%; word-wrap: break-word;">
                                                            {{ $desc->value }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>

                                <!-- Quantity -->
                                <td
                                    style="padding: 6px; text-align: center; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    {{ $product->quantity ?? ($product->r_units ?? 1) }}
                                </td>

                                <!-- Size -->
                                <td
                                    style="padding: 6px; text-align: center; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 9px; word-wrap: break-word;">
                                    {{ $product->size_mm ?? '-' }}
                                </td>

                                <!-- Cost per Unit -->
                                <td
                                    style="padding: 6px; text-align: right; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 9px;">
                                    ₹{{ number_format($product->cost_per_units ?? 0, 2) }}
                                </td>

                                <!-- Total Price -->
                                <td
                                    style="padding: 6px; text-align: right; font-weight: bold; color: #044b26; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    ₹{{ number_format($product->product_price ?? 0, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Additional Images Gallery -->
                    @if ($product->images && $product->images->where('type', 'extras')->count())
                        @php
                            $extraImagesCount = $product->images->where('type', 'extras')->count();
                        @endphp
                        
                        <!-- Add page break before extra images for the first product only if more than 3 images -->
                        @if($index === 0 && $extraImagesCount > 3)
                            <div style="page-break-before: always;"></div>
                        @endif
                        
                        <div class="image-gallery">
                            <div class="gallery-title">Additional Images</div>

                            @php
                                $extraImages = $product->images->where('type', 'extras')->values();
                                $totalImages = $extraImages->count();
                                $imageChunks = $extraImages->chunk(3); // Split into groups of 3
                                $globalIndex = 0; // Track overall image index for labels
                            @endphp

                            <!-- Loop through each chunk/row of 3 images -->
                            @foreach ($imageChunks as $chunk)
                                <div class="gallery-grid">
                                    @foreach ($chunk as $img)
                                        @php
                                            $globalIndex++;
                                            $rawPath = storage_path('app/private/' . $img->path);
                                            $extension = strtolower(pathinfo($rawPath, PATHINFO_EXTENSION));
                                            $filename = pathinfo($img->path, PATHINFO_FILENAME);

                                            if ($pdfMode) {
                                                if (in_array($extension, ['webp', 'png'])) {
                                                    $tempJpg = storage_path('app/temp/' . $filename . '.jpg');
                                                    $src = file_exists($tempJpg)
                                                        ? 'file://' . $tempJpg
                                                        : 'file://' . $rawPath;
                                                } else {
                                                    $src = 'file://' . $rawPath;
                                                }
                                            } else {
                                                $src = url('/private-image/' . $img->path);
                                            }

                                            $label = chr(96 + $globalIndex);
                                        @endphp

                                        <div class="gallery-item">
                                            <div style="position: relative; display: inline-block;">
                                                <img src="{{ $src }}" alt="Extra Image {{ $label }}"
                                                    style="max-width: 100%; height: auto; max-height: 100px; border: 1px solid #e2e8f0; padding: 2px;">
                                                <div style="position: absolute; top: 2px; left: 2px; background: #044b26; color: white; 
                                                    padding: 2px 6px; font-size: 9px; font-weight: bold; border-radius: 2px;">
                                                    {{ $label }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="product-card">
                <div class="product-header">NO PRODUCTS</div>
                <div class="product-content">
                    <p class="text-center" style="color: #94a3b8;">No products found for this quotation</p>
                </div>
            </div>
        @endforelse
        <!-- Page break AFTER all products (before totals) -->
        @php
            $productCount = $quotation->products->count();
            $forceBreak = $productCount > 2; // Adjust threshold as needed
        @endphp

        @if ($forceBreak)
            <div style="page-break-after: always; margin: 0; padding: 0; height: 0;"></div>
        @endif
        
        <!-- Flowchart Section -->
        @if ($pdfMode ?? false)
        <div class="flowchart-section">
            <h3>Process Flow</h3>
            <img src="{{ public_path('images/flowchart.svg') }}" alt="Process Flowchart">
        </div>
        @else
        <div class="flowchart-section">
            <h3>Process Flow</h3>
            <img src="{{ asset('images/flowchart.svg') }}" alt="Process Flowchart">
        </div>
        @endif
        
        <!-- Totals Section -->
        <div class="totals-section" style="margin-top: 30px;">
            <div class="totals-left">
                <div class="notes-box">
                    <h4>Terms & Conditions</h4>
                    <p>
                        • Transportation charges extra.<br>
                        • GST @18% will be extra.<br>
                        • The quote is as per the Drawing received, in case the size varies as per site condition, the quote and
invoice will vary.<br>
                        • The price quoted are as per rates on the quotation date, and in case of any change in the rate of
inputs, the quote May vary.<br>
                        • Our quote is always on standard length and height, the standard measurements will apply for billing,
Considering aluminum section length as 12 Feet.<br>
                        • Kindly attach the Delivery & Billing address along with GST/PAN details with the order<br>
                        • Electric supply for using electric power tools & scaffolding wherever found necessary, are to be
Provided by you free of cost at site.<br>
                        • SUPPLY: Minimum (Depending on quantity) Weeks after the order, advance & clear measurements
after site Readiness is received.<br>
                        • PAYMENT: 80% to be paid as advance along with order and 15% on delivery of the material and
balance 5% on completion of the work.<br>
                        • 18% interest will be charged if the payment is not received as per the terms from the due date.<br>
                    </p>
                </div>
            </div>
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>- ₹0.00</td>
                    </tr>
                    <tr>
                        <td>Tax (GST)</td>
                        <td>+ ₹0.00</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GRAND TOTAL</td>
                        <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)), 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Professional Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <h4>Payment Information</h4>
                    <p>
                        Bank Name: {{ config('app.bank_name', 'Your Bank Name') }}<br>
                        Account Number: {{ config('app.account_number', 'XXXXXXXXXXXX') }}<br>
                        IFSC Code: {{ config('app.ifsc_code', 'XXXXXX') }}<br>
                        Account Name: {{ config('app.name') }}
                    </p>
                </div>
                <div class="footer-right" style="width:0px !important;">
                    <div class="signature-line">
                        <p>Authorized Signature</p>
                    </div>
                </div>
                <div class="footer-right"></div>
                <div class="footer-right">
                    <div class="signature-line">
                        <p>Authorized Signature</p>
                    </div>
                </div>
            </div>
            <p class="text-center" style="margin-top: 20px; font-size: 9px; color: #94a3b8; font-style: italic;">
                Thank you for your business! For any queries, please contact us.
            </p>
        </div>
    </div>
</body>

</html>
