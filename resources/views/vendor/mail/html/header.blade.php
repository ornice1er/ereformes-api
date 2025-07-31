@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src= "{{ asset('Images/logo.png') }}" class="logo" alt="Laravel Logo"  style="height: 60px; width: auto; max-width: 200px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
