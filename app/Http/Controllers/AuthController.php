<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Models\Wishlist;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Mail\resetPasswordEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request){
        return view("front.account.login");

    }
    public function register(){
        return view("front.account.register");
    }
    public function processRegister(Request $request){
        $validator = $this->validate($request,[
            'name' => 'required|min:3',
            'email'=> 'required|email|unique:users',
            'password' => 'required|min:5',
            'Confirm-password' => 'required|same:password'
        ]);
        if($validator){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
        }
        return redirect()->route('account.login')->with('success','Account Created Successfully');
    }
    public function authenticate(Request $request){

        $validator = $this->validate($request,[
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if($validator){
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password], $request->get('remember'))){

                if(session()->has('url.intent')){
                    return redirect(session()->get('url.intent'));
                }
 
                return redirect()->route('account.profile')->with('success','Account Logged in Successfully');
            }else{
                return redirect()->back()->with('error','Either Email/Password is incorrect')->withInput(request()->only('email'));
            }
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success','You have Successsfully Logged Out');
    }

    public function profile(){
        $userId = Auth::user()->id;
        $countries = Country::orderBy('name','asc')->get();
        $user = User::where('id',$userId)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();
        return view('front.account.profile',compact('user','countries','address'));
    }
    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required | email | unique:users,email,'.$userId.'id',
            'phone' => 'required',
        ]);

        if($validator){
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            
            return redirect()->back()->with('success','Profile Updated Successfully');
        }
    }
    public function updateAddress(Request $request){
        $userId = Auth::user()->id;
        $validator = $request->validate([
            'first_name' => 'required | min:5',
            'last_name' => 'required',
            'email' => 'required | email',
            'country_id' => 'required',
            'address' => 'required |min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);
        // p('hi');
        // p($validator);

        if($validator){
        // p($request->all());
        CustomerAddress::updateOrCreate(
            ['user_id' => $userId],
            [
                'user_id' => $userId,
                'first_name' => $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'country_id'=> $request->country_id,
                'mobile' => $request->mobile,
                'address'=> $request->address,
                'apartment' => $request->appartment,
                'city'=> $request->city,
                'state'=> $request->state,
                'zip'=> $request->zip,
            ]
        );  
            return redirect()->back()->with('success','Address Updated Successfully');
        }
    }

    public function orders(){
        $user = Auth::user();
        $orders = Order::where('user_id',$user->id)
                ->orderBy('created_at','desc')
                ->get();
        return view('front.account.order',compact('orders'));
    }

    public function orderDetail($id){
        $user = Auth::user();
        $orders = Order::where('user_id',$user->id)
                ->where('id',$id)
                ->first();
        $orderItems = OrderItem::where('order_id',$id)->get();
        $orderItemsCount = OrderItem::where('order_id',$id)->count();

        $data = compact('orders','orderItems','orderItemsCount');
        return view('front.account.common.order-detail',$data);
    }
    public function wishlist(){
        $wishlist = Wishlist::where('user_id',Auth::user()->id)->get();
        return view('front.account.wishlist',compact('wishlist'));
    }

    public function removeProductFromWishlist(Request $request){
        $wishlist = Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();

        if($wishlist == null){
            session()->flash('error','Product already removed');
            return response()->json([
                'status' => true,
                'message'=> ''
                ]);
        }else{
            Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product removed Successfully');
            return response()->json([
                'status' => true,
                'message'=> ''
            ]);
        }
    }
    public function showChangePasswordForm(Request $request){
        return view('front.account.common.change-password');
    }
    public function changePassword(Request $request){
        $validator = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|same:new_password'
        ]);
        $user = User::where('id',Auth::user()->id)->first();
        
        if(!Hash::check($request->old_password, $user->password)){
            return redirect()->back()->with('error','Your Old Password is incorrect, please try again.');
        }
        User::where('id',$user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        return redirect()->back()->with('success','You Have successfully changed your password.');
    }
    public function forgotPassword(){
        return view('front.account.forgot-password');
    }
    public function processForgotPassword(Request $request){
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        if($validator){
            $token = Str::random(60);

            DB::table('password_reset_tokens')->where('email',$request->email)->delete();
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            // Send Email
            $user = User::where('email', $request->email)->first();
            $formData = [
                'token' =>$token,
                'user' =>$user,
                'mailSubject' => 'You Have Requested to reset your password'
            ];
            Mail::to($request->email)->send(new resetPasswordEmail($formData));
            return redirect()->route('front.forgotPassword')->with('success','Please check your inbox to rest your password');
        }

    }
    public function resetPassword($token){

        $tokenExists = DB::table('password_reset_tokens')->where('token',$token)->first();
        if ($tokenExists == null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid Request');
        }
        return view('front.account.common.reset-password',compact('token'));
    }
    public function processRequestPassword(Request $request){
        $token = $request->token;
        $tokenObj = DB::table('password_reset_tokens')->where('token',$token)->first();
        if ($tokenObj == null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid Request');
        }
        $user = User::where('email',$tokenObj->email)->first();

        $validator = $request->validate([
            'password' => 'required|min:3',
            'confirm_password' => 'required|min:3|same:password'
        ]);
        User::where('id',$user->id)->update([
            'password' => Hash::make($request->password)
        ]);
        DB::table('password_reset_tokens')->where('email',$user->email)->delete();

        return redirect()->route('account.login')->with('success','You have Successfully Changed Your password');
    }
}
