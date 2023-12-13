<html dir="ltr" lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Shipping</title>
    <style type="text/css">.table-bordered {border: 1px solid #ddd;}.table {width: 100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;}.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {border: 1px solid #ddd;}.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 8px;line-height: 1.42857143;color: #545454;}address {margin-bottom: 20px;font-style: normal;line-height: 1.42857143;}.text-right{text-align: right;}</style>
  </head>
  <body>
    <div style="width: 1000px;margin: 0 auto;font-family: Montserrat;">
      <div style="page-break-after: always;">
        <h1>Dispatch Note #{{ $order['id']}}</h1>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td colspan="2">Order Details</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <address>
                  <strong>Sartaj Co Ltd.</strong><br>
                  osaka-fu, ikeda-shi, koda 2-10-23
                </address>
                <b>Tel</b> 072-751-1975<br>
                <b>E-Mail</b> order@sartajfoods.jp<br>
                <b>Web Site:</b> <a href="https://sartajfoods.jp">https://sartajfoods.jp</a>
              </td>
              <td style="width: 50%;"><b>{{translate('Date Added')}}</b> {{date('d/m/Y',strtotime($order['created_at']))}}<br>
                <b>Order ID:</b> {{ $order['id'] }}<br>
                <b>Shipping Method</b> All Item in Dry Shipping<br>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td style="width: 50%;"><b>Shipping Address</b></td>
              <td style="width: 50%;"><b>Contact</b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $order['delivery_address']['address'] }}</td>
              <td>{{ !empty($order->customer['email']) ? $order->customer['email'] : '' }}<br>
               {{ !empty($order->customer['phone']) ? $order->customer['phone'] : '' }}
              </td>
            </tr>
          </tbody>
        </table>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td><b>Product</b></td>
              <td><b>Product Weight</b></td>
              <td><b>Model</b></td>
              <td class="text-right"><b>Quantity</b></td>
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $detail)
                @if($detail->product_details !=null)
                    @php($product = json_decode($detail->product_details, true))
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['weight'] }} {{ ( $product['weight']) ?  $product['weight_class'] : '' }}</td>
                        <td>{{ $product['model'] }}</td>
                        <td class="text-right">{{ $detail['quantity'] }}</td>
                    </tr>
                @endif    
            @endforeach
          </tbody>
        </table>
       <table class="table table-bordered">
          <thead>
            <tr>
              <td><b>Customer Comment</b></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ !empty($order['order_note']) ? $order['order_note'] : '' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>