<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public static function generateInvoiceNumber(int $year, int $month): array
    {
        $max = Invoice::where('year', $year)->where('month', $month)->max('sequence');
        $seq = $max ? $max + 1 : 1;
        $seqStr = str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
        $invoiceNumber = sprintf('INV/%04d/%02d/%s', $year, $month, $seqStr);

        return ['invoice_number' => $invoiceNumber, 'sequence' => $seq];
    }

    public static function storePdfForInvoice($invoice, $html)
    {
        // Try barryvdh wrapper first
        try {
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = app('\Barryvdh\\DomPDF\\Facade\\Pdf');
                if (method_exists($pdf, 'loadHTML')) {
                    $dom = $pdf->loadHTML($html);
                    $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
                    Storage::disk(config('filesystems.default'))->put($filename, $dom->output());
                    return $filename;
                }
                if (method_exists($pdf, 'loadView')) {
                    $dom = $pdf->loadView('invoices.pdf', ['invoice' => $invoice]);
                    $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
                    Storage::disk(config('filesystems.default'))->put($filename, $dom->output());
                    return $filename;
                }
            }
        } catch (\Throwable $e) {
            // fallback to Dompdf directly
        }

        // Fallback to using Dompdf directly
        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
            Storage::disk(config('filesystems.default'))->put($filename, $output);
            return $filename;
        }

        return null;
    }
}
