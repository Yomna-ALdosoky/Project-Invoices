<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:المنتجات', ['only' => ['index']]);
         $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
         $this->middleware('permission: حذف منتج', ['only' => ['destroy']]);
    
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = section::all(); // من الداتابيس  session علشان اجيب اليانات جدول   
        $products = product::all(); //من الداتابيس products  علشام اجيب بيانات جدول ال
        return view('products.products', compact('sections', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //ببعبت البانات للداتابيس (insert)
        $validated = $request->validate([
            'product_name' => 'required|unique:products|max:255',
            'section_id' => 'required',
        ], [
            'product_name.required' => 'اسم المنتج مطلوب',
            'section_id.required' => 'مطلوب تحديد نوع المنتح',
        ]);
        product::create([
            'product_name' => $request->product_name,
            'section_id'   => $request->section_id,
            'description'  => $request->description,
        ]);
        session()->flash('Add', 'تم اضافه المنتج بنجاج');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = section::where('section_name', $request->section_name)->first()->id;

        $product = product::findOrfail($request->pro_id);

        $product->update([
            'product_name' => $request->Product_name,
            'description' => $request->description,
            'section_id' => $id,
        ]);
        session()->flash('Edit', 'تم تعديل القسم بنجاج');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product= product::findOrfail($request->pro_id);
        $product->delete();
        session()->flash('delete', 'تم جذف المنتج');
        return back();
    }
}
