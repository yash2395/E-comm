<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        if(!empty($request->get('keyword'))){
            $users = $users->where('name','like','%'.$request->get('keyword').'%')
                        ->orwhere('email','like','%'.$request->get('keyword').'%');
        }
        $users = $users->paginate(10);
        // dd($users->toArray());
        return view("admin.users.list",compact("users"));
    }
    public function create(Request $request){
        return view("admin.users.create");
    }
    public function store(Request $request){
        $validator = $request->validate([
            "name"=> "required",
            "email"=> "required|email|unique:users",
            "password"=> "required|min:5",
            "phone"=> "required"
        ]);
        if ($validator) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make( $request->password );
            $user->phone = $request->phone;
            $user->save();
            return redirect()->back()->with("success","User Created  Suuccesssfully");
        }
    }
    public function edit(Request $request,$id){
        $user = User::find($id);

        if($user == Null){
            $message = 'User Not Found';
            return redirect()->back()->with('error', $message);
        }

        return view("admin.users.edit",compact("user"));
    }
    public function update(Request $request,$id){
        $user = User::find($id);
        // p($request->all());
        if($user == Null){
            $message = 'User Not Found';
            return redirect()->back()->with('error', $message);
        }
        // $password = '';
        // if($request->password != ''){
        //     $password = $request->password;
        // }
        // p($password);
        $validator = $request->validate([
            "name"=> "required",
            'email' => 'required|email',
            // "email"=> 'required|email|unique:users,email,'. $id .',id',
            "password"=> "",
            "phone"=> "required"
        ]);
        if ($validator) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make( $request->password );
            $user->phone = $request->phone;
            $user->save();
            return redirect()->back()->with("success","User Created  Suuccesssfully");
        }
    }
}
