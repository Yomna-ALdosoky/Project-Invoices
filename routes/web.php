<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customers_Repor;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAchiveController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoiceAttchmentController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesReport;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();
Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('invoices', InvoicesController::class);

Route::resource('sections', SectionController::class);

Route::resource('products', ProductController::class);

Route::resource('InvoiceAttachments', InvoiceAttchmentController::class);

Route::get('/section/{id}', [InvoicesController::class, 'getProduct']);

Route::get('/invoiceDetails/{id}', [InvoiceDetailController::class, 'edit']);

Route::get('view_file/{invoice_number}/{file_name}', [InvoiceDetailController::class, 'open_file']);

Route::get('dowenload/{invoice_number}/{file_name}', [InvoiceDetailController::class, 'getfile']);

Route::post('delete_file', [InvoiceDetailController::class, 'destroy'])->name('delete_file');

Route::get('/edit_invoice/{id}', [InvoicesController::class, 'edit']);

Route::get('/Status_show/{id}', [InvoicesController::class, 'show'])->name('Status_show');

Route::post('/status_update/{id}', [InvoicesController::class, 'status_update'])->name('status_update');

Route::get('invoice_Paid', [InvoicesController::class, 'invoice_paid']); // مدفوعه

Route::get('invoice_partial', [InvoicesController::class, 'invoice_partial']); // مدفوعه جزئيه

Route::get('invoice_unPaid', [InvoicesController::class, 'invoice_unPaid']); //غير مدفوعه

Route::resource('Archive', InvoiceAchiveController::class); //الفواتير المتارشفه

Route::get('print_invoice/{id}', [InvoicesController::class, 'print_invoice']);

Route::get('export_invoices', [InvoicesController::class, 'export']);


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::get('invoices_report', [InvoicesReport::class, 'index']);
Route::get('customers_repor', [Customers_Repor::class, 'index'])->name('customers_repor');
Route::post('search_customers', [Customers_Repor::class, 'search_customers']);

Route::post('search_invoices', [InvoicesReport::class, 'search_invoices']);
Route::get('MarkAsRead_all', [InvoicesController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');

Route::get('/{page}', 'App\Http\Controllers\AdminController@index');
