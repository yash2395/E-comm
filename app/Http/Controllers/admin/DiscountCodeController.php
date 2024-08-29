<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use App\Http\Controllers\Controller;

class DiscountCodeController extends Controller
{
    public function index(){
        $disountCoupons = DiscountCoupon::latest()->paginate(10);
        // if(!empty)
        return view("admin.coupon.list",compact("disountCoupons"));
    }
    public function create(){
        return view("admin.coupon.create");
    }
    public function store(Request $request){
        $validator = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);
        if($validator){

            //starting date must be greater than current date


            //expring date must be greaeter than starting date
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_users;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();
        }
        return redirect()->route('coupons')->with('success','Discount Coupon Added Successfully');
    }
    public function show($id){

    }
    public function edit($id){

    }
    public function update(Request $request, $id){

    }
    public function destroy($id){

    }

}
