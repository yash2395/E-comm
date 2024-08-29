<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function showChangePasswordForm(){
        return view("admin.changePassword");
    }
    public function processChangePassword(Request $request){
        $validator = $request->validate([
            "old_password"=> "required",
            "new_password"=> "required",
            "conform_password"=> "required|same:new_password",
        ]);

        $admin = User::where('id',Auth::guard('admin')->user()->id)->first();
        if(!Hash::check($request->old_password, $admin->password)){
            return back()->with('error','Your Old Password is Incorrect, please try again.');
        }

        User::where('id',Auth::guard('admin')->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        return back()->with('success','You Have successfully changed your password.');
    }
}
