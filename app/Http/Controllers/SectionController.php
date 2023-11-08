<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:الاقسام', ['only' => ['index']]);
         $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
         $this->middleware('permission: حذف قسم', ['only' => ['destroy']]);
    
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections= section::all(); // علشان اجيب اليانات من الداتابيس
        return view('sections.sections', compact('sections'));
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
        // اضافه قسم
        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'required',
        ],
            [
                'section_name.required' => 'الاسم مطلوب',
                'section_name.unique' => 'هذا الاسم موجود مسبقا',
                'description.required' => 'الملاحظات مطلوبة',
            ]
        );
     

            section::create([
                'section_name'=>$request->section_name,
                'description'=>$request->description,
                'created_by'=>(Auth::user()->name),
            ]);
            session()->flash('Add', 'تم اضافه القسم بنجاح');
            return redirect('/sections');


    }

    /**
     * Display the specified resource.
     */
    public function show(section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //تعديل القسم
        $id= $request->id;

        $this->validate($request, [
            'section_name' => 'required|unique:sections,section_name|max:255' .$id,
            'description' => 'required',
        ],[
            'section_name.required'=> 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required'=> 'يرجي ادخال البيان',
        ]);
        $sections= section::find($id);
        $sections->update([
            'section_name' =>$request->section_name,
            'description' => $request->description,
        ]);
        session()->flash('edit', 'تم تعديل القسم بنجاح');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //حذف القسم
        $id= $request->id;
        section::find($id)->delete();
        session()->flash('delete', 'تم حذف القسم');
        return redirect('/sections');
    }
}
