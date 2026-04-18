<?php

namespace App\Http\Controllers;

use App\Exports\invoicesExport;
use App\Models\Invoice_attchment;
use App\Models\invoices;
use App\Models\product;
use App\Models\Invoice_detail;
use App\Models\section;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use User as GlobalUser;

class InvoicesController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['invoice_paid']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['invoice_partial']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['invoice_unPaid']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['show']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['print_invoice']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = section::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' =>  $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعه',
            'Value_Status' => '2',
            'note' => $request->note,
        ]);

        $id_invoices = invoices::latest()->first()->id;
        Invoice_detail::create([
            'id_invoices' => $id_invoices,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعه',
            'Value_Status' => '2',
            'note' => $request->note,
            'user' => (Auth::user()->name),

        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = invoices::latest()->first()->id; //بجيب id
            $image = $request->file('pic'); //بجيب requst الصوره
            $file_name = $image->getClientOriginalName(); //اسم الصوره
            $invoice_number = $request->invoice_number; //رقم الفاتوره

            $attchment = new Invoice_attchment();
            $attchment->file_name = $file_name;
            $attchment->invoices_number = $invoice_number;
            $attchment->created_by = Auth()->user()->name;
            $attchment->invoice_id = $invoice_id;
            $attchment->save();

            $imageName = $request->file('pic')->getClientOriginalName();
            $request->pic->move(public_path('Attchment/' . $invoice_number), $imageName);
        }

        // $user = User::first();
        // Notification::send($user, new AddInvoice($id_invoices));


        $user = User::get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new \App\Notifications\App_invoice_new($invoices));

        session()->flash('Add', 'تم اضافه الفاتوره');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoise = invoices::where('id', $id)->first();
        $section = section::all();
        return view('invoices.edit_invoice', compact('invoise', 'section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices::updated([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' =>  $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتوره');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $details = Invoice_attchment::where('invoice_id', $id)->first();

        $id_page = $request->id_page;

        if (!$id_page == 2) {

            if (!empty($details->invoices_number)) {
                // Storage::disk('public_uploads')->delete($details->invoices_number.'/'.$details->file_name); علشان احذف اسم الملف من الفولدر 
                Storage::disk('public_uploads')->deleteDirectory($details->invoices_number);  //بحذف الفولدر كله
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        } else {
            $invoices->delete();
            session()->flash('delete_invoice');
            return redirect('/Archive');
        }
    }

    public function getProduct($id)
    {
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        // dd($products);
        return json_encode($products);
    }

    public function status_update($id, Request $request)
    {

        $invoices = invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' =>  $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoice_detail::create([
                'id_invoices' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else { // غير مدفوعه 3

            $invoices->update([
                'Value_Status' => 3,
                'Status' =>  $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoice_detail::create([
                'id_invoices' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function invoice_paid()
    { //1مدفوعه
        $invoices = invoices::where('Value_Status', 1)->get();
        return view('invoices.invoice_paid', compact('invoices'));
    }

    public function invoice_unPaid()
    { //2غير مدفوعه
        $invoices = invoices::where('Value_Status', 2)->get();
        return view('invoices.invoice_unpaid', compact('invoices'));
    }

    public function invoice_partial()
    { //مدفوعه جزئيا 3
        $invoices = invoices::where('Value_Status', 3)->get();
        return view('invoices.invoice_partial', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoices'));
    }

    public function export()
    {
        return Excel::download(new invoicesExport, 'invoices.xlsx');
    }

    public function MarkAsRead_all(Request $request) {
        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification){
            $userUnreadNotification->markAsRead();
            return back();

        }
    }
}
