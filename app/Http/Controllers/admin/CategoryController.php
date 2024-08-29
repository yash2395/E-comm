<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request){


        // $categories = Category::orderby("created_at","desc")->paginate(10);
        // print_r($request->get('keyword'));
        // exit;
        $categories = Category::latest()->paginate(10);
        if(!empty($request->get("keyword"))){
            $keyword = $request->get('keyword');
            $categories = Category::where ('name', 'LIKE', "%{$keyword}%")->latest()->paginate(10);
            // $categories = Category::where("name","like","'%".$request->get('keyword') ."%'")->get();
        }
        return view("admin.category.list",compact("categories"));
    }
    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
        // print_r($request->all());
        // exit;

        $validator = $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:category',
            'status' => 'required',
        ]);
        if($validator){
            // print_r($request->all());
            // exit;

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            // $category->image = $request->file('image')->store('uploads');
            $category->save();


            return redirect()->route('category')->with('success','Category Added Sucessfully');

        }
    }
    public function edit($id, Request $request){
        // print_r($id);
        // exit;
        $category = Category::find($id);

        if(empty($category)){
            return redirect()->route('category')->with('error','Invalid Id');
        }
        return view('admin.category.edit',compact('category'));
    }
    public function update($id, Request $request){
        $category = Category::find($id);
        if(empty($category)){
            return redirect()->route('admin.category.edit')->with('error','Invalid Id');
        }
        $validator = $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:category,slug,'.$category->id.',id',
            'status' => 'required',
        ]);
        if($validator){
            // print_r($request->all());
            // exit;
            // $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();
            return redirect()->route('category')->with('success','Category Upadated Sucessfully');
        }
    }
    public function destroy($id){
        // print_r($id);
        // exit;
        Category::find($id)->delete();
        return redirect()->route('category')->with('success','Category Deleted Sucessfully');

    }
}
