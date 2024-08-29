<?php

namespace App\Http\Controllers;
use App\Models\Page;
use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use App\Mail\ContactEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function index(){
        $featuredProducts = Product::where('is_featured','Yes')->orderBy('id','asc')->where('status',1)->get();
        $latestProducts = Product::orderBy('id','asc')->where('status',1)->take(8)->get();
        // p($featuredProducts->toArray());
        return view("front.home",compact("featuredProducts","latestProducts"));
    }

    public function addToWishList(Request $request){

        if(Auth::check() == false){
            session(["url.intent"=> url()->previous()]);
            return response()->json([
                "status"=> false,
            ]);
        }
        $product = Product::find($request->id);
        if(!$product){
            return response()->json([
                "status"=> true,
                "message"=>'<div class="alert alert-danger">Product Not Found</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id'=> $request->id
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id'=> $request->id
            ]
        );

        

        return response()->json([
            "status"=> true,
            "message"=>'<div class="alert alert-success"><strong>'.$product->title.'</strong> Added to your Wishlist</div>'
        ]);
    }
    public function page($slug){
        $page = Page::where('slug',$slug)->first();
        // p($page);
        if($page == null){
            abort(404);
        }
        return view('front.page',compact('page'));
    }
    public function sendContactEmail(Request $request){
        $validator = $request->validate([
            'name'=> 'required',
            'email'=> 'required|email',
            'subject'=> 'required|min:10',
        ]);
        if($validator){
            // send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have Received a contact emails'  
            ];
            $admin = User::where('id',1)->first();
            Mail::to($admin->email)->send(new ContactEmail($mailData));
            return redirect()->back()->with('success','Thanks For Contacting Us, We will Get Back To you Soon');
        }

    }
}
