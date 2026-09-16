@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<table cellpadding="0" cellspacing="0" role="presentation" align="center">
<tr>
<td style="padding-right: 12px;">
<div class="brand-mark">AAI</div>
</td>
<td style="text-align: left;">
<div class="brand-name">{{ $slot }}</div>
<div class="brand-tagline">Certification &amp; Training</div>
</td>
</tr>
</table>
</a>
</td>
</tr>
