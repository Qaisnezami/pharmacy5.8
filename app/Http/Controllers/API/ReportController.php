<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Stock;
use App\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
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

    //  Counter sale Report
    public function index(Request $request)
    {
        $this->authorize('isCounterSalesReport');
        
        $this->validate($request,[
            'from'=>'required'
        ]);
        $array = [];
        if($request->user)
        {
            $array['users.id']=$request->user;
        }
        if($request->doctor)
        {
            $array['doctors.id']=$request->doctor;
        }
        if($request->from)
        {
            $from = $request->from;
            $to = date('yy-m-d');
        }
        if($request->to)
        {
            $to = $request->to;
        }
       
        if($request->type =='return'){
            
            $data =  DB::table('counter_sale_returns')
                ->join('counter_sale_details','counter_sale_returns.counter_sale_details_id','counter_sale_details.id')
                ->join('counter_sales','counter_sale_details.counter_sale_id','counter_sales.id')
                ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
                ->leftjoin('users','counter_sales.user_id','users.id')
                ->select([
                    'counter_sales.*',
                    'doctors.name as doctor',
                    'users.name as user'
                ])
                ->whereNull('counter_sales.deleted_at')
                ->where($array)
                ->whereBetween('counter_sale_returns.date', [$from, $to])
                ->orderBy('id','ASC')
                ->groupBy('counter_sales.id')
                ->paginate(100);
            return response()->json($data);
        
        }else
        {
            $data =  DB::table('counter_sales')
                ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
                ->leftjoin('users','counter_sales.user_id','users.id')
                ->select([
                    'counter_sales.*',
                    'doctors.name as doctor',
                    'users.name as user'
                ])
                ->whereNull('counter_sales.deleted_at')
                ->where($array)
                ->whereBetween('counter_sales.date', [$from, $to])
                ->orderBy('id','ASC')
                ->paginate(100);
            return response()->json($data);
        }
       
    }

    // Counter Sale 
    public function medicine()
    {
        return response()->json(['data'=>Medicine::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }
    // Counter Sale Users
    public function users()
    {
        return response()->json(['data'=>User::whereNull('deleted_at')->select(['name','id as code'])->get()]);
    }

    // Purchase Report
    public function purchases(Request $request)
    {
        $this->authorize('isPurchaseReport');
        $this->validate($request,[
            'from'=>'required'
        ]);
        $array = [];
        if($request->user)
        {
            $array['users.id']=$request->user;
        }
        if($request->supplier)
        {
            $array['suppliers.id']=$request->supplier;
        }
        if($request->from)
        {
            $from = $request->from;
            $to = date('yy-m-d');
        }
        if($request->to)
        {
            $to = $request->to;
        }

       $data = DB::table('purchases')
                ->leftjoin('suppliers','purchases.supplier_id','suppliers.id')
                ->leftjoin('users','purchases.user_id','users.id')
                ->select([
                    'purchases.*',
                    'suppliers.name as supplier',
                    'users.name as user'
                ])
                ->whereNull('purchases.deleted_at')
                ->where($array)
                ->whereBetween('purchases.date', [$from, $to])
                ->orderBy('id','ASC')
                ->groupBy('purchases.id')
                ->paginate(100);

                return response()->json($data);
    }
    public function order(Request $request)
    {
        $this->authorize('isOrder');
        $this->validate($request,[
            'from'=>'required'
        ]);
        $array = [];
        if($request->user)
        {
            $array['users.id']=$request->user;
        }
        if($request->supplier)
        {
            $array['suppliers.id']=$request->supplier;
        }
        if($request->from)
        {
            $from = $request->from;
            $to = date('yy-m-d');
        }
        if($request->to)
        {
            $to = $request->to;
        }

       $data = DB::table('orders')
                ->leftjoin('suppliers','orders.supplier_id','suppliers.id')
                ->leftjoin('users','orders.user_id','users.id')
                ->select([
                    'orders.*',
                    'suppliers.name as supplier',
                    'users.name as user'
                ])
                ->whereNull('orders.deleted_at')
                ->where($array)
                ->whereBetween('orders.date', [$from, $to])
                ->orderBy('id','ASC')
                ->groupBy('orders.id')
                ->paginate(100);

                return response()->json($data);
    }

    public function expenses(Request $request)
    {
        $this->validate($request,[
            'from'=>'required'
        ]);
        if($request->from)
        {
            $from = $request->from;
            $to = date('yy-m-d');
        }
        if($request->to)
        {
            $to = $request->to;
        }

        $sales = DB::table('counter_sales')->whereNull('deleted_at')->whereBetween('counter_sales.date', [$from, $to])->sum('paid');
        $salesR = DB::table('counter_sales')->whereNull('deleted_at')->whereBetween('counter_sales.date', [$from, $to])->sum('remaind');
        $purchases =  DB::table('purchases')->whereNull('deleted_at')->whereBetween('purchases.date', [$from, $to])->sum('paid');
        $purchasesR =  DB::table('purchases')->whereNull('deleted_at')->whereBetween('purchases.date', [$from, $to])->sum('remind');
        $expenses =  DB::table('expenses')->whereNull('deleted_at')->whereBetween('expenses.date', [$from, $to])->sum('cash');

        $data = new \stdClass();

        $data->sales = $sales; 
        $data->salesR = $salesR; 
        $data->purchases = $purchases; 
        $data->purchasesR = $purchasesR; 
        $data->expenses = $expenses; 
        $data->income = ($sales+$salesR) - ($purchases+$purchasesR) - $expenses; 
        return response()->json($data);
    }


    
}
