<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Discount Lots</title>
</head>
<body marginwidth="0" marginheight="0" style="padding:0">
<div id="wrapper" dir="ltr" style="background-color:#f7f7f7;margin:0;padding:70px 0;width:100%">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tr>
            <td align="center" valign="top">
                <div id="template_header_image">
                    <p style="margin-top:0"><img src="https://discountlots.com/wp-content/uploads/2022/02/discount-lots-logo.png" alt="Discount Lots" style="border: none; display: inline-block; font-size: 14px; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; vertical-align: middle; max-width: 200px; margin-left:0; margin-right: 0;"></p>
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color:#1d3675;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0">
                                <tr>
                                    <td id="header_wrapper" style="padding:36px 48px;display:block">
                                        <h1 style="font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff;background-color:inherit">New Order: {{ $order->id }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                <tr>
                                    <td valign="top" id="body_content" style="background-color:#ffffff">
                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" style="padding:48px 48px 32px">
                                                    <div id="body_content_inner" style="color:#5c5c5c;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
                                                        @yield('greeting')
                                                        <h2 style="color:#1d3675;display:block;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                            <a class="link" href="{{ $order->getConfirmationUrl() }}" target="_blank" style="font-weight:normal;text-decoration:underline;color:#1d3675">[Order #{{ $order->id }}]</a> ({{ $order->created_at->format('Y-m-d') }})</h2>
                                                        <div style="margin-bottom:40px">
                                                            <table class="td" cellspacing="0" cellpadding="6" border="1" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
                                                                <thead>
                                                                <tr>
                                                                    <th class="td" colspan="2" width="50%" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Property</th>
                                                                    <th class="td" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Plan</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr class="order_item">
                                                                    <td class="td" colspan="2" style="color:#5c5c5c;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word">
                                                                        APN: {{ $order->property->apn }}<br>
                                                                        County: {{ $order->property->county }}<br>
                                                                        State: {{ $order->property->state }}
                                                                    </td>
                                                                    <td class="td" style="color:#5c5c5c;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
                                                                        @if($order->getPaymentCount() === 1)
                                                                            $ {{ number_format($order->getPaymentAmount(), 2, '.', '') }} one time payment
                                                                        @else
                                                                            $ {{ number_format($order->getMonthlyPaymentAmount(true), 2, '.', '') }} for {{ $order->payload->payments }} months
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <th class="td" scope="row" colspan="2" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
                                                                        @if($order->getPaymentCount() === 1)
                                                                            Initial Payment:
                                                                        @else
                                                                            Down Payment:
                                                                        @endif
                                                                    </th>
                                                                    <td class="td" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
                                                                        @if($order->getPaymentCount() === 1)
                                                                            $ {{ number_format($order->property->cash_price_current, 2, '.', '') }}
                                                                        @else
                                                                            $ {{ $order->property->getDownPayment() }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="td" scope="row" colspan="2" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Document Fee:</th>
                                                                    <td class="td" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">$ {{ number_format($order->property->getDocumentFee(), 2, '.' , '') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="td" scope="row" colspan="2" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Payment method:</th>
                                                                    <td class="td" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Credit Card - {{ $order->getCcLast4() }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="td" scope="row" colspan="2" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Total Paid:</th>
                                                                    <td class="td" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">
                                                                        $ {{ number_format($order->getPaymentAmount(), 2, '.', '') }}
                                                                    </td>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        @if($order->getPaymentCount() !== 1)
                                                        <div style="margin-bottom:40px">
                                                            <h2 style="color:#1d3675;display:block;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Recurring Payment Information:</h2>
                                                            <table class="td" cellspacing="0" cellpadding="6" border="1" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;margin-bottom:0.5em">
                                                                <thead>
                                                                <tr>
                                                                    <th class="td" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">ID</th>
                                                                    <th class="td" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Start date</th>
                                                                    <th class="td" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">End date</th>
                                                                    <th class="td" scope="col" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">Recurring total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="td" scope="row" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"><a href="{{ $order->getConfirmationUrl() }}" target="_blank" style="color:#1d3675;font-weight:normal;text-decoration:underline"># {{ $order->id }}</a></td>
                                                                    <td class="td" scope="row" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">{{ $order->getContractStartDate()->toDateString() }}</td>
                                                                    <td class="td" scope="row" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">{{ $order->getContractEndDate()->toDateString() }}</td>
                                                                    <td class="td" scope="row" style="color:#5c5c5c;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">
																					<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$ {{ number_format($order->getMonthlyPaymentAmount(true), 2, '.', '') }} / month<br>
																					<small>Next payment: {{ $order->created_at->clone()->addMonth()->toDateString() }}</small>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @endif

                                                        @yield('meta')

                                                        <h3 style="color:#1d3675;display:block;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:16px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left">Additional Details</h3>
                                                        <p style="margin:0 0 16px"><strong>Full Legal Name:</strong> {{ $order->payload->customer->full_legal_name }}</p>
                                                        <table id="addresses" cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0">
                                                            <tr>
                                                                <td valign="top" width="50%" style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0">
                                                                    <h2 style="color:#1d3675;display:block;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Billing address</h2>
                                                                    <address class="address" style="padding:12px;color:#5c5c5c;border:1px solid #e5e5e5">
                                                                        {{ $order->payload->customer->first_name }} {{ $order->payload->customer->last_name }}<br>
                                                                        {{ $order->payload->customer->address }} {{ $order->payload->customer->address2 }}<br>
                                                                        {{ $order->payload->customer->city }}, {{ $order->payload->customer->state }} {{ $order->payload->customer->postal_code }}<br>
                                                                        {{ $order->payload->customer->phone }}<br>
                                                                        {{ $order->payload->customer->email }}
                                                                    </address>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                    <tr>
                        <td valign="top" style="padding:0;border-radius:6px">
                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="2" valign="middle" id="credit" style="border-radius:6px;border:0;color:#858585;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0">
                                        <p style="margin:0 0 16px"><a href="http://discountlots.com">Discount Lots</a></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>