<?php

namespace App\Http\Controllers\admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index(Request $request){
        $pages = Page::Latest();

        if($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }
        $pages = $pages->paginate(10);

        return view('admin.pages.list', compact('pages'));
    }
    public function create(){
        return view("admin.pages.create");
    }
    public function store(Request $request){
        $validator = $request->validate([
            "name"=> "required",
            "slug"=>"required"
        ]);

        $page = new Page;
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        return redirect()->back()->with("success","Page Added Successfully");
    }
    public function edit($id){
        $page = Page::findOrFail($id);
        if($page == null){
            return redirect()->route('pages')->with("error","Page Not Found");
        }
        return view('admin.pages.edit', compact('page'));
    }
    public function update(Request $request, $id){

        $page = Page::find( $id );
        if($page == null){
            return redirect()->route('pages')->with("error","Page Not Found");
        }
        $validator = $request->validate([
            'name'=> 'required',
            'slug'=> 'required',
            'content' => 'required'
        ]);


        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        return redirect()->route('pages')->with('success','Page Edited Successfully');
    }
    public function destroy($id){
        $page = Page::findOrFail($id);
        $page->delete();
        return redirect()->back()->with('success','Page Deleted Successfully');
    }
}
