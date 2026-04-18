<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\invoices;
use App\Models\section;
use Illuminate\Http\Request;

class Customers_Repor extends Controller
{
    public function index(){
        $sections= section::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function search_customers(Request $request){

        //في حاله البحث بدون تاريخ

        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invioces = invoices::select('*')->where('section_id', '=' ,$request->Section)->where('product', '=' ,$request->product)->get();

            $sections= section::all();

            return view('reports.customers_report', compact('sections'))->withDetails($invioces);
        } 

        //في حاله تحديد التاريخ
        else {
            $start_at= $request->start_at;
            $end_at= $request->end_at;

            $invioces= invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('section_id', $request->Section)->where('product', '=' ,$request->product)->get();
            $sections= section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invioces);
        }
        
    }
}
