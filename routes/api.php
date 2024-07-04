<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('dashboard','API\DashboardController@index');
    Route::apiResources(['company'=>'API\CompaniesController']);
    Route::get('supplier/company','API\SuppliersController@company');
    Route::apiResources(['supplier'=>'API\SuppliersController']);
    Route::apiResources(['user'=>'API\UsersController']);
    Route::get('roles','API\UsersController@roles');
    Route::get('permissions','API\UsersController@permissions');
    Route::get('permission/{id}','API\UsersController@userPermissions');
    Route::get('role/{id}','API\UsersController@role');
    Route::put('assign-role/{id}','API\UsersController@assignRole');

    // doctors
    Route::apiResources(['doctor'=>'API\DoctorsController']);
    Route::apiResources(['classification'=>'API\ClassificationsController']);
    Route::apiResources(['manufacturer'=>'API\ManufacturersController']);
    Route::apiResources(['group'=>'API\GroupsController']);
    

    Route::get('medicine/classification','API\MedicinesController@classification');
    Route::get('medicine/manufacturer','API\MedicinesController@manufacturer');
    Route::get('medicine/group','API\MedicinesController@group');
    Route::get('medicine/doctor','API\MedicinesController@doctor');

    Route::get('medicine/activation/{id}','API\MedicinesController@activation');

    Route::get('medicine/approved/{state}/{id}','API\MedicinesController@approved');
    Route::get('barcode/print','API\MedicinesController@barcode');
    Route::Post('barcode/print','API\MedicinesController@barcode');
    Route::get('SearchBarcode','API\MedicinesController@SearchBarcode');
    // Route::get('qrcode/print','API\MedicinesController@qrcode');

    Route::get('medicine/barcode/{id}','API\MedicinesController@Findbarcode');
    Route::apiResources(['medicine'=>'API\MedicinesController']);

    Route::get('profile', 'API\ProfileController@index');
    Route::put('profile', 'API\ProfileController@update');


    // 
    Route::get('purchases/approved/{state}/{id}','API\PurchasesController@approved');
    Route::get('purchases/bill-no','API\PurchasesController@billNo');
    Route::get('purchases/edit/{id}','API\PurchasesController@edit');
    Route::apiResources(['purchases'=>'API\PurchasesController']);



    Route::get('counter-sale/bill-no','API\CounterSaleController@billNo');
    Route::get('counter-sale/loadBatch/{id}','API\CounterSaleController@loadBatch');
    Route::get('counter-sale/findBatch/{id}','API\CounterSaleController@findBatch');
    Route::get('counter-sale/barcodeLoadBatch/{id}','API\CounterSaleController@barcodeLoadBatch');
    Route::get('counter-sale/edit/{id}','API\CounterSaleController@edit');
    Route::put('counter-sale/add/{id}','API\CounterSaleController@add');
    Route::put('counter-sale/return/{id}','API\CounterSaleController@return');
    Route::get('counter-sale/report/medicine','API\ReportController@medicine');
    Route::get('counter-sale/report/users','API\ReportController@users');
    Route::post('counter-sale/cash','API\CounterSaleController@cash');
    Route::get('counter-sale/search/{id}','API\CounterSaleController@counterSaleSearch');
    Route::apiResources(['counter-sale'=>'API\CounterSaleController']);

    Route::apiResources(['expenses-category'=>'API\ExpensesCategoryController']);
    Route::apiResources(['incomes'=>'API\IncomesController']);

    
    Route::get('expenses/category','API\ExpensesController@category');
    Route::apiResources(['expenses'=>'API\ExpensesController']);

    Route::get('stocks/low','API\StocksController@low');
    Route::get('stocks/low/details/{id}','API\StocksController@lowDetails');
    Route::get('stocks/expiry','API\StocksController@expiry');
    Route::get('stocks/report','API\StocksController@report');
    Route::get('stocks/medicine','API\StocksController@medicine');
    Route::get('stocks/padding/orders','API\StocksController@orders');
    Route::post('stocks/order/save-to-excite','API\StocksController@AddExcite');

    Route::get('report','API\ReportController@index');
    Route::get('report/purchases','API\ReportController@purchases');
    Route::get('report/order','API\ReportController@order');
    Route::post('report/expenses','API\ReportController@expenses');

    Route::get('notification/create','API\NotificationsController@index');
    Route::post('notification/read','API\NotificationsController@read');
    

    Route::apiResources(['payment'=>'API\PaymentController']);

    Route::post('backup','API\SettingController@backup');
    Route::get('setting','API\SettingController@index');
    Route::put('setting', 'API\SettingController@update');

    Route::get('order/edit/{id}','API\OrderController@edit');
    Route::get('order/bill-no','API\OrderController@billNo');
    Route::apiResources(['order'=>'API\OrderController']);
    Route::get('order/approved/{state}/{id}','API\OrderController@approved');





});
