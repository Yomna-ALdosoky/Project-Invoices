<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\invoices;
use Illuminate\Http\Request;


class InvoicesReport extends Controller
{
    public function index(){
        return view('reports.invoices_report');
    }

    //حاله البحث بنوع الفاتوره
    public function  Search_invoices(Request $request){
        $rdio = $request->rdio;

        // في حاله عدم تحديد التاريخ

        if ($rdio == 1) {
            if ($request->type && $request->start_at =='' && $request->end_at=='') {

                $invoices = invoices::select('*')->where('Status', '=', $request->type)->get();
                $type= $request->type;
                return view('reports.invoices_report', compact('type'))->withDetails( $invoices );
            }

           //في حاله تحديد التاريخ
            else {
                $start_at= $request->start_at;
                $end_at= $request->end_at;
                $type= $request->type;

                $invoices= invoices::select('*')->whereBetween('invoice_Date', [$start_at,$end_at])->get();
                return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
            }


        } 
         //البحث برقم الفانوره
        else {
            $invoices= invoices::select('*')->where('invoice_number', $request->invoice_number)->get();
            return view('reports.invoices_report')->withDetails( $invoices );
        }
       
        
        
        
    }
}
