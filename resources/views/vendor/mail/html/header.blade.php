@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'Subly')
<img src="{{ asset('favicon-v2.png') }}" class="logo" alt="Subly Logo" style="height: 36px; width: 36px; border-radius: 8px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
