<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attchment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttchmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $this->validate($request, [
            'file_name' => 'mimes:bmp,png,pdf,jpeg,jpg'
        ], [
            'file_name.mimes'=> 'صيغه المرفق يجب ام تكون pdf, jpeg, png',
        ]);

        $image = $request->file('file_name');
        $fileName= $image->getClientOriginalName();

        $attchment= new Invoice_attchment;
        $attchment->file_name = $fileName;
        $attchment->invoices_number= $request->invoice_number;
        $attchment->invoice_id= $request->invoice_id;
        $attchment->created_by= Auth::user()->name;
        $attchment->save();

        $imageName= $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attchment/'. $request->invoice_number), $imageName);
        session()->flash('Add', 'تم اضافه المرفق بنجاح');
        return back();




    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_attchment $invoice_attchment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attchment $invoice_attchment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_attchment $invoice_attchment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice_attchment $invoice_attchment)
    {
        //
    }
}
