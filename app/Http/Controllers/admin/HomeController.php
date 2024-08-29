<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index(){

        $totalOrders = Order::where('status','!=','cancelled' )->count();
        $totalProduct = Product::count();
        $totalCustomers = User::where('role','=',1 )->count();
        $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

        // This month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        // p($totalRevenue);
        $currentDate = Carbon::now()->format('Y-m-d');
        $revenueThisMonth = Order::select('grand_total','id')->where('status','!=','cancelled')
                            ->whereDate('created_at','>=',$startOfMonth)
                            ->whereDate('created_at','<=',$currentDate)
                            ->sum('grand_total');

        // Revenue of Last Month
        $startofLastMonth = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endofLastMonth = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $revenueLastMonth = Order::where('status','!=','cancelled')
                            ->whereDate('created_at','>=',$startofLastMonth)
                            ->whereDate('created_at','<=',$endofLastMonth)
                            ->sum('grand_total');

        // Last 30 Days Sale
        $date = Carbon::now()->format('Y-m-d');
        $lastThirtythdate = Carbon::now()->subDays(30)->format('Y-m-d');
        $revenueLastThirtyDays = Order::where('status','!=','cancelled')
                                ->whereDate('created_at', '>=',$lastThirtythdate)
                                ->whereDate('created_at','<=',$date)
                                ->sum('grand_total');
        // p($revenueLastThirtyDays);

        $data = compact('totalOrders','totalProduct','totalCustomers','totalRevenue','revenueThisMonth','revenueLastMonth','revenueLastThirtyDays');
        // $admin = Auth::guard('admin')->user();
        // echo 'Welcome '. $admin->name.'<a href="'.route('admin.logout').'"> Logout</a>';
        return view('admin.dashboard',$data);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
