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
            border-bottom: 3px solid #0101fd;
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
            color: #0101fd;
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
            background: #0101fd;
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
            border-left: 4px solid #0101fd;
            font-size: 10px;
            text-align: left;
            display: inline-block;
        }

        .quotation-meta p {
            margin: 2px 0;
        }

        .quotation-meta strong {
            color: #0101fd;
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
            border-bottom: 2px solid #0101fd;
            padding-bottom: 5px;
            display: inline-block;
        }

        .address-box .company-name {
            font-size: 14px;
            color: #0101fd;
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
            background: #0101fd;
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
            color: #0101fd;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .gallery-grid {
            width: 100%;
            margin-top: 15px;
            overflow: visible;
            clear: both;
            display: block;
            padding-top: 15px;
        }

        .gallery-grid:first-of-type {
            padding-top: 0;
            margin-top: 0;
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
            border-left: 4px solid #0101fd;
            padding: 15px;
        }

        .notes-box h4 {
            font-size: 11px;
            color: #0101fd;
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
            background: #0101fd;
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
            color: #0101fd;
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
            color: #0101fd;
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
            border-top: 2px solid #0101fd;
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
            color: #0101fd;
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
                            if ($pdfMode && isset($imageMap) && isset($imageMap['company_logo'])) {
                                // Use base64 encoded logo for PDF
                                $logoSrc = $imageMap['company_logo'];
                            } else {
                                // For browser preview: use dedicated controller route
                                $logoSrc = route('company.logo');
                            }
                        @endphp

                        @if ($logoSrc)
                            <img src="{{ $logoSrc }}" alt="company-logo" style="max-width: 100px; height: auto;">
                        @endif
                    @endif

                    <h1 style="white-space: nowrap;">{{ $settings['company_name'] ?? 'Your Company' }}</h1>
                    <p><strong>since 1985</strong></p>
                    <p class="company-tagline">Your Trusted Business Partner.</p>
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
                <h3>To</h3>
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
        <!-- Products Section - Tables Only -->
        @php
            $serialNumber = 1;
        @endphp

        @forelse ($quotation->products as $index => $product)
            @if ($index > 0)
                <div class="product-card" style="page-break-before: always; margin-bottom: 10px;">
                @else
                    <div class="product-card" style="margin-bottom: 10px;">
            @endif
            <div class="product-content" style="page-break-inside: avoid;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1; table-layout: fixed;">
                    <thead>
                        <tr style="background: #0101fd; border-bottom: 2px solid #e2e8f0;">
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
                                style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 4%;">
                                Qty</th>
                            <th
                                style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 10%;">
                                Size (mm)</th>
                            <th
                                style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 8%;">
                                Cost/Unit</th>
                            <th
                                style="padding: 8px; text-align: right; font-weight: bold; color: #fff; font-size: 10px; border: 1px solid #e2e8f0; width: 10%;">
                                Total</th>
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
                                        if ($pdfMode && isset($imageMap) && isset($imageMap[$cadImage->path])) {
                                            // Use base64 encoded image
                                            $src = $imageMap[$cadImage->path];
                                        } else {
                                            // For browser preview: use URL
                                            $src = url('/private-image/' . $cadImage->path);
                                        }
                                    @endphp

                                    @if ($src)
                                        <img src="{{ $src }}" alt="CAD Image"
                                            style="max-width: 100%; height: auto; max-height: 250px; border: 1px solid #e2e8f0; padding: 2px;">
                                    @endif
                                @endif
                            </td>

                            <!-- Description Cell with Nested Table -->
                            <td style="border: 1px solid #e2e8f0; vertical-align: top; word-wrap: break-word;">
                                <div
                                    style="display:inline-flex; justify-content:center; align-items: center; text-align:center; font-weight: bold; font-size: 11px;width: 100%;">
                                    <span style="color: #546173">Product Name:</span>
                                    <span style="color: #0101fd; font-size: 11px;">{{ $product->name }}</span>
                                </div>

                                @if ($product->descriptions && $product->descriptions->count() > 0)
                                    <!-- Nested Sub-Table for Specifications -->
                                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;">
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
                                style="padding: 6px; text-align: right; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                {{ $product->quantity ?? ($product->r_units ?? 1) }}
                            </td>

                            <!-- Size -->
                            <td
                                style="padding: 6px; text-align: right; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 9px; word-wrap: break-word;">
                                {{ $product->size_mm ?? '-' }}
                            </td>

                            <!-- Cost per Unit -->
                            <td
                                style="padding: 6px; text-align: right; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 9px;">
                                ₹{{ number_format($product->cost_per_units ?? 0, 2) }}
                            </td>

                            <!-- Total Price -->
                            <td
                                style="padding: 6px; text-align: right; font-weight: bold; color: #0101fd; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                ₹{{ number_format($product->product_price ?? 0, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
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

    <!-- Totals Table - Right Below Product Tables -->
    <div style="margin-top: 20px; margin-bottom: 30px; float: right; clear: both;">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)), 2) }}
                </td>
            </tr>
            @if ($quotation->discount && $quotation->discount > 0)
                <tr>
                    <td>Discount ({{ $quotation->discount }}%)</td>
                    <td>-
                        ₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)) * ($quotation->discount / 100), 2) }}
                    </td>
                </tr>
            @endif
            <tr>
                <td>Tax (GST)</td>
                <td>+ ₹0.00</td>
            </tr>
            <tr class="grand-total">
                <td>GRAND TOTAL</td>
                <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)) * (1 - ($quotation->discount > 0 ? $quotation->discount / 100 : 0)), 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both; margin-top: 20px;"></div>


    @php
        $hasExtraImages = $quotation->products->some(function ($product) {
            return $product->images && $product->images->where('type', 'extras')->count() > 0;
        });
    @endphp

    @if ($hasExtraImages)
        <div style="page-break-before: always;"></div>

        <!-- Clearfix for floated totals table -->

        <!-- Additional Images Section - All Products -->
        @forelse ($quotation->products as $imgIndex => $product)
            @if ($product->images && $product->images->where('type', 'extras')->count())
                <div class="image-gallery">
                    <!-- Product Heading for Images Section -->
                    <div style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #0101fd;">
                        <h3 style="color: #0101fd; margin: 0; font-size: 16px;">
                            {{ $product->name }} - Additional Images
                        </h3>
                    </div>

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

                                    if ($pdfMode && isset($imageMap) && isset($imageMap[$img->path])) {
                                        // Use base64 encoded image
                                        $src = $imageMap[$img->path];
                                    } else {
                                        // For browser preview
                                        $src = url('/private-image/' . $img->path);
                                    }

                                    $label = chr(96 + $globalIndex);
                                @endphp

                                @if ($src)
                                    <div class="gallery-item">
                                        <div style="position: relative; display: inline-block;">
                                            <img src="{{ $src }}" alt="Extra Image {{ $label }}"
                                                style="max-width: 100%; height: auto; max-height: 100px; border: 1px solid #e2e8f0; padding: 2px;">
                                            <div
                                                style="position: absolute; top: 2px; left: 2px; background: #0101fd; color: white; 
                                                padding: 2px 6px; font-size: 9px; font-weight: bold; border-radius: 2px;">
                                                {{ $label }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <!-- No additional images -->
        @endforelse
    @endif

    <!-- Flowchart Section -->
    <div class="flowchart-section" style="page-break-before: always;">
        <h3>Process Flow</h3>
        @php
            if ($pdfMode) {
                // Use absolute file path for DomPDF
                $flowchartPath = public_path('images/flowchart.svg');
                $flowchartSrc = file_exists($flowchartPath) ? 'file://' . $flowchartPath : '';
            } else {
                // For browser preview
                $flowchartSrc = url('images/flowchart.svg');
            }
        @endphp
        @if ($flowchartSrc)
            <img src="{{ $flowchartSrc }}" alt="Process Flow Diagram">
        @endif
    </div>

    <!-- Terms & Conditions Section-->
    <div class="notes-box" style="margin-top: 40px; page-break-inside: avoid;">
        <h4>Terms & Conditions</h4>
        <p>
            @if ($quotation->remarks)
                {!! nl2br(e($quotation->remarks)) !!}<br><br>
            @endif
            <strong>TRANSPORT:</strong> Charges will be extra.<br>
            <strong>VALIDITY:</strong> This quotation will be valid up to two weeks from the date of our quote.<br>
            <strong>TAXES:</strong> GST @18% will be extra.<br>
            <strong>QUOTE:</strong> The quote is as per the drawing received from you. In case the size varies as per
            site condition, the quote and invoice will vary.
            The price quoted is as per rates on the quotation date, and in case of any change in the rate of inputs, the
            quote may vary.
            Our quote is always on standard length and height; hence the standard measurements will apply for billing,
            considering aluminium section length as 12 feet.<br>
            <strong>YOUR ORDER:</strong> Please attach the delivery &amp; billing address along with GST/PAN details
            with the order.<br>
            <strong>IN YOUR COST:</strong> Electric supply for using electric power tools and scaffolding wherever found
            necessary are to be provided by you free of cost at site.<br>
            <strong>SUPPLY:</strong> Minimum 6 to 7 weeks after the order, advance, and clear measurements after site
            readiness is received.<br>
            <strong>PAYMENT:</strong> 80% to be paid as advance along with the order, 15% on delivery of the material,
            and the balance 5% on completion of the work.
            18% interest will be charged if the payment is not received as per the terms from the due date.
        </p>

    </div>

    <!-- Professional Footer -->
    <div class="footer">
        <div class="footer-content">
            @php
                $hasBankInfo =
                    !empty($settings['bank_name']) ||
                    !empty($settings['bank_account_number']) ||
                    !empty($settings['bank_ifsc_code']) ||
                    !empty($settings['bank_account_name']);
            @endphp

            @if ($hasBankInfo)
                <div class="footer-left">
                    <h4>Payment Information</h4>
                    <p>
                        @if (!empty($settings['bank_name']))
                            Bank Name: {{ $settings['bank_name'] }}<br>
                        @endif
                        @if (!empty($settings['bank_account_number']))
                            Account Number: {{ $settings['bank_account_number'] }}<br>
                        @endif
                        @if (!empty($settings['bank_ifsc_code']))
                            IFSC Code: {{ $settings['bank_ifsc_code'] }}<br>
                        @endif
                        @if (!empty($settings['bank_account_name']))
                            Account Name: {{ $settings['bank_account_name'] }}
                        @endif
                    </p>
                </div>
            @endif

            <div class="footer-right" style="width:0px !important;">
                <div class="signature-line">
                    <p>Customer Signature</p>
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
