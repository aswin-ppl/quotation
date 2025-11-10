<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quotation #{{ $quotation->id }}</title>
    <style type="text/css">
        @page {
            margin: 10mm 15mm;
            size: A4 portrait;
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

        /* Main Container with Border */
        .page-container {
            /* border: 2px solid #1e40af; */
            border: 2px solid transparent;
            padding: 20px;
            margin: 0 auto;
        }

        /* Professional Header with Brand Identity */
        .header-wrapper {
            border-bottom: 3px solid #1e40af;
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
            width: 60%;
            vertical-align: top;
        }

        .company-info h1 {
            font-size: 32px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .company-tagline {
            font-size: 10px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 10px;
        }

        .quotation-info {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .quotation-badge {
            display: inline-block;
            background: #1e40af;
            color: #ffffff;
            padding: 8px 20px;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .quotation-meta {
            background: #f1f5f9;
            padding: 10px 15px;
            border-left: 4px solid #1e40af;
            text-align: left;
            display: inline-block;
            width: 100%;
        }

        .quotation-meta p {
            margin: 4px 0;
            font-size: 10px;
            color: #334155;
        }

        .quotation-meta strong {
            color: #1e40af;
            font-weight: bold;
            display: inline-block;
            width: 80px;
        }

        /* Address Section - Side by Side Layout */
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
            border-bottom: 2px solid #1e40af;
            padding-bottom: 5px;
            display: inline-block;
        }

        .address-box .company-name {
            font-size: 14px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        .address-box p {
            font-size: 10px;
            color: #475569;
            line-height: 1.7;
        }

        /* Enhanced Product Table */
        .table-wrapper {
            margin: 20px 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        .product-table thead {
            background: linear-gradient(to bottom, #1e40af 0%, #1e3a8a 100%);
            background: #1e40af;
        }

        .product-table th {
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.8px;
            color: #ffffff;
            border-right: 1px solid #3b82f6;
        }

        .product-table th:last-child {
            border-right: none;
        }

        .product-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .product-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .product-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .product-table td {
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
            color: #334155;
            border-right: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .product-table td:last-child {
            border-right: none;
        }

        .product-table td.text-left {
            text-align: left;
        }

        .product-table td img {
            border: 2px solid #e2e8f0;
            max-width: 70px;
            height: 70px;
            display: block;
            margin: 0 auto;
        }

        .product-name {
            font-weight: bold;
            color: #1e40af;
            font-size: 11px;
        }

        .serial-number {
            font-weight: bold;
            color: #64748b;
            background: #f1f5f9;
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
        }

        /* Description Styling */
        .desc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .desc-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .desc-table tr:last-child {
            border-bottom: none;
        }

        .desc-table td {
            text-align: left;
            padding: 5px 8px;
            border: none;
        }

        .desc-table td:first-child {
            font-weight: bold;
            color: #64748b;
            width: 35%;
            font-size: 9px;
        }

        .desc-table td:last-child {
            color: #334155;
        }

        /* Professional Totals Section */
        .totals-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .totals-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 20px;
        }

        .totals-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1e40af;
            padding: 15px;
        }

        .notes-box h4 {
            font-size: 11px;
            color: #1e40af;
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

        .totals-table tr.subtotal-row {
            background: #f8fafc;
        }

        .totals-table tr.grand-total {
            background: #1e40af;
            border: none;
        }

        .totals-table tr.grand-total td {
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            padding: 15px 12px;
        }

        .totals-table td {
            padding: 10px 12px;
            border: none;
            font-size: 10px;
        }

        .totals-table td:first-child {
            font-weight: bold;
            color: #475569;
            text-align: left;
            width: 65%;
        }

        .totals-table td:last-child {
            text-align: right;
            color: #1e40af;
            font-weight: bold;
            font-size: 11px;
        }

        .totals-table tr.grand-total td:first-child,
        .totals-table tr.grand-total td:last-child {
            color: #ffffff;
        }

        /* Professional Footer */
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
            vertical-align: top;
            text-align: right;
        }

        .footer h4 {
            font-size: 10px;
            color: #1e40af;
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
            border-top: 2px solid #1e40af;
            width: 200px;
            padding-top: 5px;
            text-align: center;
        }

        .signature-line p {
            font-size: 10px;
            color: #475569;
            font-weight: bold;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Page Break Container for Multi-page */
        .page-break-container {
            border: 2px solid #1e40af;
            padding: 20px;
            margin: 0 auto;
            margin-top: 10mm;
        }

        /* Utility Classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Professional Header -->
        <div class="header-wrapper">
            <div class="header-top">
                <div class="company-info">
                    <h1>{{ $settings['company_name'] ?? '' }}</h1>
                    <p class="company-tagline">Your Trusted Business Partner</p>
                </div>
                <div class="quotation-info">
                    <div class="quotation-badge">QUOTATION</div>
                    <div class="quotation-meta">
                        <p><strong>Quote ID:</strong> {{ $quotation->id }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($quotation->date)->format('d M Y') }}</p>
                        <p><strong>Valid Until:</strong>
                            {{ \Carbon\Carbon::parse($quotation->date)->addDays(30)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="address-section">
            <div class="address-box">
                <h3>Bill To</h3>
                <span class="company-name">{{ $quotation->customer->name ?? 'Unknown Customer' }}</span>
                <p>
                    @php
                        $address = $quotation->customer->addresses->first();
                    @endphp
                    @if ($address)
                        @if ($defaultAddress == 1)
                            {{ $address->address_line_1 }},<br>
                        @else
                            {{ $address->address_line_2 }},<br>
                        @endif
                        {{ $address->city->name }}, {{ $address->district->name }}<br>
                        {{ $address->state->name }} - {{ $address->pincode->code }}
                    @else
                        <em>No address found</em>
                    @endif
                </p>
            </div>

            <div class="address-box">
                <h3>From</h3>

                <span class="company-name">{{ $settings['company_name'] ?? '' }}</span>
                <p>
                    {{ $settings['company_address'] ?? 'Company Street' }}<br>
                    {{ $settings['company_city_name'] ?? 'City' }}, {{ $settings['company_district_name'] ?? 'District' }}<br>
                    {{ $settings['company_state_name'] ?? 'State' }} - {{ $settings['company_pincode_value'] ?? '000000' }}<br>
                    <strong>Email:</strong> {{ $settings['company_email'] ?? 'info@company.com' }}<br>
                    <strong>Phone:</strong> {{ $settings['company_mobile'] ?? '+91 XXXXX XXXXX' }}
                </p>
            </div>
        </div>

        <!-- Product Table -->
        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 10%;">Image</th>
                        <th style="width: 18%;">Product</th>
                        <th style="width: 32%;">Specifications</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 13%;">Unit Price</th>
                        <th style="width: 14%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotation->items as $i => $item)
                        <tr>
                            <td><span class="serial-number">{{ $i + 1 }}</span></td>
                            <td>
                                <img src="{{ optional($item->product)->image ? public_path('storage/' . $item->product->image) : public_path('images/no-image.png') }}"
                                    alt="{{ $item->product_name }}">
                            </td>
                            <td class="text-left">
                                <span class="product-name">{{ $item->product_name }}</span>
                            </td>
                            <td style="padding: 8px;">
                                @php
                                    $desc = is_string($item->description)
                                        ? json_decode($item->description, true)
                                        : (is_array($item->description)
                                            ? $item->description
                                            : []);
                                @endphp

                                @if (!empty($desc))
                                    <table class="desc-table">
                                        @foreach ($desc as $key => $value)
                                            <tr>
                                                <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">No specifications</span>
                                @endif
                            </td>
                            <td><strong>{{ $item->quantity }}</strong></td>
                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                            <td><strong style="color: #1e40af;">₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>

                        @if (($i + 1) % 10 == 0 && $i + 1 < count($quotation->items))
                </tbody>
            </table>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="page-break-container">
        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 10%;">Image</th>
                        <th style="width: 18%;">Product</th>
                        <th style="width: 32%;">Specifications</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 13%;">Unit Price</th>
                        <th style="width: 14%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

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
                    <tr class="subtotal-row">
                        <td>Subtotal</td>
                        <td>₹{{ number_format($quotation->sub_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>- ₹{{ number_format($quotation->discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax (GST)</td>
                        <td>+ ₹{{ number_format($quotation->tax, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GRAND TOTAL</td>
                        <td>₹{{ number_format($quotation->grand_total, 2) }}</td>
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
                <div class="footer-right">
                    <div class="signature-line">
                        <p>Authorized Signature</p>
                    </div>
                </div>
            </div>
            <p style="text-align: center; margin-top: 20px; font-size: 9px; color: #94a3b8; font-style: italic;">
                Thank you for your business! For any queries, please contact us.
            </p>
        </div>
    </div>
</body>

</html>