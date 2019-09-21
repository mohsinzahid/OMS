<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function ()
{
    return view('front');
});


Route::group(['middleware' => 'auth'], function()
{
    Route::get('/admin', function ()
    {
        return view('admin.master');
    });

    Route::get('/dashboard',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return view('admin.master');
        else
            return view('admin.master');
    });

// supplier start start

/*Route::get('/vendor/add', function () {
    return view('vendor.add', ["msg"=>'']);
});*/

    Route::get('/supplier/add',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return view('trading.forms.supplier opening.add', ["msg"=>'']);
        else
            return view('admin.master');
    });

//Route::post('/vendor/postvendor','VendorController@store');

    Route::post('/vendor/postvendor',function (Request $request)
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\VendorController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/vendor/index','VendorController@index');

    Route::get('/vendor/index',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\VendorController@index');
        else
            return view('admin.master');
    });

//Route::get('/vendor/{id}/edit','VendorController@edit');

    Route::get('/supplier/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\VendorController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/vendor/updatevendor/{id}','VendorController@update');

    Route::post('/vendor/update-vendor', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\VendorController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::post('/supplier-adjustment/postpayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SupplierAdjustmentController@store',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/supplier/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\VendorController@destroy',[$id]);
        else
            return view('admin.master');
    });

/*Route::get('/vendor/{id}/delete','VendorController@destroy');*/

// vendor end

// inventory report start

    Route::get('/inventory/inventory-report',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\InventoryReportsController@InventoryReport');
        else
            return view('admin.master');
    });

    Route::get('/inventory-report/ajaxupdate','InventoryReportsController@InventoryReportAjaxUpdate');

    Route::get('/inventory/inventory-summary',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return  view('inventory.reports.inventory summary');
        else
            return view('admin.master');
    });

    Route::get('/inventory-summary-report/ajaxupdate','InventoryReportsController@InventorySummaryReportAjaxUpdate');


// inventory report end

//Stock start

/*Route::get('/stock/add','StockController@index');*/

    Route::get('/stock/add',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\StockController@create');
        else
            return view('admin.master');
    });

//Route::post('/stock/poststock','StockController@store');

    Route::post('/stock/poststock',function (Request $request)
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\StockController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/stock/show','StockController@show');



//Route::get('/stock/{id}/edit','StockController@edit');

    Route::get('/stock/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\StockController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/stock/Updatestock','StockController@update');

    Route::post('/stock/Updatestock', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\StockController@update',[$request]);
        else
            return view('admin.master');
    });

//Stock End

//Waste Start

//Route::post('/wastage/postwaste','WasteController@store');

    Route::post('/wastage/postwaste', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 4 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/wastage/add','WasteController@create');

    Route::get('/wastage/add',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@create');
        else
            return view('admin.master');
    });

//Route::get('/wastage/show','WasteController@index');

    Route::get('/wastage/show',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@index');
        else
            return view('admin.master');
    });

//Route::get('/wastage/{id}/edit','WasteController@edit');

    Route::get('/wastage/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/wastage/updatewaste','WasteController@update');

    Route::post('/wastage/updatewaste', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 4 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@update',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/wastage/{id}/delete','WasteController@destroy');

    Route::get('/wastage/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 4 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\WasteController@destroy',[$id]);
        else
            return view('admin.master');
    });


//Waste End

//Purchase  Start

//Route::get('/purchase/add','PurchasesReportsController@create');

    Route::get('/purchase/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchaseOrderController@create');
        else
            return view('admin.master');
    });

//Route::post('/purchase/store','PurchasesReportsController@store');

    Route::post('/purchase/store', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchaseOrderController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/purchase/index','PurchasesReportsController@index');

    Route::get('/purchases/supplier_ledger', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchasesReportsController@SupplierLedger');
        else
            return view('admin.master');
    });

    Route::get('/purchase/supplier_ledger_detail', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchasesReportsController@SupplierLedgerDetail');
        else
            return view('admin.master');
    });

Route::get('/supplier-ledger/ajaxupdate', 'PurchasesReportsController@SupplierLedgerAjaxUpdate');

Route::get('/supplier-ledger-detail/ajaxupdate', 'PurchasesReportsController@SupplierLedgerDetailAjaxUpdate');

//Route::get('/purchase/{id}/edit','PurchasesReportsController@edit');

    Route::get('/purchase-order/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchaseOrderController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::get('/purchase/{id}/delete','PurchasesReportsController@destroy');

    Route::get('/purchase-order/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchaseOrderController@destroy',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/purchase/update', 'PurchasesReportsController@update');

    Route::post('/purchase-order/update', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PurchaseOrderController@update',[$request]);
        else
            return view('admin.master');
    });

/*Route::get('/purchase/ajaxgetdate', 'PurchasesReportsController@date');*/

    Route::get('/supplier-adjustment/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SupplierAdjustmentController@create');
        else
            return view('admin.master');
    });

    Route::get('/supplier-adjustment/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SupplierAdjustmentController@edit',[$id]);
        else
            return view('admin.master');
    });

    Route::post('/supplier-adjustment/updatepayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SupplierAdjustmentController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/supplier-adjustment/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SupplierAdjustmentController@destroy',[$id]);
        else
            return view('admin.master');
    });

