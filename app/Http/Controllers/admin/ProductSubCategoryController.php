<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request){
        // $this->info($request->category_id);
        // $subCategory = $request->category_id;
        // echo '<pre>';
        // print_r($subCategory);
        // exit;
        if(!empty($request->category_id)){
            $subCategory =  SubCategory::where('category_id', $request->category_id)
            ->orderBy('name','ASC')->get();

        return response()->json([
            'status' => true,
            'subCateogries' => $subCategory
        ]);
    }else{
        return response()->json([
            'status'=> true,
            'subCategory' => []
        ]);
    }

    }
}
