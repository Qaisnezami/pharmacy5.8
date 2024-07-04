<?php

namespace App\Http\Controllers\API;

use App\Order;
use App\Stock;
use App\Medicine;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StocksController extends Controller
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
        
    }

    public function low()
    {
        $this->authorize('isLowStock');
        $this->authorize('Read');
        $search = \Request::get('search');
      
        return DB::table('medicines')
        ->join('stocks','medicines.id','stocks.medicine_id')
        ->leftjoin('classifications','medicines.classification_id','classifications.id')
                ->leftjoin('manufacturers','medicines.manufacturer_id','manufacturers.id')
                ->leftjoin('doctors','medicines.doctor_id','doctors.id')
                ->leftjoin('groups','medicines.group_id','groups.id')
                ->select([
                    'medicines.*',
                    'classifications.id as classification',
                    'classifications.name as classificationName',
                    'manufacturers.id as manufacturer',
                    'manufacturers.name as manufacturerName',
                    'doctors.id as doctor',
                    'doctors.name as doctorName',
                    'groups.id as group',
                    'groups.name as groupName',
                    DB::raw("SUM(stocks.quantity) as quantity")
                ])
       
        ->where(function($query) use ($search){
            if($search = \Request::get('search'))
            {
                $query->where('medicines.barcode','LIKE',"$search%")
                    ->orWhere('medicines.name','LIKE',"$search%");
            }
            
        })
        
        ->groupBy('medicines.barcode')
        ->paginate(20);
        
        
        
    
    }

    public function lowDetails($id)
    {
        $this->authorize('isLowStock');
        $this->authorize('Read');
        return DB::table('stocks')
        ->join('medicines','stocks.medicine_id','medicines.id')
        ->select([
            'stocks.*',
            'medicines.name',
            'medicines.barcode',
        ])
        ->where('medicines.id',$id)
        ->orderBy('stocks.quantity','desc')
        ->paginate(20);
    }

    public function expiry()
    {
        $this->authorize('isExpiry');
        $date = date("Y-m-d", strtotime("+3 months"));
        $search = \Request::get('search');

        return DB::table('stocks')
        ->join('medicines','stocks.medicine_id','medicines.id')
        ->select([
            'stocks.*',
            'medicines.name',
            'medicines.barcode',
        ])
        ->where('stocks.quantity','>',0)
        ->where('stocks.expiry_date','<=', $date)
        ->where(function($query) use ($search){
            if($search = \Request::get('search'))
            {
                $query->where('medicines.barcode','LIKE',"$search%")
                    ->orWhere('medicines.name','LIKE',"$search%");
            } 
        })
        ->paginate(20);
        
        
    }

    public function report(Request $request)
    {
        $this->authorize('isInventoryReport');
        date_default_timezone_set('Asia/Kabul');
        $this->validate($request,[
            'from'=>'required'
        ]);

        $array = [];
        if($request->from)
        {
            $from = $request->from." 00:00:00";
            $to = date('yy-m-d H:i:s');
        }
        if($request->to)
        {
            $to = $request->to." 00:00:00";
        }
        if($request->medicine)
        {
            $array['medicines.id'] = $request->medicine;
        }
        $data =  DB::table('stocks')
        ->join('medicines','stocks.medicine_id','medicines.id')
        ->leftjoin('groups','medicines.group_id','groups.id')
        ->select([
            'stocks.*',
            'medicines.name',
            'medicines.barcode',
            'groups.name as groupName',
        ])
        ->where($array)
        ->whereBetween('stocks.created_at', [$from, $to])
        ->paginate(100);

        return response()->json($data);
        
    }

    public function medicine()
    {
        $this->authorize('isInventoryReport');
        return response()->json(['data'=>Medicine::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }


    public function orders()
    {
        return DB::table('orders')
            ->join('suppliers','orders.supplier_id','suppliers.id')
            ->select([
                'suppliers.name as supplier',
                'orders.BillNo as BillNo',
                'orders.id as id',
            ])
            ->whereNull('orders.deleted_at')
            ->where('approve',0)
            ->get();
    }


    public function AddExcite(Request $request)
    {
        $this->authorize('isOrder');
        if($request->Mode)
        {
            $this->validate($request,[
                'id'=>'required',
                'quantity'=>'required',
                'supplier'=>'required'
            ]);

            $user = auth('api')->user();
        $approve = 0;

        $order = Order::create([
            'BillNo'=> $this->billNo(),
            'supplier_id' => $request->supplier,
            'date' => date('Y-m-d'),
            'user_id' => $user->id,
            'is_stock'=>1,
            'approve' => $approve
        ]);

        
        OrderDetail::create(['order_id'=>$order->id,'medicine_id'=>$request->id,'quantity'=>$request->quantity,'approve'=>$approve]);
       
            
        }else
        {
            $this->validate($request,[
                'id'=>'required',
                'quantity'=>'required',
                'billNo'=>'required',
            ]);
    
            OrderDetail::create(['order_id'=>$request->billNo,'medicine_id'=>$request->id,'quantity'=>$request->quantity,'approve'=>0]);
    
            return response()->json(['success'=>'Order add Successfully']);
        }
        


    }


    public function billNo()
    {
        $this->authorize('isOrder');
        $codeArray=Order::select('BillNo')->orderBy('id','desc')->limit(1)->get();

        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="O01";
         }else{
             $code= $codeArray[0]->BillNo;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'O0'.(string)($last_digit+1);
         }

         return $code_value;
    }
}
