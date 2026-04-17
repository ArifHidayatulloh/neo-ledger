<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Spk;
use App\Models\Invoice;
use App\Services\InvoiceService;

echo "Creating sample client and SPK...\n";
$client = Client::firstOrCreate(
    ['email' => 'client@example.com'],
    ['name' => 'Demo Client', 'contact_person' => 'Suwondo Risdianto']
);

$spk = Spk::firstOrCreate(
    ['spk_ref' => 'NEO.TEST/SPK/03/2026'],
    [
        'client_id' => $client->id,
        'total_contract' => 9000000,
        'dp_amount' => 2700000,
        'final_bill' => 6300000,
        'status' => 'draft',
    ]
);

echo "Creating invoice record...\n";
$invoice = new Invoice();
$invoice->spk_id = $spk->id;
$invoice->client_id = $client->id;
$invoice->total_contract = $spk->total_contract;
$invoice->dp_amount = $spk->dp_amount;
$invoice->final_bill = $spk->final_bill;
$invoice->bank_account = 'Bank Mandiri a.n Arif Hidayatulloh';
$invoice->status = 'draft';
$invoice->save();

echo "Rendering HTML and generating PDF...\n";
$html = view('invoices.pdf', ['invoice' => $invoice])->render();
$path = InvoiceService::storePdfForInvoice($invoice, $html);

echo "Result: ";
if ($path) {
    echo "PDF generated at storage/app/" . $path . "\n";
} else {
    echo "PDF generation failed or dompdf not available.\n";
}
