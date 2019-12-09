
    <h4><strong>Dear {{ $user->name }}</strong></h4><br>
    <p>We are excited to receive your order with order no {{ $order->order_id }}.</p>
    <p>Your order is been attended to and will be dispatched as soon as possible.</p>
    <p>Should there be any need for clarifications, we shall contact you by email via your email address <strong>{{ $user->email }}</strong> or through your phone number <strong>{{ $user->phone }}</strong></p>
    <p>Once our bearers are in your neighbourhood, we will call to inform you.</p>
    <p>You can track your order when you login into your account on <a href="#">www.blemagroceries.com</a>, on your dashboard, go to "My Orders", then click on the Order to track.</p>
    <p>The item(s) you ordered for are as listed below:</p>
    <table>
        <thead>
            <tr>
                <th>Items</th>
                <th>Unit</th>
                <th>Price(&#8358;)</th>
            </tr>
        <thead>
        <tbody>
            <tr v-for="item in items">
                <td>{{ item.product.name }}</td>
                <td>{{ item.units }}</td>
                <td>{{ item.units }} * money_format({{ item.product.price / 100 }}, 2)</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Delivery Charges</strong></td>
                <td><strong>(&#8358;)money_format({{ charges / 100 }}, 2)</strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>(&#8358;)money_format({{ order / 100 }}, 2)</strong></td>
            </tr>
        </tbody>
    </table>
    <p>Thank you for making us a part of your life.</p>
    <p>Sincerely,</p>
    <p><strong>Blessing</strong></p>
    <p><strong>Blema Groceries</strong></p>
    <p><a href="#">www.blemagroceries.com</a></p>
