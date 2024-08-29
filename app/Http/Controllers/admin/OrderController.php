<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request){
        // $orders = Order::latest();
        // $orders = $orders->leftJoin("users","users.id","orders.user_id");
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email'); // Explicitly specify the table for created_at
        $orders = $orders->leftJoin("users", "users.id", "=", "orders.user_id");
        if($request->get('keyword')){
            $orders = $orders->where('users.name','LIKE','%'.$request->keyword.'%');
            $orders = $orders->orwhere('users.email','LIKE','%'.$request->keyword.'%'); 
            $orders = $orders->orwhere('users.id','LIKE','%'.$request->keyword.'%');
        }

        $orders = $orders->paginate(10);
        return view("admin.orders.list",compact('orders'));
    }


    public function detail($orderId){
        // $order = Order::select('orders.*','countries.name As countyName')
        //         ->findOrFail($orderId)
        //         ->leftJoin('countries','countries.id','orders.country_id')
        //         ->first();
        $order = Order::leftJoin('countries', 'countries.id', '=', 'orders.country_id')
                ->select('orders.*', 'countries.name AS countryName')
                ->where('orders.id', $orderId)
                ->first();

        $orderItems = OrderItem::where('order_id',$orderId)->get();
        // p($order->toArray());
        return view('admin.orders.detail',compact('order','orderItems'));
    }

    public function changeOrderStatus(Request $request, $orderId){
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';

        session()->flash('success',$message);

        return response()->json([
            'status'=> 'true',
            'message'=> $message,
        ]);
    }

    public function sendInvoiceEmail(Request $request,$orderid){
        orderEmail($orderid, $request->usertype);

        $message = 'Order Email sent Successfully';
        session()->flash('success',$message);

        return response()->json([
            'status'=> 'true',
            'message'=> $message,
        ]);
    }
}
