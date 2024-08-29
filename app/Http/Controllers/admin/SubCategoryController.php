<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
// use App\Http\Controllers\admin\DB;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function index(Request $request){

       $subCategories = DB::table('subcategories')
                            ->select('subcategories.*', 'category.name as categoryName')
                            ->leftJoin('category', 'subcategories.category_id', '=', 'category.id')
                            ->paginate(10);

        $keyword = $request->get('keyword');
        if(!empty($keyword)){
            
            $subCategories = DB::table('subcategories')
                                ->select('subcategories.*', 'category.name as categoryName')
                                ->leftJoin('category', 'subcategories.category_id', '=', 'category.id')
                                ->where('subcategories.name', 'LIKE', "%{$keyword}%")
                                ->orWhere('category.name', 'LIKE', "%{$keyword}%")
                                ->paginate(10);
            // $subCategory = SubCategory::where('name', 'LIKE', "%{$keyword}%")->latest()->paginate(10);
        }
       
        // echo '<pre>';
        // print_r($subCategories);
        // die;
        // $subCategories = SubCategory::latest()->paginate(10);
        
        // $subCategories = SubCategory::hydrate($subCategories);
        return view("admin.subcategory.list",compact("subCategories"));

    }
    public function create(){
        $categories = Category::orderBy("name","asc")->get();
        $data = compact('categories'); 
        return view('admin.subcategory.create',$data);
    }
    public function store(Request $request){

        // print_r($request->all());
        // exit;
        $validator = $request->validate([
            'category' => 'required',
            'name' =>'required',
            'slug' => 'required|unique:subcategories',
            'status' => 'required',
        ]);

        if($validator){
            $SubCategory = new SubCategory();
            $SubCategory->name = $request->name; 
            $SubCategory->slug = $request->slug;
            $SubCategory->category_id = $request->category;
            $SubCategory->status = $request->status;
            $SubCategory->showHome = $request->showHome;

            $SubCategory->save();
            return redirect()->route('subcategory')->with('success','Sub-Category Created Successsfully');
        }
    }
    public function edit(Request $request,$id){
        $subCategory = SubCategory::find($id);
        if(!$subCategory){
            return redirect()->route('subcategory')->with('error','Record Not Found');
        }
        
        $categories = Category::orderBy("name","asc")->get();
        $data = compact('subCategory','categories'); 
        
        return view('admin.subcategory.edit',$data);
    }
    public function update(Request $request,$id){
        // echo '<pre>';
        // print_r($id);
        // exit;
        $subCategory = SubCategory::find($id);
        
        if(empty($subCategory)){
            return redirect()->route('subcategories.edit')->with('error','Invalid Id');
        }
        $validator = $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:subcategories,slug,'.$subCategory->id.',id',
            'status' => 'required',
        ]);
        if($validator){
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;   
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();
            return redirect()->route('subcategory')->with('success','Sub-Category Upadated Sucessfully');
        }

    }
    public function destroy($id){
        // print_r($id);
        // exit;
        SubCategory::find($id)->delete();

        return redirect()->route('subcategory')->with('success','Sub-Category Deleted Sucessfully');

    }

}
