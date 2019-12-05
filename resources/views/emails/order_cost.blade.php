
    <h4>Dear {{ $user->name }}</h4><br>
    <p>We receive your special order with order no {{ $order->order_no }}.</p>
    <p>Your order is been attended to and will be dispatched as soon as possible.</p>
    <p>The cost of your special order No {{ $order->order_no }} is &#8358;{{ $order->cost }}.</p>
    <p>Should in case you are no longer interested in this order kindly reply this email or call us back immediately.</p>
    <p>Should there be any need for clarifications, we shall contact you by email <strong>{{ $user->email }}</strong> or through your phone number <strong>{{ $user->phone }}</strong></p>
    <p>Once our bearers are in your neighbourhood, we will call to inform you.</p>
    <p>You can track your order when you login into <a href="#">www.blema.com.ng</a>, on your dashboard, click on Orders and then on the order to be tracked.</p>
    <p>Thank you for making us a part of your life.</p><br>
    <p>Sincerely,</p>
    <p><strong>Blessing</strong>
    <p><strong>Blema Deliveries</strong></h4>
    <p><a href="#">www.blema.com.ng</a></p>
