<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
<p style="margin: 0 0 4px;">© {{ date('Y') }} <strong>{{ config('app.name') }}</strong></p>
<p style="margin: 0; font-size: 11px; color: #b0b0b0;">Cashflow Management System</p>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
