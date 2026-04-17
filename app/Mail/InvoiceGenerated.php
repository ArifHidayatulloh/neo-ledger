<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        $mail = $this->subject('Invoice ' . $this->invoice->invoice_number)
                     ->view('emails.invoice')
                     ->with(['invoice' => $this->invoice]);

        if ($this->invoice->pdf_path && file_exists(storage_path('app/' . $this->invoice->pdf_path))) {
            $mail->attach(storage_path('app/' . $this->invoice->pdf_path));
        }

        return $mail;
    }
}
