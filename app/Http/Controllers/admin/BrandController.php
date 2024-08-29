<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::latest('id');
        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.list', compact('brands'));
    }
    public function create(Request $request){
        return view("admin.brands.create");
    }
    public function store(Request $request){

        // print_r('hi');
        // exit;
        $validator = $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:brands',
        ]);
        if($validator){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return redirect()->route('brands')->with('success','Brand Created Successfully');
        }

    }
    public function edit($id, Request $request){
        
        $brand = Brand::find($id);
        if(empty($brand)){
            return redirect()->route('brands')->with('error','Record not found');
        }
        return view('admin.brands.edit', compact('brand'));
    }
    public function update(Request $request, $id){


        $brand = Brand::find($id);
        
        if(empty($brand)){
            return redirect()->route('brands.edit')->with('error','Invalid Id');
        }
        $validator = $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required',
        ]);
        if($validator){
            $brand->name = $request->name;
            $brand->slug = $request->slug;   
            $brand->status = $request->status;
            $brand->save();
            return redirect()->route('brands')->with('success','Category Upadated Sucessfully');
        }
    }
}
