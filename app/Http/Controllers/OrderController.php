<?php

namespace App\Http\Controllers;

use App\Order;
use App\Charges;
use App\Payment;
use Carbon\Carbon;
use App\OrderSummary;
use App\SpecialOrder;
use App\Mail\OrderReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendSpecialOrder(Request $request)
    {
        // print_r($request->order);
        $this->validate($request, [
            'order.name' => 'required|max:50',
            'order.details' => 'required|max:300',
            'order.units' => 'required|max:20',
            'order.request' => 'max:140'
        ]);

        $year= Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('j');
        $rand = substr(sha1(mt_rand()), 17, 6);
        $orderId = '99'.$day.$month.$year.$rand;

        $order = new SpecialOrder;
        $order->order_no = $orderId;
        $order->user_id = 10;
        $order->request = $request->order['name'];
        $order->details = $request->order['details'];
        $order->units = $request->order['units'];
        $order->del_date = $request->order['delDate'];
        $order->del_time = $request->order['delTime'];
        $order->special_req = $request->order['special_req'];
        $order->save();

        //send an email to user and blema
        $user = Auth::user();
        // Mail::to($user->email)->send(new OrderReceived($user, $order));

        return response()->json($order, 200);
    }

    public function payOnDelivery(Request $request)
    {
        $orders = $request->orders;
        $services = $request->services;
        $user = Auth::id();

        $year= Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('j');
        $rand = substr(sha1(mt_rand()), 17, 6);
        $orderId = $year.$month.$day.$rand;

        for($i=0; $i < count($orders); $i++){
           $order = new Order;
           $order->order_id = $orderId;
           $order->user_id = $user;
           $order->product_id = $orders[$i]['id'];
           $order->units = $orders[$i]['units'];

           $order->save();
        }

        for($i=0; $i < count($services); $i++){
            $order = new Order;
            $order->order_id = $orderId;
            $order->user_id = $user;
            $order->service_id = $services[$i]['id'];
            $order->units = $services[$i]['units'];

            $order->save();
         }

        // //create order summary
        $this->validate($request, [
            'message' => 'max:100'
        ]);

        $summary = new OrderSummary;
        $summary->order_id = $orderId;
        $summary->user_id =  $user;
        $summary->item_count = count($orders);
        $summary->services_count = count($services);
        $summary->value = $request->total;
        $summary->message = $request->message;
        $summary->order_status = '1';
        $summary->pymt_mode = 'POD';
        $summary->pymt_status = 0;
        $summary->save();

        //store charges
        $charges = new Charges;
        $charges->amount = $request->charges;
        $charges->user_id = Auth::id();
        $charges->status = 0;
        $charges->order_id = $orderId;

        $charges->save();

        return response()->json($summary, 200);
    }

    public function sendOrderReceivedEmails(Request $request, $id)
    {
        $order = OrderSummary::findOrFail($id);
        $orderId = $request->order;
        $charges = Charges::where('order_id', $orderId)->first();
        $items = Order::where('order_id', $orderId)->get();
        $user = Auth::user();
        Mail::to($user->email)->send(new OrderReceived($user, $order, $items, $charges));
        Mail::to('blemadeliveries@gmail.com')->send(new OrderReceived($user, $order, $items, $charges));
    }

    public function getUserPendingOrders()
    {
        $user = Auth::id();

        $pending = OrderSummary::where('user_id', $user)->where('order_status', '!=', 7)->latest()->get();

        return response()->json($pending, 200);
    }

    public function getUserOrders()
    {
        $user = Auth::id();

        $orders = OrderSummary::where('user_id', $user)->latest()->get();

        return response()->json($orders, 200);
    }

    public function getUserSpecialOrders()
    {
        $user = Auth::id();

        $orders = SpecialOrder::where('user_id', $user)->get();

        return response()->json($orders, 200);
    }

    public function getUserOrder($id)
    {
        $order = OrderSummary::where('order_id', $id)->first();

        if(Auth::id() == $order->user_id){
            return response()->json($order, 200);
        }
    }

    public function getUserOrdersByOrderId($order)
    {
        $user = Auth::id();

        $orders = Order::where('user_id', $user)->where('order_id', $order)->latest()->get();

        return response()->json($orders->load(['product', 'service']), 200);
    }

    public function getDeliveryContact()
    {
        if(Auth::check()){
            $user = Auth::user();
            return response()->json(['address' => $user->address, 'phone' => $user->phone, 200]);
        }
    }

    public function getLastFiveOrders()
    {
        if(Auth::check()){
            $user = Auth::id();

            $orders = OrderSummary::where('user_id', $user)->latest()->limit(4)->get();

            return response()->json($orders, 200);
        }
    }

    public function genOrderIdForPymnt()
    {
        //create unique order id that will also be sent to paystack
        $year= Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('j');
        $rand = substr(sha1(mt_rand()), 17, 6);
        $orderId = $year.$month.$day.$rand;

        return response()->json($orderId, 200);
    }

    public function saveOrderPayment(Request $request)
    {
        $orders = $request->items;
        $services = $request->services;
        $user = Auth::id();

        //save each order
        for($i=0; $i < count($orders); $i++){
           $order = new Order;
           $order->order_id = $request->orderId;
           $order->user_id = $user;
           $order->product_id = $orders[$i]['id'];
           $order->units = $orders[$i]['units'];

           $order->save();
        }

        //save each service
        for($i=0; $i < count($services); $i++){
            $order = new Order;
            $order->order_id = $request->orderId;
            $order->user_id = $user;
            $order->service_id = $services[$i]['id'];
            $order->units = $services[$i]['units'];

            $order->save();
         }

        // //create order summary
        $this->validate($request, [
            'message' => 'max:100'
        ]);

        $summary = new OrderSummary;
        $summary->order_id = $request->orderId;
        $summary->user_id =  $user;
        $summary->item_count = count($orders);
        $summary->services_count = count($services);
        $summary->value = $request->total;
        $summary->message = $request->message;
        $summary->order_status = '1';
        $summary->pymt_mode = 'POL';
        $summary->pymt_status = 1;
        $summary->save();

        //store charges
        $charges = new Charges;
        $charges->amount = $request->charges;
        $charges->user_id = $user;
        $charges->status = 1;
        $charges->order_id = $request->orderId;
        $charges->save();

        //store payment info
        $payment = new Payment;
        $payment->user_id = $user;
        $payment->order_id = $request->orderId;
        $payment->amount = $request->grandTotal;
        $payment->trx_ref = $request->trxResponse['reference'];
        $payment->trx_status = $request->trxResponse['status'];
        $payment->trx_msg = $request->trxResponse['message'];
        $payment->trx_trans = $request->trxResponse['transaction'];
        $payment->save();

        return response()->json(['summary' => $summary, 'payment' => $payment, 'charges' => $charges], 200);
    }
}
