<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        {!! file_get_contents(public_path('build/assets/app-CkPajAo4.css')) !!}

        /* PDF print overrides for a cleaner, professional invoice */
        @page {
            margin: 20mm;
        }

        html,
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #111827;
            background: #fff;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .max-w-5xl {
            max-width: 820px;
            margin: 0 auto;
            background: transparent;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 24px 18px !important;
        }

        .rounded-2xl,
        .shadow-sm {
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .text-sm {
            font-size: 12px !important;
        }

        .text-lg {
            font-size: 16px !important;
        }

        .text-xs {
            font-size: 11px !important;
        }

        .h-12 {
            height: 48px !important;
        }

        .border-gray-100 {
            border-color: #e5e7eb !important;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            border-bottom: 1px solid #ddd;
            padding: 10px 8px;
            font-weight: 600;
            color: #374151;
        }

        table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f1f1;
            color: #111827;
        }

        .font-semibold {
            font-weight: 600 !important;
        }

        /* Payment box */
        .bg-gray-50 {
            background: #fafafa !important;
        }

        .rounded-lg {
            border-radius: 6px !important;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 28px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }

        /* Header vertical centering — dompdf has limited flexbox support, use table-cell */
        .invoice-header-row {
            display: table !important;
            width: 100% !important;
        }

        .invoice-header-logo {
            display: table-cell !important;
            vertical-align: middle !important;
            width: 50%;
        }

        .invoice-header-meta {
            display: table-cell !important;
            vertical-align: middle !important;
            text-align: right !important;
            width: 50%;
        }
    </style>
</head>

<body>
    @php
        $logoPath = null;
        $candidates = ['logo.png', 'logo.jpg', 'logo.jpeg', 'logo.svg'];
        foreach ($candidates as $f) {
            $p = public_path('images/' . $f);
            if (file_exists($p)) {
                $logoPath = $p;
                break;
            }
        }
        if ($logoPath) {
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            if ($ext === 'png') {
                $mime = 'image/png';
            } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                $mime = 'image/jpeg';
            } elseif ($ext === 'svg') {
                $mime = 'image/svg+xml';
            } else {
                $mime = mime_content_type($logoPath) ?: 'application/octet-stream';
            }
            $data = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:' . $mime . ';base64,' . $data;
        } else {
            $logoSrc = null;
        }
    @endphp

    @include('invoices.partials.content')

</body>

</html>
