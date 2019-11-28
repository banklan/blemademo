<?php

namespace App\Http\Controllers;

use Image;
use App\User;
use App\Order;
use App\Charges;
use App\Message;
use App\Product;
use App\Service;
use App\Category;
use App\Location;
use App\OrderFee;
use Carbon\Carbon;
use App\OrderSummary;
use App\SpecialOrder;
use App\Mail\WelcomeToBlema;
use Illuminate\Http\Request;
use App\Mail\SpecialOrderCost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\ContactMessage;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.home');
    }

    public function getUsersCount()
    {
        $count = User::all()->count();

        return response()->json($count, 200);
    }

    public function getOrdersCount()
    {
        $today = Carbon::now();

        $orders = OrderSummary::whereDate('created_at', $today)->count();

        $pending = OrderSummary::where('order_status', 1)->whereDate('created_at', $today)->count();

        $treated = OrderSummary::where('order_status', 2)->whereDate('created_at', $today)->count();

        $transit = OrderSummary::where('order_status', 3)->whereDate('created_at', $today)->count();

        $delivered = OrderSummary::where('order_status', 4)->whereDate('created_at', $today)->count();

        $completed = OrderSummary::where('order_status', 7)->whereDate('created_at', $today)->count();

        return response()->json(['orders'=>$orders, 'pending'=>$pending, 'treated'=> $treated, 'transit'=> $transit, 'delivered' => $delivered, 'completed' => $completed, 'message' => 200]);
    }

    public function getProductsCount()
    {
        $products = Product::count();

        return response()->json($products, 200);
    }

    public function getServicesCount()
    {
        $servs = Service::count();

        return response()->json($servs, 200);
    }

    public function getCategoriesCount()
    {
        $cats = Category::count();

        return response()->json($cats, 200);
    }

    public function getOrderSummaries()
    {
        $orders = OrderSummary::latest()->paginate(10);

        return response()->json($orders, 200);
    }

    public function getOrder($order)
    {
        // $summary = OrderSummary::where('order_id', $order)->first();

        $orders = Order::where('order_id', $order)->get();

        return response()->json($orders, 200);
    }

    public function getOrderSummary($id)
    {
        $summary = OrderSummary::findOrFail($id);

        return response()->json($summary, 200);
    }

    public function delOrder(Request $request)
    {
        $order = $request->order;

        if(Order::findOrFail($order)->delete()){
            return response()->json(['message' => 'deleted', 200]);
        }
    }

    public function getUserHistory($id)
    {
        $history = OrderSummary::where('user_id', $id)->latest()->limit(3)->get();

        return response()->json($history, 200);
    }

    public function delOrderSummary($id)
    {
        $summary = OrderSummary::findOrFail($id);
        $orderId = $summary->order_id;
        $orders = Order::where('order_id', $orderId)->get();
        foreach($orders as $order){
            $order->delete();
        }
        $summary->delete();
        return response()->json(['message'=> 'deleted']);
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $status = $request->status;
        if($status == 1){
            $newStatus = 2;
        }
        if($status == 2){
            $newStatus = 3;
        }
        if($status == 3){
            $newStatus = 7;
        }

        $order = OrderSummary::findOrFail($id);
        $order->update([
            $order->order_status = $newStatus
        ]);

        return response()->json($order, 200);
    }


    public function getUser($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function getMessages($id)
    {
        $messages = Message::where('sender_id', $id)->orWhere('receiver_id', $id)->get();

        return response()->json($messages, 200);
    }

    public function postMessages(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|max:120s'
        ]);

        $message = new Message;
        $message->sender_id = 1;
        $message->receiver_id = $request->receiver;
        $message->message = $request->message;
        $message->status = 0;
        $message->save();

        return response()->json($message, 200);
    }

    public function getAllMessages()
    {
        // $messages = Message::selectRaw('sender_id', 'message')->where('sender_id', '!=', 1)->groupBy('sender_id')->get();
        // $messages = Message::where('sender_id', '!=', 1)->groupBy('sender_id');
        // $messages = Message::where('sender_id', '!=', 1)->distinct()->get(['sender_id']);
        $messages = Message::where('sender_id', '!=', 1)->distinct('status')->count('status');
        // $messages = Message::where('sender_id', '!=', 1)->distinct()
        return response()->json($messages, 200);
    }

    public function getAllUsers()
    {
        $users = User::get();

        return response()->json($users, 200);
    }

    public function changeMessagesStatus($id)
    {
        $messages = Message::where('sender_id', $id)->get();

        foreach ($messages as $msg) {
            $msg->update([
                $msg->status = 1
            ]);
        }

        return response()->json(['message' => 'changed']);
    }

    public function getLocations()
    {
        $locations = Location::all();

        return response()->json($locations, 200);
    }

    public function createUser(Request $request)
    {
        // print_r($request->all());
        $this->validate($request, [
            'user.name' => 'required|max:60',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|digits_between:8,12|max:15',
            'user.alt_phone' => 'nullable|digits_between:8,12|max:15',
            'user.address' => 'required|max:120',
            'user.location' => 'required',
            'user.password' => 'required|min:5|max:30'
        ]);

        $user = new User;
        $user->name = $request->user['name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->alt_phone = $request->user['alt_phone'];
        $user->address = $request->user['address'];
        $user->location_id = $request->user['location'];
        $user->status = 1;
        $user->password = Hash::make($request->user['password']);
        $user->save();

        return response()->json($user, 200);
    }

    public function sendWelcomeMail(Request $request)
    {
        $id = $request->user;
        $user = User::findOrFail($id);

        Mail::to($user->email)->send(new WelcomeToBlema($user));

        return response()->json(['message' => 'sent']);
    }

    public function getUsersOrdersCount($id)
    {
        $count = OrderSummary::where('user_id', $id)->count();

        return response()->json($count, 200);
    }

    public function getUsersSpecialOrdersCount($id)
    {
        $count = SpecialOrder::where('user_id', $id)->count();

        return response()->json($count, 200);
    }

    public function getUsersOrders($id)
    {
        $orders = OrderSummary::where('user_id', $id)->latest()->get();

        return response()->json($orders, 200);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // print_r($request->user());
        $this->validate($request, [
            'user.name' => 'required|max:60',
            'user.phone' => 'required|digits_between:8,12|max:15',
            'user.alt_phone' => 'nullable|digits_between:8,12|max:15',
            'user.address' => 'required|max:120',
            'user.location_id' => 'required',
        ]);

        $user->update([
            $user->name = $request->user['name'],
            $user->phone = $request->user['phone'],
            $user->alt_phone = $request->user['alt_phone'],
            $user->address = $request->user['address'],
            $user->location_id = $request->user['location_id'],
        ]);

        return response()->json($user, 200);
    }

    public function changeUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $request->status;

        if($status == 1){
            $newStatus = 0;
        }else{
            $newStatus = 1;
        }

        $user->update([
            $user->status = $newStatus
        ]);

        return response()->json($user->status, 200);
    }

    public function ResetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            $user->password = Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password changed']);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message'=> 'Deleted']);
    }

    public function getAllProducts()
    {
        $products = Product::orderBy('id', 'DESC')->paginate(10);

        return response()->json($products, 200);
    }

    public function createProduct(Request $request)
    {
        // print_r($request->all());
        $this->validate($request, [
            'name' => 'required|max:60',
            'description' => 'required|max:100',
            'price' => 'required|numeric|between:1,100000000000',
            'unit' => 'required',
            'size' => 'nullable|string',
            'cat' => 'required',
            'color' => 'nullable|string',
            'file' => 'required|mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $cat = Category::findOrFail($request->cat);
        $path = $cat->img_path;

        $file = $request->file;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            //save new file in folder
            // $file_loc = public_path('/images/products/'.$path.'/'.$filename);
            // if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
            //     $upload = Image::make($file)->resize(225, 225, function($constraint){
            //         $constraint->aspectRatio();
            //     });
            //     $upload->sharpen(2)->save($file_loc);
            // }

            // save in s3
            $filepath = '/products/' . $filename;
            Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
        }

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->unit = $request->unit;
        $product->price = intval($request->price * 100);
        $product->size = $request->size;
        $product->color = $request->color;
        $product->category_id = $request->cat;
        $product->picture = $filename;
        $product->save();

        return response()->json($product, 200);
    }

    public function getCats()
    {
        $cats = Category::orderBy('name', 'ASC')->get();

        return response()->json($cats, 200);
    }

    public function searchProducts(Request $request)
    {
        $q = $request->q;

        $this->validate($request, [
            'q' => 'required|string'
        ]);

        $items = Product::where('name', 'LIKE', "%".$q."%")->get();

        return response()->json($items, 200);
    }

    public function filterProdsByCats($id)
    {
        $prods = Product::where('category_id', $id)->get();

        return response()->json($prods, 200);
    }

    public function deleteProd($id)
    {
        $prod = Product::findOrFail($id);
        $filename = $prod->picture;
        $path = $prod->category->img_path;

        $filePath = public_path('/images/products/'.$path.'/'.$filename);
        if(file_exists($filePath)){
            unlink($filePath);
        }

        $prod->delete();

        return response()->json(['Message' => 'Deleted'], 200);
    }

    public function getProduct($id)
    {
        $prod = Product::findOrFail($id);

        return response()->json($prod, 200);
    }

    public function updateImage(Request $request, $id)
    {
        $prod = Product::findOrFail($id);

        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,bmp,png,gif,pdf'
        ]);

        $oldImg = $prod->picture;
        $path = $prod->category->img_path;

        // unlink old image
        $oldImgPath = public_path('/images/products/'.$path.'/'.$oldImg);
        if(file_exists($oldImgPath)){
            unlink($oldImgPath);
        }

        //store new image
        $img = $request->image;
        if($img){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $img->getClientOriginalExtension();
            $newImg = substr(str_shuffle($pool), 0, 8).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/products/'.$path.'/'.$newImg);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($img)->resize(450, 500, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(2)->save($file_loc);
            }
        }

        //update db with new image
        $prod->update([
            $prod->picture = $newImg
        ]);

        return response()->json($prod, 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $prod = Product::findOrFail($id);

        $this->validate($request, [
            'product.name' => 'required|max:60',
            'product.description' => 'required|max:100',
            'product.price' => 'required|numeric|between:1,100000000000',
            'product.unit' => 'required',
            'product.size' => 'nullable|string',
            // 'product.category_id' => 'required',
            'product.color' => 'nullable|string',
        ]);

        $prod->update([
            $prod->name = $request->product['name'],
            $prod->description = $request->product['description'],
            $prod->price = intval($request->product['price'] * 100),
            $prod->unit = $request->product['unit'],
            // $prod->category_id = $request->product['category_id'],
            $prod->colour = $request->product['color'],
            $prod->size = $request->product['size'],
        ]);

        return response()->json($prod, 200);
    }

    public function getProductServices($id)
    {
        $prod = Product::findOrFail($id);

        $services = Service::where('product_id', $id)->get();

        return response()->json($services, 200);
    }

    public function getService($id)
    {
        $service = Service::findOrFail($id);

        return response()->json($service->load('product'), 200);
    }

    public function createService(Request $request, $id)
    {
        $this->validate($request, [
            'service.name' => 'required|max:30',
            'service.description' => 'required|max:80',
            'service.price' => 'required|numeric|between:1,100000000000',
        ]);

        $service = new Service;
        $service->name = $request->service['name'];
        $service->description = $request->service['description'];
        $service->price = intval($request->service['price'] * 100);
        $service->product_id = $id;
        $service->save();

        return response()->json($service, 200);
    }

    public function delService($id)
    {
        $serv = Service::findOrFail($id);

        $serv->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function updateService(Request $request, $id)
    {
        $serv = Service::findOrFail($id);

        $this->validate($request, [
            'service.name' => 'required|max:30',
            'service.description' => 'required|max:80',
            'service.price' => 'required|numeric|between:1,100000000000',
        ]);

        $serv->update([
            $serv->name = $request->service['name'],
            $serv->description = $request->service['description'],
            $serv->price = intval($request->service['price'] * 100),
        ]);

        return response()->json($serv->load('product'), 200);
    }

    public function createCateg(Request $request)
    {
        $this->validate($request, [
            'cat' => 'required|max:30|unique:categories,name'
        ]);

        $cat = new Category;
        $cat->name = $request->cat;
        $cat->img_path = str_slug($request->cat);
        $cat->save();

        //create directory--removed in production as all products are in same dir
        // if(!file_exists(public_path('/images/products/'.$cat->img_path))){
        //     mkdir(public_path('/images/products/'.$cat->img_path, 0777, true));
        // }

        return response()->json($cat, 200);
    }

    public function delCateg($id)
    {
        $categ = Category::findOrFail($id);

        $categ->delete();
        return response()->json($categ, 200);
    }

    public function updateCateg(Request $req, $id)
    {
        $categ = Category::findOrFail($id);

        $this->validate($req, [
            'cat' => 'required|max:30'
        ]);

        $categ->update([
            $categ->name = $req->cat,
            // $categ->img_path = str_slug($req->cat)
        ]);

        return response()->json($categ, 200);
    }

    public function getAllLocs()
    {
        $locs = Location::orderBy('name', 'ASC')->get();

        return response()->json($locs, 200);
    }

    public function createLocation(Request $req)
    {
        $this->validate($req, [
            'loc' => 'required|max:30|unique:locations,name'
        ]);

        $loc = new Location;
        $loc->name = $req->loc;
        $loc->save();

        return response()->json($loc, 200);
    }

    public function delLoc($id)
    {
        $loc = Location::findOrFail($id);

        $loc->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function updateLoc(Request $req, $id)
    {
        $loc = Location::findOrFail($id);

        $this->validate($req, [
            'loc' => 'required|max:30'
        ]);

        $loc->update([
            $loc->name = $req->loc,
        ]);

        return response()->json($loc, 200);
    }

    public function searchCharges(Request $req)
    {
        $q = $req->q;

        $this->validate($req, [
            'q' => 'required|string'
        ]);

        $items = Charges::where('order_id', 'LIKE', "%".$q."%")->orWhere('amount', 'LIKE', "%".$q."%")->get();

        return response()->json($items, 200);
    }

    public function getTrxCharges()
    {
        $charges = Charges::latest()->paginate(10);

        return response()->json($charges, 200);
    }

    public function getOrderDates()
    {
        // $dates = OrderSummary::selectRaw('Date(created_at) as date')->groupBy('date')->get();
        $dates = OrderSummary::select([
            DB::raw('count(id) as `count`'),
            DB::raw('DATE(created_at) as day')
        ])->groupBy('day')->get();

        return response()->json($dates, 200);
    }

    public function filterChargesByDates(Request $req)
    {
        $start = $req->dates['fromDate'];
        $end = $req->dates['toDate'];
        if($start == $end){
            $charges = Charges::where(DB::raw('DATE(created_at)'), $start)->orWhere(DB::raw('DATE(created_at)'), $end)->get();
        }else{
            $charges = Charges::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        }
        return response()->json($charges, 200);
    }

    public function thisWeek()
    {
        $charges = Charges::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        return response()->json($charges, 200);
    }

    public function getDefaultCharges()
    {
        $chgs = OrderFee::orderBy('id', 'ASC')->get();

        return response()->json($chgs, 200);
    }

    public function updateFee(Request $req, $id)
    {
        $this->validate($req, [
            'fee' => 'required|numeric|between:1,100000000000'
        ]);

        $fee = OrderFee::findOrFail($id);
        $fee->update([
            $fee->fee = intval($req->fee * 100)
        ]);

        return response()->json($fee, 200);
    }

    public function getSpecialOrders()
    {
        $orders = SpecialOrder::latest()->paginate(10);

        return response()->json($orders, 200);
    }

    public function filterSpecialOrdersByDates(Request $req)
    {
        $start = $req->dates['fromDate'];
        $end = $req->dates['toDate'];

        if($start == $end){
            $orders = SpecialOrder::where(DB::raw('DATE(created_at)'), $start)->orWhere(DB::raw('DATE(created_at)'), $end)->get();
        }else{
            $orders = SpecialOrder::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        }

        return response()->json($orders, 200);
    }

    public function delSpecialOrder($id)
    {
        if(SpecialOrder::findOrFail($id)->delete()){
            return response()->json(['message' => 'deleted', 200]);
        }
    }

    public function getSpecialOrder($id)
    {
        $order = SpecialOrder::findOrFail($id);

        return response()->json($order, 200);
    }

    public function setSpecialOrderCost(Request $req, $id)
    {
        $order = SpecialOrder::findOrFail($id);

        $this->validate($req, [
            'cost' => 'required|numeric|between:1,100000000000'
        ]);

        $order->update([
            $order->cost = floatval($req->cost) * 100
        ]);

        return response()->json($order, 200);
    }

    public function sendCostEmail(Request $req)
    {
        $user = User::findOrFail($req->user);
        $cost = floatval($req->cost) / 100;
        $order = SpecialOrder::findOrFail($req->order);

        Mail::to($user->email)->send(new SpecialOrderCost($user, $order));
    }

    public function processSpecialOrder(Request $req, $id)
    {
        $order = SpecialOrder::findOrFail($id);

        $status = $req->status;
        if($status == 1){
            $newStatus = 2;
        }else if($status == 2){
            $newStatus = 3;
        }elseif($status == 3){
            $newStatus = 7;
        }else{
            $newStatus = 7;
        }

        $order->update([
            $order->order_status = $newStatus
        ]);

        return response()->json($order, 200);
    }

    public function filterOrdersByDates(Request $req)
    {
        $start = $req->dates['fromDate'];
        $end = $req->dates['toDate'];

        if($start == $end){
            $orders = OrderSummary::where(DB::raw('DATE(created_at)'), $start)->orWhere(DB::raw('DATE(created_at)'), $end)->get();
        }else{
            $orders = OrderSummary::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        }

        return response()->json($orders, 200);
    }

    public function getProfile()
    {
        $admin = Auth::guard('admin')->user();

        return response()->json($admin, 200);
    }

    public function confirmCurrentPswd(Request $req)
    {
        $this->validate($req, [
            'pswd' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        $current = $admin->password;
        $check = Hash::check($req->pswd, $current);

        return response()->json($check, 200);
    }

    public function updateAdminPswd(Request $req)
    {
        $this->validate($req, [
            'current' => 'required',
            'password' => 'required|min:5|max:30|confirmed',
            'password_confirmation' => 'required'
        ]);

        $admin = Auth::guard('admin')->user();

        $admin->update([
            $admin->password = Hash::make($req->password)
        ]);

        return response()->json($admin, 200);
    }

    public function getMailsEnquiries()
    {
        $mails = ContactMessage::latest()->paginate(10);

        return response()->json($mails, 200);
    }

    public function getMail($id)
    {
        $mail = ContactMessage::findOrFail($id);

        return response()->json($mail, 200);
    }

    public function readEnquiry($id)
    {
        $mail = ContactMessage::findOrFail($id);

        $mail->update([
            $mail->status = 1
        ]);

        return response()->json($mail, 200);
    }

    public function changeStatus($id)
    {
        $mail = ContactMessage::findOrFail($id);

        $mail->update([
            $mail->status = 1
        ]);

        return response()->json($mail, 200);
    }

    public function delEnquiry($id)
    {
        $mail = ContactMessage::findOrFail($id);

        $mail->delete();

        return response()->json(['message' => 'deleted'], 200);
    }

    public function getUnreadCount()
    {
        $count = ContactMessage::where('status', 0)->count();

        return response()->json($count, 200);
    }

    public function getUnreadChats()
    {
        $msgs = Message::where('status', 0)->count();

        return response()->json($msgs, 200);
    }

    public function getTodaysOrdersCounts()
    {
        $count = OrderSummary::whereDate('created_at', Carbon::today())->count();

        return response()->json($count, 200);
    }

    public function getTodaysOrders()
    {
        $today = Carbon::today();

        $orders = OrderSummary::whereDate('created_at', $today)->get();

        return response()->json($orders, 200);
    }

    public function getSpecialOrdersCount()
    {
        $today = Carbon::today();
        $count = SpecialOrder::whereDate('created_at', $today)->count();

        return response()->json($count, 200);
    }

    public function getSalesPeriod(){
        $today = Carbon::today()->format('d-m-Y');
        $yesterday = Carbon::yesterday()->format('d-m-Y');
        $day_before = Carbon::now()->subDays(2)->format('d-m-Y');
        $three_days = Carbon::now()->subDays(3)->format('d-m-Y');
        $four_days = Carbon::now()->subDays(4)->format('d-m-Y');

        return response()->json(['0' => $today, '1' => $yesterday, '2' => $day_before, '3' => $three_days, '4' => $four_days]);
    }
    public function getSales()
    {
        $todays = OrderSummary::whereDate('created_at', Carbon::today())->count();
        $yesterdays = OrderSummary::whereDate('created_at', Carbon::yesterday())->count();
        $day_befores = OrderSummary::whereDate('created_at', Carbon::now()->subDays(2))->count();
        $threedays = OrderSummary::whereDate('created_at', Carbon::now()->subDays(3))->count();
        $fourdays = OrderSummary::whereDate('created_at', Carbon::now()->subDays(4))->count();

        return response()->json(['0' => $todays, '1' => $yesterdays, '2' => $day_befores, '3' => $threedays, '4' => $fourdays]);
    }

    public function delConversations(Request $req)
    {
        $user = intval($req->user);
        $convs = Message::where('sender_id', $user)->orWhere('receiver_id', $user)->get();

        foreach($convs as $conv){
            $conv->delete();
        }

        return response()->json(['message' => 'deleted']);
    }
}
