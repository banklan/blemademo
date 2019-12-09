@extends('layouts.app')

@section('content')
<body>
    <h4><strong>Dear {{ $user->name }}</strong></h4><br>
    <h5>We are excited to receive your order with order no {{ $order->order_id }}.</h5>
    <h5>Your order is been attended to and will be dispatched as soon as possible.</h5>
    <h5>Should there be any need for clarifications, we shall contact you by email via your email address <strong>{{ $user->email }}</strong> or through your phone number <strong>{{ $user->phone }}</strong></h5>
    <h5>Once our bearers are in your neighbourhood, we will call to inform you.</h5>
    <h5>You can track your order when you login into your account on <a href="#">www.blemagroceries.com</a>, on your dashboard, go to "My Orders", then click on the Order to track.</h5>
    <h5>The item(s) you ordered for are as listed below:</h5>
    <div class="mx-3 pa-1">
        <table class="table table-responsive table-border table-condensed">
            <thead>
                <tr>
                    <th>Items</th>
                    <th>Unit</th>
                    <th>Price(&#8358;)</th>
                </tr>
            <thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->units }}</td>
                        <td>{{ number_format(intval($item->product->price / 100), 2, '.', ',') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Delivery Charges</strong></td>
                    <td><strong>{{ number_format(intval($charges->amount / 100), 2, '.', ',') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong>{{ number_format(intval($order->value / 100), 2, '.', ',') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h5>Thank you for making us a part of your life.</h5>
    <h5>Sincerely,</h5>
    <h5><strong>Blessing</strong></h5>
    <h5><strong>Blema Groceries</strong></h5>
    <h5><a href="#">www.blemagroceries.com</a></h5>
    @endsection
