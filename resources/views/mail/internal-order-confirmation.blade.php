@extends('mail.partials.base-order-confirmation')

@section('greeting')
<p style="margin:0 0 16px">
You've received the following order from
{{ $order->payload->customer->first_name }} {{ $order->payload->customer->last_name }}:
</p>
@endsection

@section('meta')
<h2 style="color:#1d3675;display:block;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
Marketing Source
</h2>
<ul>
@if($user->tracking->params->utm_source ?? false)
    <li>UTM Source: {{ $user->tracking->params->utm_source }}</li>
@endif
@if($user->tracking->params->utm_medium ?? false)
    <li>UTM Medium: {{ $user->tracking->params->utm_medium }}</li>
@endif
@if($user->tracking->params->utm_term ?? false)
    <li>UTM Term: {{ $user->tracking->params->utm_term }}</li>
@endif
@if($user->tracking->params->utm_content ?? false)
    <li>UTM Content: {{ $user->tracking->params->utm_content }}</li>
@endif
@if($user->tracking->params->utm_campaign ?? false)
    <li>UTM Campaign: {{ $user->tracking->params->utm_campaign }}</li>
@endif
@if($user->tracking->params->fbclid ?? false)
    <li>FBCLID: {{ $user->tracking->params->fbclid }}</li>
@endif
@if($user->tracking->params->msclkid ?? false)
    <li>MSCLKID: {{ $user->tracking->params->msclkid }}</li>
@endif
@if($user->tracking->params->gclid ?? false)
    <li>GCLID: {{ $user->tracking->params->gclid }}</li>
@endif
@if($user->tracking->referer ?? false)
    <li>Original Referrer: {{ $user->tracking->referer }}</li>
@endif
@if($user->tracking->referer ?? false)
    <li>Landing Page: {{ $user->tracking->referer }}</li>
@endif
@if($user->tracking->ip_address ?? false)
    <li>User IP: {{ $user->tracking->ip_address }}</li>
@endif
@if($user->tracking->user_agent ?? false)
    <li>User Agent: {{ $user->tracking->user_agent }}</li>
@endif
</ul>
@endsection