//Purchase End

//Purchase Payment Start


//Route::get('/purchasepayment/add','CashPaymentController@create');

    Route::get('/cashpayment/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashPaymentController@create');
        else
            return view('admin.master');
    });

//Route::post('/purchasepayment/postpayment','CashPaymentController@store');

    Route::post('/purchasepayment/postpayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashPaymentController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/purchasepayment/{id}/edit','CashPaymentController@edit');

    Route::get('/cash-payment/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashPaymentController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::get('/purchasepayment/{id}/delete','CashPaymentController@destroy');

    Route::get('/purchasepayment/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashPaymentController@destroy',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/purchasepayment/update','CashPaymentController@update');

    Route::post('/purchasepayment/update', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashPaymentController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/supplier-adjustment/ajax/get-invoice', 'SupplierAdjustmentController@getinvoice');



//Purchase Payment End

//customer Start

//Route::get('/customer/add', 'CustomerController@build');

    Route::get('/customer/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\CustomerController@build');
        else
            return view('admin.master');
    });

//Route::post('customer/store','CustomerController@store');

    Route::post('customer/store', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\CustomerController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('customer/index','CustomerController@index');

    Route::get('customer/index', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\CustomerController@index');
        else
            return view('admin.master');
    });

//Route::get('/customer/{id}/edit','CustomerController@edit');

    Route::get('/customer/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/customer/update','CustomerController@update');

    Route::post('/customer/update', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerController@update',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/customer/saleprice','CustomerController@create');

    Route::get('/customer/saleprice', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerController@create');
        else
            return view('admin.master');
    });

Route::get('/customer/saleprice/ajaxupdate','CustomerController@show');

//Route::post('/customer/saleprice/add','CustomerController@addsaleprice');

    Route::post('/customer/saleprice/add', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerController@addsaleprice',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/customer/saleprice/{id}/delete','CustomerController@destroy');

    Route::get('/customer/saleprice/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerController@destroy',[$id]);
        else
            return view('admin.master');
    });


//customer End

//Sale Start

/*Route::get('/saleadd','SaleinventoryController@create');*/

    Route::get('/sales/forms/job-order',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\JobOrderController@create');
        else
            return view('admin.master');
    });

    Route::post('/sales/forms/job-order/store',function (Request $request)
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\JobOrderController@store',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/joborder/ajax/get-saleprice', 'JobOrderController@getsaleprice');


    /*Route::post('/sale/store','SaleinventoryController@store');*/

//Route::get('/sale/index','SaleinventoryController@index');

    Route::get('/sales/reports/customerLedgerReport',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2  || Auth::user()->type_id ===  3)
            return app()->call('App\Http\Controllers\SalesReportsController@CustomerLedgerReport');
        else
            return view('admin.master');
    });

Route::get('/sales/reports/customerLedgerReport/ajaxupdate', 'SalesReportsController@CustomerLedgerReportAjaxUpdate');

    Route::get('/reports/customerLedgerDetail/ajaxupdate', 'SalesReportsController@CustomerLedgerDetailAjaxUpdate');

    Route::get('/reports/customerLedgerDetail', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2  || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\SalesReportsController@statement');
        else
            return view('admin.master');
    });


//Route::get('/sale/receipt','SaleinventoryController@receipt');

    Route::get('/sale/receipt',function ()
    {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SalesFormsController@receipt');
        else
            return view('admin.master');
    });

Route::get('/sale/ajaxreceipt', 'SaleinventoryController@getreceipt');

//Route::get('/customer/sale/{id}/edit', 'SaleinventoryController@edit');

    Route::get('/job-order/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\JobOrderController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/sale/changeAmount','SaleinventoryController@update');

    Route::post('/sale/changeAmount', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\SaleinventoryController@update',[$request]);
        else
            return view('admin.master');
    });

//Route::post('customer/sale/update','SaleinventoryController@updatesale');

    Route::post('/job-order/update', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\JobOrderController@update',[$request]);
        else
            return view('admin.master');
    });

//Route:: get('/customer/sale/{id}/delete','SaleinventoryController@destroy');

    Route::get('/customer/sale/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\JobOrderController@destroy',[$id]);
        else
            return view('admin.master');
    });

    Route::get('/job-order/{id}/print-job-order', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 4)
            return app()->call('App\Http\Controllers\JobOrderController@printjoborder',[$id]);
        else
            return view('admin.master');
    });

