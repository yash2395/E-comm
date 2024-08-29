<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

use Illuminate\Http\Request;
use App\Models\ProductRating;


class ShopController extends Controller
{
    // public function index(Request $request, $categorySlug = null,$subCategorySlug = null){
    //     $categorySelected = '';
    //     $subCategorySelected = '';
    //     $brandsArray = [];
    //     $categories = Category::orderBy('name','asc')->with('sub_category')->where('status',1)->get();
    //     // $products = Product::orderBy('id','desc')->where('status',1)->get();
    //     $products = Product::orderBy('id','desc')->paginate(3);
    //     if(!empty($categorySlug)){
    //         $category = Category::where('slug',$categorySlug)->first();
    //         $products = Product::where('category_id', '=',$category->id)->get();
    //         $categorySelected = $category->id;
    //     }
        
    //     if(!empty($subCategorySlug)){
    //         $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
    //         $products = Product::where('sub_category_id',$subCategory->id)->get();
    //         $subCategorySelected = $subCategory->id;
    //     }
    //     if(!empty($request->get('brands'))){
    //         $brandsArray = explode(',',$request->get('brands')) ;
    //         $products = $products->whereIn('brand_id', $brandsArray);
    //         // p($products);
    //     }
    //     if($request->get('price_max') != '' && $request->get('price_min') != ''){
    //         // p($request->get('price_max'));
            
    //         $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
    //     }
    //     // v($products);
    //     if($request->get('sort') == ''){
    //         if($request->get('sort') == 'latest'){
    //             $products = Product::orderBy('id','desc')->get();
    //         }
    //         elseif($request->get('sort') == 'price_desc'){
    //             $products = Product::orderBy('price','desc')->get();
    //         }
    //         // else{
    //         //     $products = Product::orderBy('price','asc')->get();
                
    //         // }
            
    //     }
        
        
    //     $products = $products->paginate(3);
    //     // $products = Product::orderBy('id','desc')->get();
    //     // p($products);
    //     $brands = Brand::orderBy('name','asc')->where('status',1)->get();
    //     $priceMax = intval($request->get('price_max'));
    //     $priceMin = intval( $request->get('price_min'));
    //     $sort = $request->get('sort') ;
    //     $data = compact('categories','brands','products','categorySelected','subCategorySelected','brandsArray','priceMax','priceMin','sort');
    //     // p($data);
    //     return view("front.shop", $data);
    // }
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];
        $categories = Category::orderBy('name', 'asc')->with('sub_category')->where('status', 1)->get();
        
        $productsQuery = Product::orderBy('id', 'desc')->where('status', 1);
        
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $productsQuery->where('category_id', '=', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $productsQuery->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($request->get('brands'))) {
            $brandsArray = explode(',', $request->get('brands'));
            $productsQuery->whereIn('brand_id', $brandsArray);
        }

        if ($request->has(['price_max', 'price_min'])) {
            $productsQuery->whereBetween('price', [
                intval($request->get('price_min')),
                intval($request->get('price_max'))
            ]);
        }

        if(!empty($request->get('search'))) {
            $keyword = $request->get('search');
            $productsQuery->where('title','like',"%{$keyword}%");
        }

        if ($request->get('sort')) {
            // p($request->get('sort'));
            if ($request->get('sort') == 'latest') {
                $productsQuery->orderBy('id', 'desc');
            } elseif ($request->get('sort') == 'price_desc') {
                $productsQuery->orderBy('price', 'desc');
                // v($productsQuery->toArray());
            }else{
                $productsQuery->orderBy('price','asc');
                            
                }
        }

        // if($request->get('sort') == ''){
        //             if($request->get('sort') == 'latest'){
        //                 $products = Product::orderBy('id','desc')->get();
        //             }
        //             elseif($request->get('sort') == 'price_desc'){
        //                 $products = Product::orderBy('price','desc')->get();
        //             }
        //             else{
        //                 $products = Product::orderBy('price','asc')->get();
                        
        //             }
                    
        // }
        // dd($productsQuery);
        $products = $productsQuery->paginate(12);
        
        $brands = Brand::orderBy('name', 'asc')->where('status', 1)->get();
        $priceMax = intval($request->get('price_max'));
        $priceMin = intval($request->get('price_min'));
        $sort = $request->get('sort');
        $data = compact('categories', 'brands', 'products', 'categorySelected', 'subCategorySelected', 'brandsArray', 'priceMax', 'priceMin', 'sort');
        
        return view("front.shop", $data);
    }
    public function product($slug){

        $product = Product::where("slug", $slug)->first();
        $relatedProducts=[] ;
        if($product->related_products){
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->get();
        }
        // dd($product);
        if (!$product) {
            abort(404);
        }
        return view('front.product',compact('product','relatedProducts'));
    }
    public function saveRating(Request $request,$id){
        $validator = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email',
            'review' => 'required',
            'rating' => 'required'
        ]);
        // p($request->all()); 
        $productRating = new ProductRating;
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->review;
        $productRating->rating = $request->rating;
        $productRating->status = 0;
        $productRating->save();

        return  redirect()->back()->with('success','Thanks For Your Rating');
    }
}
