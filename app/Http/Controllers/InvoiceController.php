<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Spk;
use App\Models\Client;
use App\Services\InvoiceService;
use App\Mail\InvoiceGenerated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function create(Spk $spk = null)
    {
        $clients = Client::orderBy('name')->get();
        $spks = Spk::orderBy('spk_ref')->get();

        return view('invoices.create', compact('spk', 'clients', 'spks'));
    }

    public function index()
    {
        $query = Invoice::with(['client', 'spk'])->orderBy('created_at', 'desc');

        if (request()->filled('search')) {
            $s = request('search');
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('spk', function ($q2) use ($s) {
                      $q2->where('spk_ref', 'like', "%{$s}%");
                  })->orWhereHas('client', function ($q3) use ($s) {
                      $q3->where('name', 'like', "%{$s}%");
                  });
            });
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('client_id')) {
            $query->where('client_id', request('client_id'));
        }

        if (request()->filled('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $invoices = $query->paginate(15)->withQueryString();

        $clients = \App\Models\Client::orderBy('name')->get();

        return view('invoices.index', compact('invoices', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spk_id' => 'nullable|exists:spks,id',
            'client_id' => 'required|exists:clients,id',
            'bank_account' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_number' => 'nullable|string|max:64',
            'due_date' => 'nullable|date',
        ]);

        $spk = $validated['spk_id'] ? Spk::find($validated['spk_id']) : null;

        // If SPK has outstanding final bill, require explicit confirmation
        if ($spk && ($spk->final_bill > 0 || ($spk->total_contract - $spk->dp_amount) > 0)) {
            if (!$request->filled('confirm_unpaid')) {
                return back()->withInput()->withErrors(['confirm_unpaid' => 'SPK belum lunas. Harap konfirmasi sebelum membuat invoice.']);
            }
        }

        $invoice = new Invoice();
        $invoice->spk_id = $spk ? $spk->id : null;
        $invoice->client_id = $validated['client_id'];
        $invoice->total_contract = $spk ? $spk->total_contract : 0;
        $invoice->dp_amount = $spk ? $spk->dp_amount : 0;
        $invoice->final_bill = $spk ? ($spk->total_contract - $spk->dp_amount) : 0;
        $invoice->bank_account = $validated['bank_account'] ?? null;
        $invoice->bank_name = $validated['bank_name'] ?? null;
        $invoice->bank_number = $validated['bank_number'] ?? null;
        $invoice->due_date = $validated['due_date'] ?? null;
        $invoice->status = 'draft';
        $invoice->save();

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        // render the standalone PDF view (no layout) to avoid full app chrome in PDF
        $html = view('invoices.pdf', compact('invoice'))->render();
        $path = InvoiceService::storePdfForInvoice($invoice, $html);
        if ($path) {
            $invoice->pdf_path = $path;
            $invoice->save();
            return Storage::download($path);
        }
        return redirect()->back()->with('error', 'PDF generation not available.');
    }

    public function sendEmail(Invoice $invoice)
    {
        if (!$invoice->client || !$invoice->client->email) {
            return redirect()->back()->with('error', 'Client email not found.');
        }

        if ($invoice->pdf_path && !Storage::exists($invoice->pdf_path)) {
            // try to regenerate
            $html = view('invoices.pdf', compact('invoice'))->render();
            $invoice->pdf_path = InvoiceService::storePdfForInvoice($invoice, $html);
            $invoice->save();
        }

        Mail::to($invoice->client->email)->send(new InvoiceGenerated($invoice));
        $invoice->status = 'sent';
        $invoice->sent_at = now();
        $invoice->save();

        return redirect()->back()->with('success', 'Invoice emailed.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->status = 'paid';
        $invoice->paid_at = now();
        $invoice->save();

        if ($invoice->spk) {
            $invoice->spk->is_finalized = true;
            $invoice->spk->status = 'paid';
            $invoice->spk->save();
        }

        return redirect()->back()->with('success', 'Invoice marked as paid and SPK finalized.');
    }
}
