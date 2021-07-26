<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;

class CategoryController extends Controller
{
    public function view(){
        $data['allData'] = Category::all();

        return view('backend.category.view-category', $data);
    }

    public function add(){
    
        return view('backend.category.add-category');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name_bn' => 'required|unique:categories',
            'name_en' => 'required|unique:categories',
            'status' => 'required',
        ]);
        $category = new Category();
        $category->name_bn = $request->name_bn;
        $category->name_en = $request->name_en;
        $category->status = $request->status;
        $category->save();

        return redirect()->route('categories.view')->with('success', 'Data inserted successfully');
    }

    public function edit($id){
        $editData = Category::find($id);

        return view('backend.category.add-category', compact('editData'));
    }

    public function update(Request $request, $id){
        $category = Category::find($id);
        $category->name_bn = $request->name_bn;
        $category->name_en = $request->name_en;
        $category->status = $request->status;
        $category->save();

        return redirect()->route('categories.view')->with('success', 'Data updated successfully');
    }

    public function delete(Request $request){
        $category = Category::find($request->id);
        $category->delete();

        return redirect()->route('categories.view')->with('success', 'Data deleted successfully');
    }
}
