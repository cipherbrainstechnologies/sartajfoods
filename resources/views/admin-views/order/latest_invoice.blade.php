<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body style="-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;">
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;background-color:#1d2e85;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;">
                <h1 style="margin:0;padding:5px 0;font-size:30px;line-height:1.25em;text-align:center;color:#fff;text-transform:uppercase;">Invoice</h1>
            </td>
        </tr>
    </table>
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;width:50%;">
            </td>
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;width:50%;text-align:right;">
                <p style="margin:0;padding:5px 0;font-size:18px;line-height:1.25em;text-align:right;color:#000;"><strong style="margin:0 5px 0 0;">{{translate('Date')}}:</strong>{{date('d M Y h:i a',strtotime($order['created_at']))}}</p> 
            </td>
        </tr>
    </table>
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;width:54%;vertical-align:bottom;">
                <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0 60px 0 0;">
                        <th style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;font-size:18px;line-height:1.5em;color:#000;font-weight:700;text-align:left;">{{ translate('Customer Name') }}:</th>
                    </tr>
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0 60px 0 0;">
                        <td style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;font-size:18px;line-height:1.5em;color:#000;border-bottom:1px solid #000;">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</td>
                    </tr>
                </table>
                <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <th style="width:40%;border:none;border-collapse:collapse;margin:0;padding:0;font-size:18px;line-height:1.5em;color:#000;font-weight:700;text-align:left;">{{translate('Total Payable Amt.')}}</th>
                        <td style="width:60%;border:none;border-collapse:collapse;margin:0;padding:0;font-size:18px;line-height:1.5em;color:#000;border-bottom:1px solid #000;text-align:center;"> {{Helpers::set_symbol($totalAmt)}}</td>
                    </tr>
                </table>
            </td>
            <td style="margin:0;padding:0 0 0 20px;border:none;border-collapse:collapse;width:46%;vertical-align:bottom;">
                <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:28%;min-height:28px;">{{ translate('Add') }} :</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:72%;min-height:28px;">
                            {{ $order->delivery_address['contact_person_name'] }} </br>
                            {{ $order->delivery_address['address'] }} {{ $order->delivery_address['road'] }} <br/>
                            {{ $order->delivery_address['house'] }} {{ $order->delivery_address['floor'] }} <br/>
                            {{ $order->delivery_address['city'] }} {{ $order->delivery_address['state'] }} {{$order->delivery_address['post_code']}}
                        </td>
                    </tr>
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:28%;min-height:28px;">{{ translate('Phone') }} :</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:72%;min-height:28px;">{{$order->delivery_address['contact_person_number']}}</td>
                    </tr>
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:28%;min-height:28px;">Reg. No. :</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:72%;min-height:28px;">{{$order['id']}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width:100%;margin-top:20px;border-collapse:collapse;">
        <thead>
            <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;background-color:rgba(29,46,133,0.2);">
                <th style="border:1px solid #000;padding:4px;font-size:18px;line-height:22px;color:#000;font-weight:700;text-align:center;width:54%;">{{translate('PRODUCT NAME')}}</th>
                <th style="border:1px solid #000;padding:4px;font-size:18px;line-height:22px;color:#000;font-weight:700;text-align:center;width:14%;">{{translate('QTY.')}}</th>
                <th style="border:1px solid #000;padding:4px;font-size:18px;line-height:22px;color:#000;font-weight:700;text-align:center;width:14%;">{{translate('PRICE')}}<BR/>PER PC.</th>
                <!-- <th style="border:1px solid #000;padding:4px;font-size:18px;line-height:22px;color:#000;font-weight:700;text-align:center;width:14%;">{{translate('Discount')}}<BR/>PER PC.</th> -->
                <th style="border:1px solid #000;padding:4px;font-size:18px;line-height:22px;color:#000;font-weight:700;text-align:center;width:18%;">{{translate('TOTAL')}}<BR/>{{translate('AMT.')}}</th>
            </tr>
        </thead>
        @php($sub_total=0)
        @php($amount=0)
        @php($total_Eight_tax=0)
        @php($total_Ten_tax=0)
        @php($total_dis_on_pro=0)
        @php($total_item_discount=0)
        @php($price_after_discount=0)
        @php($updated_total_tax=0)
        @php($vat_status = '')
        <tbody>
        @foreach($order->details as $detail)
        
            @if($detail->product_details !=null)
                @php($product = json_decode($detail->product_details, true))
                @php($amount=($detail['price'])*$detail['quantity'])
                <tr>
                    <td style="border:1px solid #000;padding:2px 20px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:left;width:54%;min-height:26px;">{{$product['name']}}</td>
                    <td style="border:1px solid #000;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:14%;min-height:26px;"> {{$detail['quantity']}}</td>
                    <td style="border:1px solid #000;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:14%;min-height:26px;">{{ Helpers::set_symbol($detail['price']) }}</td>
                    <!-- <td style="border:1px solid #000;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:14%;min-height:26px;">{{ Helpers::set_symbol($detail['discount_on_product']) }}</td> -->
                    <td style="border:1px solid #000;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:18%;min-height:26px;">{{ Helpers::set_symbol($amount)}}</td>
                </tr>
                @if($detail['tax_amount'] == '8')
                    @php($total_Eight_tax+=$detail['tax_amount']*$detail['quantity'])
                @else
                    @php($total_Ten_tax+=$detail['tax_amount']*$detail['quantity'])
                @endif
                @php($updated_total_tax+= $detail['vat_status'] === 'included' ? 0 : $detail['tax_amount']*$detail['quantity'])
                @php($vat_status = $detail['vat_status'])
                @php($total_item_discount += $detail['discount_on_product'] * $detail['quantity'])
                @php($price_after_discount+=$amount)
            @endif
        @endforeach
        @php($total_item_discount = $total_item_discount + $order['coupon_discount_amount'])
        @php($sub_total+=$price_after_discount)
        </tbody>
    </table>
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:100%;min-height:26px;"> &nbsp;   &nbsp; </td>
        </tr>
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:100%;min-height:26px;"> &nbsp;   &nbsp; </td>
        </tr>
    </table>
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;width:54%;vertical-align:bottom;">
                <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:30%;min-height:28px;">Note</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:70%;min-height:28px;">This Mark is for 8%</td>
                    </tr>
                </table>
            </td>
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;width:46%;vertical-align:bottom;">
                <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">Total Without Tax.</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">{{ Helpers::set_symbol($sub_total) }}</td>
                    </tr>
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">Discount Amount</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">- {{ Helpers::set_symbol($total_item_discount) }}</td>
                    </tr>
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">Sub Total</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;"> {{ Helpers::set_symbol($sub_total - $total_item_discount) }}</td>
                    </tr>
                    @if(!empty($EightPercentTax) && isset($EightPercentTax))
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">8% Consumption Tax.</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">{{ Helpers::set_symbol($EightPercentTax) }}</td>
                    </tr>
                    @endif
                    @if(!empty($TenPercentTax) && isset($TenPercentTax))
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">10% Consumption Tax.</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">{{ Helpers::set_symbol($TenPercentTax) }}</td>
                    </tr>
                    @endif
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">Delivery Charge</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">{{ Helpers::set_symbol($order->delivery_charge) }}</td>
                    </tr>
                    

                    
                    <!-- <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:55%;min-height:28px;">10% Consumption Tax.</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:left;width:5%;min-height:28px;">¥</td>
                        <td style="border:none;padding:3px 4px;font-size:18px;line-height:22px;color:#000;font-weight:400;text-align:right;width:40%;min-height:28px;">15,693</td>
                    </tr> -->
                 
                    <tr style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;border-bottom:1px solid #000;">
                        <td style="border:none;padding:3px 4px;font-size:22px;line-height:22px;color:#000;font-weight:700;text-align:left;width:55%;min-height:28px;">TOTAL</td>
                        <td style="border:none;padding:3px 4px;font-size:22px;line-height:22px;color:#000;font-weight:700;text-align:left;width:5%;min-height:28px;"></td>
                        <td style="border:none;padding:3px 4px;font-size:22px;line-height:22px;color:#000;font-weight:700;text-align:right;width:40%;min-height:28px;">{{Helpers::set_symbol($totalAmt)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width:100%;border:none;border-collapse:collapse;margin:0;padding:0;">
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:100%;min-height:26px;"> &nbsp;   &nbsp; </td>
        </tr>
        <tr style="margin:0;padding:0;border:none;border-collapse:collapse;">
            <td style="margin:0;padding:0;border:none;border-collapse:collapse;padding:2px 4px;font-size:16px;line-height:22px;color:#000;font-weight:400;text-align:right;width:100%;min-height:26px;border-bottom:1px solid #000;"></td>
        </tr>
    </table>
</body>
</html>