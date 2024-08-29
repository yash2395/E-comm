<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;

use App\Models\Product;



class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::orderBy("id","asc");
        // \dd($products);
        if($request->get('keyword') != ''){
            $products = $products->where('title','LIKE','%'.$request->keyword.'%');
        }
        $products = $products->paginate(10);
        return view("admin.products.list",compact("products"));
    }
    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();

        return view("admin.products.create",compact("categories","brands"));
    }
    public function store(Request $request){
        // echo '<pre>';
        // var_dump($_FILES);
        // exit;
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category'=> 'required|numeric',
            // 'is_featured'=> 'required|in:Yes,No',
        ];
        
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }
        $validator = $request->validate($rules);
        if( $validator){
            // echo'<pre>';
            // var_dump($request);
            // exit;
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products) ? implode(',',$request->related_products) : '');

            $product->save();
        }
        return redirect()->route('products')->with('success','Product Crreated Successfully');
        
    }
    public function edit($id, Request $request){
        $relatedProducts = [];
        $product = Product::find($id);
        // p($product);
        $categories = Category::orderBy('name','ASC')->get();
        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
        $brands = Brand::orderBy('name','ASC')->get();
        // fetch related products
        if($product->related_products){
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->get();
        }

        if(empty( $product )){
            return redirect()->route('products')->with('error','Product Not Found');
        }
        $data = compact('product','categories','brands','subCategories','relatedProducts');
        return view('admin.Products.edit',$data);
    }
    public function update(Request $request, $id){
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',

            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category'=> 'required|numeric',
            // 'is_featured'=> 'required|in:Yes,No',
        ];
        
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }
        $validator = $request->validate($rules);
        if( $validator){
            // echo'<pre>';
            // var_dump($request);
            // exit;
            // $product = new Product();

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products) ? implode(',',$request->related_products) : '');
            
            $product->save();
        }
        return redirect()->route('products')->with('success','Product Updated Successfully');
        
    }
    public function destroy($id){
        // print_r($id);
        // exit;
        Product::find($id)->delete();
        return redirect()->route('products')->with('success','Product Deleted Sucessfully');

    }
    public function getProducts(Request $request){
        $tempProduct = [];
        if($request->term !=''){
            $products = Product::where('title','LIKE','%'.$request->term.'%')->get();
            // p($products);
            if($products){
                foreach($products as $product){
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }
}