//Sale End

    //salelist start

    Route::get('/jobs-list/submitted-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return view('jobs list.submitted jobs');
        else
            return view('admin.master');
    });

    Route::get('/jobs-list/ajax-get-submitted-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\JobslistController@submittedjobs');
        else
            return view('admin.master');
    });

    Route::get('/job-order/search', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return view('jobs list.search');
        else
            return view('admin.master');
    });

    Route::get('/job-order/ajaxsearch', 'JobOrderController@AjaxSearch');

    Route::get('/jobs-list/pending-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3 || Auth::user()->type_id === 4)
            return view('jobs list.pending jobs');
        else
            return view('admin.master');
    });

//Route::get('salelist/ajaxupdate','SalelistController@index');

    Route::get('jobs-list/get-pending-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3 || Auth::user()->type_id === 4)
            return app()->call('App\Http\Controllers\JobslistController@pendingjobs');
        else
            return view('admin.master');
    });

    Route::post('/job-list/ajax-update-job-status', 'JobslistController@updatejobstatus');


    Route::get('/jobs-list/printed-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3 || Auth::user()->type_id === 4)
            return view('jobs list.printed jobs');
        else
            return view('admin.master');
    });

//Route::get('salelist/printed', 'SalelistController@getprinted');

    Route::get('/jobs-list/ajax-get-printed-jobs', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3|| Auth::user()->type_id === 4)
            return app()->call('App\Http\Controllers\JobslistController@printedjobs');
        else
            return view('admin.master');
    });



//salelist end


//Purchase Payment Start

    Route::get('/sales/forms/cash-collection', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\CashCollectionController@create');
        else
            return view('admin.master');
    });

//Route::post('/salepayment/postpayment','SalepaymentController@store');

    Route::post('/cash-collection/postpayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\CashCollectionController@store',[$request]);
        else
            return view('admin.master');
    });

//Route::get('/salepayment/{id}/edit','SalepaymentController@edit');

    Route::get('/cash-collection/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashCollectionController@edit',[$id]);
        else
            return view('admin.master');
    });

//Route::get('/salepayment/{id}/delete','SalepaymentController@destroy');

    Route::get('/cash-collection/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashCollectionController@destroy',[$id]);
        else
            return view('admin.master');
    });

//Route::post('/salepayment/update','SalepaymentController@update');

    Route::post('/cash-collection/updatepayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CashCollectionController@update',[$request]);
        else
            return view('admin.master');
    });

//Customer adjustment start

    Route::get('/sales/forms/customer-adjustment', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerAdjustmentController@create');
        else
            return view('admin.master');
    });

    Route::post('/customer-adjustment/postpayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerAdjustmentController@store',[$request]);
        else
            return view('admin.master');
    });

    Route::post('/customer-adjustment/updatepayment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerAdjustmentController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/customer-adjustment/ajax/get-invoice','CustomerAdjustmentController@getinvoice');



    Route::get('/customer-adjustment/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerAdjustmentController@edit',[$id]);
        else
            return view('admin.master');
    });

    Route::get('/customer-adjustment/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\CustomerAdjustmentController@destroy',[$id]);
        else
            return view('admin.master');
    });
//Customer adjustment End

//Edit forms start

    Route::get('/form/edit', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return view('editform');
        else
            return view('admin.master');
    });

    Route::post('/form/edit/redirect', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\EditFormController@show',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/edit-form/ajax/get-id','EditFormController@ajaxgetids');




//Edit forms end

//General Ledger Start
    Route::get('/petty-cash/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2 || Auth::user()->type_id === 3)
            return view("general ledger.forms.petty cash payment.add",['msg' => '']);
        else
            return view('admin.master');
    });

    Route::post('/petty-cash/post-payment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2  || Auth::user()->type_id === 3)
            return app()->call('App\Http\Controllers\PettyCashController@store',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/petty-cash/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PettyCashController@edit',[$id]);
        else
            return view('admin.master');
    });

    Route::post('/petty-cash/update-payment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PettyCashController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/petty-cash/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\PettyCashController@destroy',[$id]);
        else
            return view('admin.master');
    });

    Route::get('/general-ledger-adjustment/add', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerAdjustmentController@Create');
        else
            return view('admin.master');
    });

    Route::get('/general-ledger/{id}/edit', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerAdjustmentController@edit',[$id]);
        else
            return view('admin.master');
    });

    Route::post('/general-ledger/update-adjustment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerAdjustmentController@update',[$request]);
        else
            return view('admin.master');
    });

    Route::post('/general-ledger/post-adjustment', function (Request $request) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerAdjustmentController@store',[$request]);
        else
            return view('admin.master');
    });

    Route::get('/general-ledger/reports', function () {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerReportsController@GeneralLedgerReports');
        else
            return view('admin.master');
    });

    Route::get('/general-ledger-reports/ajaxupdate','GeneralLedgerReportsController@GetGeneralLedgerAjax');

    Route::get('/general-ledger/{id}/delete', function ($id) {
        if (Auth::user()->type_id === 1 || Auth::user()->type_id === 2)
            return app()->call('App\Http\Controllers\GeneralLedgerAdjustmentController@destroy',[$id]);
        else
            return view('admin.master');
    });


//General Ledger End

Route::post('logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('admin');

});

Auth::routes();