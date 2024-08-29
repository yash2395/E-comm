<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Country;
use App\Models\Product;
use App\Models\Shipping;
// use Glodemans\Shoppingcart\Facades\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request){

        $product = Product::find( $request->id );
        // p($product);

        if ( $product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        if(Cart::count() > 0){
            // Product is already in cart then retutn message that product is already in cart
            // If porduct not found in cart, then add product in cart

            $cartContent = Cart::content();

            foreach( $cartContent as $item){
                if($item->id == $product->id){
                    $status = false;
                    $message = $product->title . ' Already exists in Cart';
                }else{
                    Cart::add($product->id, $product->title,1,$product->price);
                    $status = true;
                    $message = $product->title . ' Added in Cart';
                }
            }

        }else{
            Cart::add($product->id, $product->title,1,$product->price);
            $status = true;
            $message = $product->title." Added in cart";
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
        
    }
    public function cart(){

        $cartContent = Cart::content();
        // dd($cartContent);
        // p($cartContent);
        return view('front.cart',compact('cartContent'));
    }

    public function updateCart(Request $request){
        // echo 
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
        //check quantity avalable in stock
        if($product->track_qty == 'Yes'){
            if($qty <= $product->qty){
                Cart::update($rowId,$qty);
                $message = 'Cart Updated Successfully';
                $status = true;
                session()->flash('success',$message);

            }else{
                $message = 'Requested qty('.$qty.') not available in stock';
                $status = false;
                session()->flash('error',$message);

            }
        }else{
            Cart::update($rowId,$qty);
            $message = 'Cart Updated Successfully';
            $status = true;
            session()->flash('success',$message);

        }
        Cart::update($rowId,$qty);

        return response()->json([
            'status'=> true,
            'message'=> $message
        ]);
    }

    public function deleteItem (Request $request) {
        $itemInfo = Cart::get ($request->rowId);
        if ($itemInfo== null) {
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);
            return response()->json ([
                'status' => false,
                'message' => $errorMessage
            ]);
        }
        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully.';
        session ()->flash('success', $message);
        return response()->json([
        'status' => true,
        'message' => $message
        ]);
    }
    public function checkout(){

        $discount = 0;
        // if cart is empty redirect to cart page
        if(Cart::count() == 0) {
            return redirect()->route('front.cart');
        }
        // if user is not loggedin then redirect to login page
        if(Auth::check() == false){
            if(!session()->has('url.intent')) {
                session(['url.intent' => url('checkout') ]);
                
            }
            return redirect()->route('account.login');
        }
        $userId = Auth::user()->id;
        $customerAddress = CustomerAddress::where('user_id', $userId)->first();
        session()->forget('url.intended');
        $countries = Country::orderBy('name','ASC')->get();
        $subTotal = Cart::subtotal('2','.','');

        // Apply discount coupoun here
        if(session('code')){
            $code = session('code');
            if( $code->type == 'percent' ){
                $discount = ($code->discount_amount/100) * $subTotal;
            }else{
                $discount = $code->discount_amount;
            }
        }

        // Calculate Shipping here
        if($customerAddress){

        $userCountry = $customerAddress['country_id'];
        $shippingInfo = Shipping::where('country_id', $userCountry)->first();
        $totalShippingCharges = 0;
        $totalQty = 0;
        if($shippingInfo == null){
        $shippingInfo = Shipping::where('country_id', 'rest_of_worl')->first();
        }
        
        foreach(Cart::content() as $item){
            $totalQty += $item->qty;
        }

        $totalShippingCharges = $totalQty * $shippingInfo->amount;
        // p(Cart::subtotal());
        $grandTotal = intval(Cart::subtotal('2','.','')) + $totalShippingCharges;
        }
        else{
            $totalShippingCharges = 0;
            $grandTotal = intval(Cart::subtotal('2','.','')) + $totalShippingCharges;
        }


        return view('front.checkout',compact('countries','customerAddress','totalShippingCharges','grandTotal','discount'));
    }
    public function processCheckout(Request $request){
        // p($request->all());
        // p('hi');
        $validator = $request->validate([
            'first_name' => 'required | min:5',
            'last_name' => 'required',
            'email' => 'required | email',
            'country' => 'required',
            'address' => 'required | min: 30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);
        // save user address


        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                
                'first_name' => $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'country_id'=> $request->country,
                'mobile' => $request->mobile,
                'address'=> $request->address,
                'apartment' => $request->appartment,
                'city'=> $request->city,
                'state'=> $request->state,
                'zip'=> $request->zip,

            ]
        );

        if($request->payment_method == 'cod'){

            $discountCodeId = null;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subTotal(2,'.','');
            // $grandTotal = $subTotal+$shipping-$discount;

             //Appply Discount here
             if(session('code')){
                 $code = session('code');
                //  p($code);
                if( $code->type == 'percent' ){
                    $discount = ($code->discount_amount/100) * $subTotal;
                }else{
                    $discount = $code->discount_amount;
                }
                
            $discountCodeId = $code->id;
            $promoCode = $code->code;
            // p($discountCodeId);
            }

            // Calculating Shipping
            $shippingInfo = Shipping::where('country_id',$request->country)->first();

            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }
            if($shippingInfo){
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

                
            }else{
                $shippingInfo = Shipping::where('country_id','rest_of_worl')->first();    

                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

                
            }

           

            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            // p($order->discount);
            $order->coupon_code = $promoCode;
            $order-> coupon_code_id = $discountCodeId;
            $order-> payment_status = 'not paid';
            $order-> status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->country_id = $request->country;
            $order->zip = $request->zip;
            // p($order->coupon_code_id);
            $order->save();


            // Store Order items in orders items table
            foreach(Cart::content() as $item){
                $orderItem = new OrderItem();
                $orderItem ->product_id= $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem ->price =  $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                // Update Product Stock
                $productData = Product::find($item->id);
                if($productData->track_qty == 'Yes'){
                    
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty-$item->qty;
                    $productData->qty = $currentQty-$item->qty;
                    $productData->save();
                    // p($productData->qty);
                }
            }

            // Send Order Email
            orderEmail($order->id,'customer');

            Cart::destroy();
            session()->forget('code');
            return redirect()->route('front.thanks',$order->id)->with('success','Your Order has been placed successfully');
        }
    }

    public function thankyou(Request $request,$orderid){
        $user = Auth::user();
        $id = $user->id;
        return view('admin.layouts.thanks',compact('id','orderid'))->with('success','Your Order has been placed successfully');
    }

    public function getOrderSummery(Request $request){
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';
        //Appply Discount here
        if(session('code')){
            $code = session('code');
            if( $code->type == 'percent' ){
                $discount = ($code->discount_amount/100) * $subTotal;
            }else{
                $discount = $code->discount_amount;
            }
            
            $discountString = 
            '<div class="mt-4" id="discount-response">
            <strong>'.session('code')->code.'</strong>
            <button class="btn btn-sm btn-danger" id="remove-discount" type="button"><i class="fa fa-times"></i></button>
            </div>';
        }


        if($request->country_id > 0){

            $shippingInfo = Shipping::where('country_id',$request->country_id)->first();
            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if($shippingInfo){
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal - $discount)+$shippingCharge;

                return response()->json([
                    'status' =>true,
                    'grandTotal' => $grandTotal,
                    'discount' => $discount,
                    'shippingCharge' => $shippingCharge,
                    'discountString'=> $discountString,
                ]);
            }else{
                $shippingInfo = Shipping::where('country_id','rest_of_worl')->first();    

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal - $discount)+$shippingCharge;

                return response()->json([
                    'status' =>true,
                    'grandTotal' => $grandTotal,
                    'discount' => $discount,
                    'discountString'=> $discountString,
                    
                    'shippingCharge' => $shippingCharge,
                ]);
            }
        }else{
            return response()->json([
                'status'=> true,
                'grandTotal' => number_format(($subTotal - $discount),2,'.',''),
                'discount' => $discount,
                'discountString'=> $discountString,

                'shippingCharge' => number_format(0,2),
            ]);
        }
    }
    public function applyDiscount(Request $request){
        // dd($request->code);
        $code = DiscountCoupon::where('code',$request->code)->first();
        if($code == null){
            return response()->json([
                'status'=>false,
                'message'=> 'Invalid Discount Coupon',
            ]);
        }
        //Check if ocupon start date is valid or not 
        // $now = Carbon::now();


        //Max Uses Check
        if($code->max_uses > 0){

            $couponUsed = Order::where('coupon_code_id', $code->id)->count();
            
            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status'=>false,
                    'message'=> 'Invalid Discount Coupon',
                ]);
            }
        }

        // Max Uses for user check
        if($code->max_uses_user > 0){

            $couponUsedByUser = Order::where(['coupon_code_id'=> $code->id, 'user_id' => Auth::user()->id])->count();
            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status'=>false,
                    'message'=> 'You Already Used this Coupon',
                ]);
            }
        }

        $subTotal = Cart::subtotal(2,'.','');
        
        // check minmun amount condition
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status'=>false,
                    'message'=> 'Your Min amount must be $'.$code->min_amount.'.',
                ]);
            }
        }

        session()->put('code', $code);
        return $this->getOrderSummery($request);
    }
    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->getOrderSummery($request);
    }

}