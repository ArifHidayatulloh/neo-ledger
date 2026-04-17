@extends('layouts.app')

@section('content')
{{-- <div class="mt-3 flex gap-2 justify-end">
    <a href="{{ route('invoices.downloadPdf', $invoice->id) }}" class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Download PDF</a>
    <a href="{{ route('invoices.sendEmail', $invoice->id) }}" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm">Send Email</a>
</div> --}}
@include('invoices.partials.content')
@endsection
