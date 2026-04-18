<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attchment;
use App\Models\Invoice_detail;
use App\Models\invoices;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;


class InvoiceDetailController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:اضافة مرفق', ['only' => ['create','store']]);
        $this->middleware('permission:حذف المرفق', ['only' => ['destroy']]);
    }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_detail $invoice_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $details = Invoice_detail::where('id_invoices', $id)->get();
        $attachment = Invoice_attchment::where('invoice_id', $id)->get();



        return view('invoices.details_invoices', compact('invoices', 'details', 'attachment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_detail $invoice_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoices = Invoice_attchment::findOrFail($request->id_file);
        $invoices->delete(); //علشان يحذفه من الداتابيس
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name); //علشان يجذفه من الملفات
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
       
    }


    public function open_file($invoice_number, $file_name)
    {
        $st = "Attchment";
        $pathToFile = public_path($st . '/' . $invoice_number . '/' . $file_name);
        return response()->file($pathToFile);
    }

    public function dowenload($invoice_number, $file_name){
        $st = 'Attchment';
        $contents= public_path($st. '/'. $invoice_number. '/'.  $file_name);
        return response()->dowenload($contents);

    }

}
