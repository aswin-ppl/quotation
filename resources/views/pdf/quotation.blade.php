<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quotation #{{ $quotation->quotation_number }}</title>
    <style type="text/css">
        @page {
            size: A4;
            margin: 10mm 15mm;
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
        }

        .page-container {
            background: white;
            padding: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        .product-page-break {
            page-break-after: always;
        }

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
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .company-info img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        .company-info h1 {
            font-size: 24px;
            color: #044b26;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 10px;
            color: #64748b;
            font-style: italic;
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
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .quotation-meta {
            background: #f1f5f9;
            padding: 10px 15px;
            border-left: 4px solid #044b26;
            font-size: 10px;
            text-align: left;
            display: inline-block;
        }

        .quotation-meta p {
            margin: 4px 0;
        }

        .quotation-meta strong {
            color: #044b26;
            font-weight: bold;
        }

        /* Address Section */
        .address-section {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .address-box {
            display: table-cell;
            width: 50%;
            padding: 18px;
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
            margin-bottom: 10px;
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
            line-height: 1.7;
        }

        /* Product Card */
        .product-card {
            border: 2px solid #cbd5e1;
            margin-bottom: 20px;
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
            padding: 20px;
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
            display: table;
            width: 100%;
        }

        .gallery-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 5px;
        }

        .gallery-item img {
            max-width: 100%;
            height: auto;
            max-height: 120px;
            border: 2px solid #e2e8f0;
            padding: 5px;
        }

        .gallery-item p {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
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
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Professional Header -->
        <div class="header-wrapper">
            <div class="header-top">
                <div class="company-info">
                    @if ($settings && isset($settings['company_logo']) && $settings['company_logo'])
                        <img src="{{ url('/storage/' . $settings['company_logo']) }}" alt="company-logo">
                    @endif
                    <h1>{{ $settings['company_name'] ?? 'Your Company' }}</h1>
                    <p class="company-tagline">Your Trusted Business Partner</p>
                </div>
                <div class="quotation-info">
                    <div class="quotation-badge">{{ $quotation->quotation_number }}</div>
                    <div class="quotation-meta">
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}</p>
                        <p><strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($quotation->created_at)->addDays(30)->format('d M Y') }}</p>
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
                        $address = $customer->addresses()->where('is_default', 1)->first() 
                                   ?? $customer->addresses()->first();
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
                    {{ $settings['company_city_name'] ?? 'City' }}, {{ $settings['company_district_name'] ?? 'District' }}<br>
                    {{ $settings['company_state_name'] ?? 'State' }} - {{ $settings['company_pincode_value'] ?? '000000' }}<br>
                    <strong>Email:</strong> {{ $settings['company_email'] ?? 'info@company.com' }}<br>
                    <strong>Phone:</strong> {{ $settings['company_mobile'] ?? '+91 XXXXX XXXXX' }}
                </p>
            </div>
        </div>

        <!-- Products Section -->
        @forelse ($quotation->products as $index => $product)
            <div class="product-card">
                <div class="product-header">
                    PRODUCT {{ $index + 1 }}
                    {{-- PRODUCT #{{ $index + 1 }} of {{ $quotation->products->count() }} --}}
                </div>

                <div class="product-content">
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <th style="padding: 8px; text-align: center; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 40px;">S.No</th>
                                <th style="padding: 8px; text-align: center; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 100px;">CAD Image</th>
                                <th style="padding: 8px; text-align: left; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0;">Description</th>
                                <th style="padding: 8px; text-align: center; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 50px;">Qty</th>
                                <th style="padding: 8px; text-align: center; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 70px;">Size (mm)</th>
                                <th style="padding: 8px; text-align: right; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 80px;">Cost/Unit</th>
                                <th style="padding: 8px; text-align: right; font-weight: bold; color: #475569; font-size: 10px; border: 1px solid #e2e8f0; width: 90px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border: 1px solid #e2e8f0;">
                                <td style="padding: 8px; text-align: center; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">1</td>
                                
                                <!-- Image Cell -->
                                <td style="padding: 8px; text-align: center; border: 1px solid #e2e8f0; vertical-align: top;">
                                    @php
                                        $cadImage = $product->images ? $product->images->where('type', 'cad')->first() : null;
                                        $imagePath = $cadImage ? url('/private-image/' . $cadImage->path) : url('/images/no-image.png');
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="{{ $product->name }}" style="max-width: 300px; height: auto; border: 1px solid #e2e8f0; padding: 2px;">
                                </td>
                                
                                <!-- Description Cell with Nested Table -->
                                <td style="border: 1px solid #e2e8f0; vertical-align: top;">
                                    <div style="font-weight: bold; color: #044b26; font-size: 11px;">{{ $product->name }}</div>
                                    
                                    @if ($product->descriptions && $product->descriptions->count() > 0)
                                        <!-- Nested Sub-Table for Specifications -->
                                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;">
                                            <tbody>
                                                @foreach ($product->descriptions as $desc)
                                                    <tr>
                                                        <td style="padding: 4px 6px; font-size: 8px; color: #475569; font-weight: bold; border: 1px solid #e2e8f0; background: #fafafa;">
                                                            {{ ucfirst(str_replace('_', ' ', $desc->key)) }}
                                                        </td>
                                                        <td style="padding: 4px 6px; font-size: 8px; color: #334155; border: 1px solid #e2e8f0;">
                                                            {{ $desc->value }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                                
                                <!-- Quantity -->
                                <td style="padding: 8px; text-align: center; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    {{ $product->quantity ?? $product->r_units ?? 1 }}
                                </td>
                                
                                <!-- Size -->
                                <td style="padding: 8px; text-align: center; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    {{ $product->size_mm ?? '-' }}
                                </td>
                                
                                <!-- Cost per Unit -->
                                <td style="padding: 8px; text-align: right; font-weight: bold; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    ₹{{ number_format($product->cost_per_units ?? 0, 2) }}
                                </td>
                                
                                <!-- Total Price -->
                                <td style="padding: 8px; text-align: right; font-weight: bold; color: #044b26; border: 1px solid #e2e8f0; vertical-align: top; font-size: 10px;">
                                    ₹{{ number_format($product->product_price ?? 0, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Additional Images Gallery -->
                    @if ($product->images && $product->images->where('type', 'extras')->count() > 0)
                        <div class="image-gallery">
                            <div class="gallery-title">Additional Images</div>
                            <div class="gallery-grid">
                                @foreach ($product->images->where('type', 'extras') as $image)
                                    <div class="gallery-item">
                                        <img src="{{ url('/private-image/' . $image->path) }}" alt="Extra Image">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Page break after each product -->
                    <div class="product-page-break"></div>
                </div>
            </div>
        @empty
            <div class="product-card">
                <div class="product-header">NO PRODUCTS</div>
                <div class="product-content">
                    <p class="text-center" style="color: #94a3b8; padding: 20px;">No products found for this quotation</p>
                </div>
            </div>
        @endforelse

        <!-- Page break before totals -->
        <div class="page-break"></div>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="totals-left">
                <div class="notes-box">
                    <h4>Terms & Conditions</h4>
                    <p>
                        • This quotation is valid for 30 days from the date of issue.<br>
                        • Prices are subject to change without prior notice.<br>
                        • Payment terms: As per agreement.<br>
                        • Delivery timeline will be confirmed upon order confirmation.<br>
                        • All disputes subject to jurisdiction only.
                    </p>
                </div>
            </div>
            <div class="totals-right">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)), 2) }}</td>
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
                        <td>₹{{ number_format($quotation->products->sum(fn($p) => ($p->r_units ?? 1) * ($p->product_price ?? 0)), 2) }}</td>
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
                <div class="footer-right" style="width:0% !important">
                    <div class="signature-line">
                        <p>Customer Signature</p>
                    </div>
                </div>
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
