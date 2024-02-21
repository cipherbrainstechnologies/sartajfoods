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
		.invoice_table_product td {border: 1px solid #3e4493;padding: 2px 5px;border-collapse: collapse;}
		table.invoice_table_product,.invoice_table_info {border-collapse: collapse;}
		.invoice_table_info tr,.invoice_table_info td{border-bottom: 1px solid #3e4493;}
		span.icon_8 {padding: 0 5px 0 0;width: 15px;display: inline-block;}
		@media print {
		  @page { size: portrait; margin:0.2cm 0.5cm 0.2cm 0.5cm;}
		  html, body {font-size: 12px;font-family: calibri:margin:0;-webkit-print-color-adjust: exact !important;color-adjust: exact !important;padding:0;min-height:130mm;}
		  span.icon_8 {width: 12px;}
		  .invoice_table_product td,.invoice_table_product th {padding: 2px 5px;}
		}
	</style>
</head>
<body style="-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;">
	<table width="100%" class="invoice_table">
		<tr>
			<td>
				<table width="100%" style="border-collapse: collapse;">
					<tr>
						<td width="50%" style="padding: 0;">
							<table width="100%" style="border: 1px solid #000;border-collapse: collapse;">
								<tr>
                                    
									<td width="35%" style="border: 1px solid;text-align: center;"><img src="{{asset('/storage/restaurant/' . $order->shop_detail['shop_logo'])}}" style="width:100px" alt="logo"></td>
									<td width="65%">
										<table width="100%">
											<tr>
												<td width="100%">{{$order->shop_detail['shop_name']}}</td>
											</tr>
											<tr>
												<td width="100%">〒563-0043</td>
											</tr>
											<tr>
												<td width="100%">{{$order->shop_detail['address']}}</td>
											</tr>
											<tr>
												<td width="100%">Telephone No.： {{$order->shop_detail['phone']}}</td>
											</tr>
											<tr>
												<td width="100%">Registration No.： T4120901027786</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td width="50%" style="padding: 0;">
							<table width="100%" style="border: 1px solid #000;border-collapse: collapse;">
								<tr>
									<td width="70%">
										
										<table width="100%">
											<tr>
												<td>Order Id: </td>
												<td>{{$order->id}}</td>
											</tr>
                                            @php 
                                                $dateTime = new DateTime($order->created_at);
                                            @endphp
											<tr>
												<td  >Date :</td>
												<td >{{$dateTime->format("d/m/Y")}}</td>
											</tr>
											<tr>
												<td  >Time :</td>
												<td >{{$dateTime->format("h:i a")}}</td>
											</tr>
											
											<tr>
												<td  >Payment Method :</td>
												<td >Cash on Delivery</td>
											</tr>
											<tr>
												<td  >Shipping Method :</td>
												<td >Regular Delivery</td>
											</tr>
										</table>
									</td>
									
									<td width="30%">
										<table width="100%">
                                            
											<tr>
												<td width="50%" style="text-align:right;">Weight :</td>
												<td width="50%" style="text-align:center;">{{$totalWeight}} kg</td>
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
			<td>
				<table width="100%" style="border-collapse: collapse;">
					<tr>
						<td width="50%" style="padding: 0;">
							<table width="100%" style="border: 1px solid #000;border-collapse: collapse;">
								<tr>
									<td width="100%" style="border: 1px solid;text-align: center;">Payment Address</td>
								</tr>
								<tr>
									<td width="100%">
										<p style="margin:0">Name : {{$order->delivery_address['full_name']}}</p>
										<p style="margin:0">Telephone No.: {{$order->delivery_address['contact_person_number']}}</p>
										<p style="margin:0">Address Line 1 : {{$order->delivery_address['address']}} </p>
										<p style="margin:0">Address Line 2 : {{$order->delivery_address['road']}} {{$order->delivery_address['house']}} {{$order->delivery_address['floor']}}</p>
										<p style="margin:0">Postal Code : {{$order->delivery_address['post_code']}}</p>
										<p style="margin:0">City : {{$order->delivery_address['city']}}</p>
										<p style="margin:0">Region : {{$order->delivery_address['state']}}</p>
									</td>
								</tr>
							</table>
						</td>
						<td width="50%" style="padding: 0;">
							<table width="100%" style="border: 1px solid #000;border-collapse: collapse;">
								<tr>
									<td width="100%" style="border: 1px solid;text-align: center;">Shipping Address</td>
								</tr>
								<tr>
									<td width="100%">
										<p style="margin:0">Name : {{$order->delivery_address['full_name']}}</p>
										<p style="margin:0">Telephone No.: {{$order->delivery_address['contact_person_number']}}</p>
										<p style="margin:0">Address Line 1 : {{$order->delivery_address['address']}} </p>
										<p style="margin:0">Address Line 2 : {{$order->delivery_address['road']}} {{$order->delivery_address['house']}} {{$order->delivery_address['floor']}}</p>
										<p style="margin:0">Postal Code : {{$order->delivery_address['post_code']}}</p>
										<p style="margin:0">City : {{$order->delivery_address['city']}}</p>
										<p style="margin:0">Region : {{$order->delivery_address['state']}}</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" style="border: 1px solid;border-collapse: collapse;">
					<tr>
						<td width="50%" style="padding: 0;border: 1px solid;border-collapse: collapse;text-align:center;"><h3 style="margin: 0;">Total Payable Amount : <span style="text-align:right;">{{$totalAmt}}</span> </h3></td>
						<td width="50%" style="padding: 0;border: 1px solid;border-collapse: collapse;text-align:center;"><h3 style="margin: 0;">INVOICE NO. {{$order->id}}</h3></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				
                <table width="100%" class="invoice_table_product">
					<tr style="background: #a0a3ca;">
                        <th width="2%" style="text-align: center;">Sr.No.</th>
						<th width="18%" style="text-align: center;">Product Name</th>
                        <th width="10%" style="text-align: center;">Code</th>
						<th width="10%" style="text-align: center;">Qty.</th>
						<th width="10%" style="text-align: center;">Unit Price</th>
						<th width="10%" style="text-align: center;">Total</th>
					</tr>
                    @if(!empty($order->details))
                        @foreach($order->details as $key => $detail)
                            @php $productDetail = json_decode($detail->product_details,true); @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td width="50%">
                                    <span class="icon_8">
                                        @if($productDetail['tax'] == 8)
                                            ※ 
                                        @endif
                                    </span>
                                    {{$productDetail['name']}}
                                </td>
                                <td>{{$productDetail['model']}}</td>
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
						<td width="50%" valign="bottom">
							<p style="height: 90px;"><b>Comment:</b> 7pm to 9pm</p>Note ※ This Mark is for 8%
						</td>
						<td width="50%">
							<table width="100%" class="invoice_table_info">
								<tr>
									<td width="55%">Sub Total</td>
									<td width="5%">¥</td>
									<td width="40%" style="text-align:right;">{{$subTotal - $EightPercentTax - $TenPercentTax}}</td>
								</tr>
                                @if($totalTaxPercent['TotalEightPercentTax']!=0)
								<tr>
									<td width="55%">8% Taxable Amount</td>
									<td width="5%">¥</td>
									<td width="40%" style="text-align:right;">{{$totalTaxPercent['TotalEightPercentTax']}}</td>
								</tr>
                                @endif
                                @if($totalTaxPercent['TotalTenPercentTax']!=0)
								<tr>
									<td width="55%">10% Taxable Amount</td>
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
                                @if($order->coupon_discount_amount != 0)
                                    <tr>
                                        <td width="55%">Coupon Discount Amount</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">-{{$order->coupon_discount_amount }}</td>
                                    </tr>
                                @endif
                                @if($order->delivery_charge!=0)
                                    <tr>
                                        <td width="55%">Shipping Charges</td>
                                        <td width="5%">¥</td>
                                        <td width="40%" style="text-align:right;">{{$order->delivery_charge}}</td>
                                    </tr>
                                @endif
                               
								
								<tr>
									<td width="55%"><h3 style="margin: 0;">Total</h3></td>
									<td width="5%"><h3 style="margin: 0;">¥</h3></td>
									<td width="40%" style="text-align:right;"><h3 style="margin: 0;">{{$totalAmt}}</h3></td>
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