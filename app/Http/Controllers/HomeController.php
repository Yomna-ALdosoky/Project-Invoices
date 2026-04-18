<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // احصائيات نسبه الحالات
        $count_all = invoices::count();

        // الحركة دي بتخلي المقام دايماً 1 لو الداتا بيز فاضية، فميطلعش Error قسمة
        $divisor = $count_all > 0 ? $count_all : 1;

        $count_invoices2 = invoices::where('Value_Status', 2)->count(); // الغير مدفوعه
        $nspainvoices2 = ($count_invoices2 / $divisor) * 100;

        $count_invoices1 = invoices::where('Value_Status', 1)->count();
        $nspainvoices1 = ($count_invoices1 / $divisor) * 100;

        $count_invoices3 = invoices::where('Value_Status', 3)->count();
        $nspainvoices3 = ($count_invoices3 / $divisor) * 100;
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير مدفوعه', 'الفواتير المدفوعه', 'الفواتير المدفوعه جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#C51605'],
                    'data' => [$nspainvoices2]
                ],
                [
                    "label" => "الفواتير المدفوعه",
                    'backgroundColor' => ['#03C988'],
                    'data' => [$nspainvoices1]
                ],
                [
                    "label" => "الفواتير المدفوعه جزئيا",
                    'backgroundColor' => ['#E47312'],
                    'data' => [$nspainvoices3]

                ]

            ])

            ->options([]);
        // return view('home', compact('chartjs'));


        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => [$nspainvoices2, $nspainvoices1, $nspainvoices3]
                ]
            ])
            ->options([]);

        // return view('home', compact('chartjs', 'chartjs_2'));
        return view('home', compact('chartjs', 'chartjs_2', 'nspainvoices1', 'nspainvoices2', 'nspainvoices3'));
    }
}
