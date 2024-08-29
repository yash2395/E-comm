<?php

namespace App\Http\Controllers\admin;

use App\Models\Country;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function create(){
       $countries = Country::get();

       $shippingCharges = Shipping::select('shipping_charges.*','countries.name')
                            ->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
            // p(compact('shippingCharges'));
       return view('admin.shipping.create',compact('countries','shippingCharges'));
    }
    public function store(Request $request){
        $count = Shipping::where('country_id', $request->country)->count();
        $validator = $request->validate([
            'country'=> 'required',
            'amount' => 'required|numeric',
        ]);
        if($count > 0){
            return back()->with('error','Country already Exists');
        }
        if($validator){
            $shipping = new Shipping;
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
        }
        return redirect()->back()->with('success','Shipping Charges Updated Successfully');
    }
    public function edit($id){

        $shippingCharges = Shipping::find($id);
        $countries = Country::get();
        // p( $shippingCharges);
        
        return view('admin.shipping.edit',compact('shippingCharges','countries'));
    }
    public function update(Request $request, $id){
        // p('hi');
        $validator = $request->validate([
            'country'=> 'required',
            'amount' => 'required|numeric',
        ]);
        if($validator){
            $shipping = Shipping::find( $id );
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
        }
        return redirect()->route('shipping.create')->with('success','Shipping Charges Updated Successfully');
    }
    public function destroy($id){
        $shippingCharge = Shipping::find($id);

        if($shippingCharge == null){
            return redirect()->back()->with('error','Shipping Does not found');
        }
        $shippingCharge->delete();
        return redirect()->back()->with('error','Shipping Deleted Successfully');
    }
}
