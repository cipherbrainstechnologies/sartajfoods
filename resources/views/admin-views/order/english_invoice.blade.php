<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice</title>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
		body{font-family: 'Poppins', sans-serif;font-size: 14px}
		.invoice_table_product tr,.invoice_table_product th {border: 1px solid #3e4493;padding: 10px 20px;border-collapse: collapse;}
		.invoice_table_product td {border: 1px solid #3e4493;padding: 5px 10px;border-collapse: collapse;}
		table.invoice_table_product,.invoice_table_info {border-collapse: collapse;}
		.invoice_table_info tr,.invoice_table_info td{border-bottom: 1px solid #3e4493;}
		span.icon_8 {padding: 0 5px 0 0;width: 15px;display: inline-block;}
		@media print {
		  @page { size: portrait; margin:0.2cm 0.5cm 0.2cm 0.5cm;}
		  html, body {font-size: 12px;font-family: calibri:margin:0;-webkit-print-color-adjust: exact !important;color-adjust: exact !important;padding:0;min-height:130mm;}
		  span.icon_8 {width: 12px;}
		  .invoice_table_product td {padding: 5px;}
		}
	</style>
</head>
<body style="-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;">
	<table width="100%" class="invoice_table">
		<tr style="background: #3e4493;">
			<td>
				<h2 style="margin: 10px 0;color: #fff;text-align: center;font-size: 15px;">INVOICE</h2>
			</td>
		</tr>
		<tr>
			<td>
				{{--  <p style="margin: 10px 0;color: #000;text-align: right;">Date: 04 Jan 2024 01:08 am</p>  --}}
				@php 
					$dateTime = new DateTime($order->created_at);
				@endphp
                <p style="margin: 10px 0;color: #000;text-align: right;">Date : {{$dateTime->format("d M Y h:i a")}}</p>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="50%">
							<h3 style="margin-top: 0;">{{$order->delivery_address['full_name']}}</h3>
							<p style="margin-bottom: 0;">{{$order->delivery_address['address']}}</p>
							<p style="border-bottom: 2px solid #3e4493;margin: 0; padding: 0 0 10px;"><b>Tel </b>
                                {{$order->delivery_address['contact_person_number']}}
                            </p>
							<table width="100%">
								<tr>
									<td width="70%"><h3 style="margin: 0;">Total Payable Amount</h3></td>
									<td width="30%"><h3 style="margin: 0;">{{$totalAmt}}</h3></td>
								</tr>
							</table>
						</td>
						<td width="50%" style="padding-left: 30px;">
							<p style="margin:0"><b>{{$order->shop_detail['shop_name']}}</b></p>
							<p style="margin:0">{{$order->shop_detail['address']}}</p>
							<p style="margin:0"><b>Tel</b>{{$order->shop_detail['phone']}}</p>
							<p style="margin:0"><b>登録番号</b>T4120901027786</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" class="invoice_table_product">
					<tr style="background: #a0a3ca;">
						<th width="50%" style="text-align: center;">Product Name</th>
						<th width="10%" style="text-align: center;">Qty.</th>
						<th width="20%" style="text-align: center;">Price Per pc.</th>
						<th width="20%" style="text-align: center;">Total Amount</th>
					</tr>
                    @if(!empty($order->details))
                        @foreach($order->details as $key => $detail)
                            @php $productDetail = json_decode($detail->product_details,true); @endphp
                            <tr>
                                <td width="50%">
                                    <span class="icon_8">
                                        @if($productDetail['tax'] == 8)
                                            ※ 
                                        @endif
                                    </span>
                                    {{$productDetail['name']}}
                                </td>
                                <td width="10%" style="text-align:right;">{{$order->details[$key]['quantity']}}</td>
                                <td width="20%" style="text-align:right;">¥{{$productDetail['actual_price']}}</td>
                                <td width="20%" style="text-align:right;">¥{{$productDetail['actual_price'] * $order->details[$key]['quantity']}}</td>
                            </tr>
                        @endforeach
                    @endif
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="50%" valign="bottom">Note ※ This Mark is for 8%</td>
						<td width="50%">
							<table width="100%" class="invoice_table_info">
								<tr>
									<td width="55%">Total Without Tax.</td>
									<td width="5%">¥</td>
									<td width="40%" style="text-align:right;">{{$subTotal - $EightPercentTax - $TenPercentTax}}</td>
								</tr>
                                @if($totalTaxPercent['TotalEightPercentTax']!=0)
                                    <tr>
                                        <td width="55%">8% Tax</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$totalTaxPercent['TotalEightPercentTax']}}</td>
                                    </tr>
                                @endif
                                @if($totalTaxPercent['TotalTenPercentTax']!=0)
                                    <tr>
                                        <td width="55%">10% Tax</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$totalTaxPercent['TotalTenPercentTax']}}</td>
                                    </tr>
                                @endif
                                @if($EightPercentTax != 0)
                                    <tr>
                                        <td width="55%">Consumption Tax 8%</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$EightPercentTax}}</td>
                                    </tr>
                                @endif
                                @if($TenPercentTax != 0)
                                    <tr>
                                        <td width="55%">Consumption Tax 10%</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$TenPercentTax}}</td>
                                    </tr>
                                @endif
                                @if($totalDiscount != 0)
                                    <tr>
                                        <td width="55%">Discount Amount</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">-{{$totalDiscount}}</td>
                                    </tr>
                                @endif
                                @if($order->delivery_charge!=0)
                                    <tr>
                                        <td width="55%">Delivery Charge</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$order->delivery_charge}}</td>
                                    </tr>
                                @endif
                                @if($order->coupon_discount_amount != 0)
                                    <tr>
                                        <td width="55%">Coupon Discount Amount</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">-{{$order->coupon_discount_amount }}</td>
                                    </tr>
                                @endif
								<tr>
									<td width="55%"><h3 style="margin: 0;">Total</h3></td>
									<td width="5%"><h3 style="margin: 0;">¥</h3></td>
									<td width="40%" style="text-align:right;">{{$totalAmt}}<h3 style="margin: 0;"></h3></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>