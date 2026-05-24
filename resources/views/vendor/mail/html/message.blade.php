<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<span style="color: #5e6ada;">sub</span>ly
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
© {{ date('Y') }} {{ config('app.name') }}. {{ __('Empowering your static deployments.') }}<br>
<a href="{{ config('app.url') }}" style="color: #71717a; text-decoration: none; font-size: 11px; margin-top: 10px; display: inline-block;">Manage your account</a>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
