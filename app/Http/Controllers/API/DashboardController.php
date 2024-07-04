<?php

namespace App\Http\Controllers\API;

use App\Stock;
use App\Expense;
use App\Medicine;
use App\Purchase;
use Carbon\Carbon;
use App\Counter_sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('isDashboard');
        $this->authorize('Read');
        $date = date("Y-m-d", strtotime("+3 months"));
        $sale = Counter_sale::whereNull('deleted_at')->where('date', Date('Y-m-d'))->sum('paid');

        
        $stock = DB::table('medicines')->join('stocks','medicines.id','stocks.medicine_id')->select([
            'medicines.*',
            DB::raw("SUM(stocks.quantity) as quantity")
            ])
            
            ->groupBy('medicines.barcode')
            ->get()->where('quantity','<',5)->count();
        $expiry = Stock::where('expiry_date','<=',$date)->where('stocks.quantity','>',0)->count();
        $sales = Counter_sale::select('id', 'date','paid')
        ->where('date','>=',date("Y"))
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->date)->format('m'); 
        });
        
        $salesmcount = [];
        $salesArr = [];
        $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        foreach ($sales as $key => $value) {
            $salesmcount[(int)$key] = count($value);
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($salesmcount[$i])){
                $salesArr[$months[$i]] = $salesmcount[$i];    
            }else{
                $salesArr[$months[$i]] = 0;    
            }
        }
        $purchases = Purchase::select('id', 'date')
        ->where('date','>=',date("Y"))
        ->get()
        ->groupBy(function($date) {
            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
            return Carbon::parse($date->date)->format('m'); // grouping by months
        });
        
        $purchaseMCount = [];
        $purchaseArr = [];
        $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        foreach ($purchases as $key => $value) {
            $purchaseMCount[(int)$key] = count($value);
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($purchaseMCount[$i])){
                $purchaseArr[$months[$i]] = $purchaseMCount[$i];    
            }else{
                $purchaseArr[$months[$i]] = 0;    
            }
        }
        $expenses = Expense::whereNull('deleted_at')->where('date', Date('Y-m-d'))->sum('cash');
        return response()->json(['sale'=>$sale,'stock'=>$stock,'expiry'=>$expiry,'expenses'=>$expenses,'sales'=>$salesArr,'purchases'=>$purchaseArr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
