<?php
use App\Models\Page;
use App\Models\Order;
use App\Models\Country;
use App\Mail\OrderEmail;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;

function getCategories(){
    return Category::orderBy("name","asc")
            ->with('sub_category')
            ->orderBy('id','desc')
            ->where('status','=',1)
            ->where('showHome','Yes')->get();
}

// echo "hell";
if(!function_exists('p')){
    function p($data){
        echo '<pre>';
        print_r($data);
        exit;
    }
}
if(!function_exists('v')){
    function v($data){
        echo '<pre>';
        var_dump($data);
        exit;
    }
}

function orderEmail($orderID, $userType='customer'){
    $order = Order::where('id',$orderID)->with('items')->first();
    // p($order->toArray());

    if($userType == 'customer'){
        $subject = 'Thanks for your order';
        $email = $order->email;
    }else{
        $subject = 'You have received an order';
        $email = env('ADMIN_EMAIL');
    }
    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
    // dd($order->toArray());
}

function getCountryInfo($id){
    return Country::where('id',$id)->first();
}
function staticPages(){
    $pages = Page::orderBy('name','asc')->get();
    return $pages;
}
?>