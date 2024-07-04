<?php

namespace App\Http\Controllers\API;

use App\Stock;
use App\Medicine;
use App\Counter_sale;
use App\Purchase_detail;
use App\CounterSaleReturn;
use App\Counter_sale_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CounterSaleController extends Controller
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
        $this->authorize('isCounterSales');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('counter_sales')
                ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
                ->leftjoin('users','counter_sales.user_id','users.id')
                ->select([
                    'counter_sales.*',
                    'doctors.name as doctor',
                    'users.name as user'
                ])
                ->whereNull('counter_sales.deleted_at')
                ->where(function($query) use ($search){
                    $query->where('counter_sales.BillNo','LIKE',"$search%")
                            ->orWhere('counter_sales.patient_name','LIKE',"$search%");
                })
                ->latest()->paginate(20);
        }else{
            return DB::table('counter_sales')
                ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
                ->leftjoin('users','counter_sales.user_id','users.id')
                ->select([
                    'counter_sales.*',
                    'doctors.name as doctor',
                    'users.name as user'
                ])
                ->whereNull('counter_sales.deleted_at')
                ->orderBy('id','DESC')
                ->latest()->paginate(20);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Create');
        $this->validate($request,[
            'BillNo'=>'required',
            'total'=>'required',
            'Paid'=>'required',
            'date'=>'required',
        ]);

        for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if(!$request->stock_id[$i]){

                return response()->json(['error'=>'Please Enter Batch No ']);
               
            }
            else if(!$request->Expiry[$i]){
              
                return response()->json(['error'=>'Please Enter Expiry Date ']);
                   
             
            }
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
            else if(!$request->Mrp[$i]){
                
                return response()->json(['error'=>'Please Enter Mrp ']);
                  
            }
            else if(!$request->Amount[$i]){
              
                return response()->json(['error'=>'Please Enter Amount ']);
                 
            }

            $stock =  Stock::find($request->stock_id[$i]);
            if($request->Quantity[$i] >  $stock->quantity)
                {
                    return response()->json(['error'=> ($i+1).'You Enter Value  More then Stock']);
                }
        }

        $user = auth('api')->user();
        $approve = 0;

        $this->authorize('isCounterSales');
        $codeArray=Counter_sale::select('BillNo')->orderBy('id','desc')->limit(1)->get();

        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="S01";
         }else{
             $code= $codeArray[0]->BillNo;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'S0'.(string)($last_digit+1);
         }

         $request->merge(['BillNo'=>$code_value]);

       $Counter_sale = Counter_sale::create([
           'BillNo'=>$request->BillNo,
           'patient_name' => $request->patient_name,
           'doctor_id' => $request->doctor,
           'date' => $request->date,
           'description' => $request->description,
           'user_id' => $user->id,
           'total' => $request->total,
           'remaind' => $request->Remaind,
           'discount' => $request->discount,
           'paid' => $request->Paid,
       ]);

       for($i=0;$i<count($request->medicineId);$i++){

        $stock =  Stock::find($request->stock_id[$i]);
        if($request->Quantity[$i] >  $stock->quantity)
            {
                return response()->json(['error'=>'You Enter Value  More then Stock']);
            }
            Counter_sale_detail::create(['counter_sale_id'=>$Counter_sale->id,'medicine_id'=>$request->medicineId[$i],'stock_id'=>$request->stock_id[$i],'expiry_date'=>$request->Expiry[$i],'quantity'=>$request->Quantity[$i],'MRP'=>$request->Mrp[$i],'amount'=>$request->Amount[$i],'user_id' => $user->id,'status'=>0]);
            $stock->quantity = ($stock->quantity)-((int)$request->Quantity[$i]);
            $stock->save();
       }

        return response()->json(['success'=>'Data add Successfully','id'=>$Counter_sale->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Read');
        $sale = DB::table('counter_sales')
        ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
        ->select([
            'counter_sales.*',
            'doctors.name as doctor',
        ])
        ->where('counter_sales.id',$id)
        ->first();
        $sale_detail = DB::table('counter_sale_details')
        ->join('medicines','counter_sale_details.medicine_id','medicines.id')
        ->join('stocks','counter_sale_details.stock_id','stocks.id')
        ->select([
            'counter_sale_details.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode',
            'stocks.batch_no as batch_no'
        ])
        ->where('counter_sale_details.counter_sale_id',$id)
        ->get();

        $return = DB::table('counter_sale_returns')
        ->join('counter_sale_details','counter_sale_returns.counter_sale_details_id','counter_sale_details.id')
        ->join('counter_sales','counter_sale_details.counter_sale_id','counter_sales.id')
        ->join('medicines','counter_sale_details.medicine_id','medicines.id')
        ->join('stocks','counter_sale_details.stock_id','stocks.id')
        ->select([
            'counter_sale_returns.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode',
            'stocks.batch_no as batch_no'
        ])
        ->whereNull('counter_sales.deleted_at')
        ->where('counter_sales.id',$id)
        ->get();

        return response()->json(['sale'=>$sale,'sale_detail'=>$sale_detail,'return'=>$return]);
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
        $this->authorize('isCounterSales');
        $this->authorize('Update');
        $this->validate($request,[
            'BillNo'=>'required',
            'date'=>'required',
        ]);

        $counter=Counter_sale::findOrFail($id);


        $counter->BillNo=$request->BillNo;
        $counter->patient_name = $request->patient_name;
        $counter->doctor_id = $request->doctor;
        $counter->date = $request->date;
        $counter->description = $request->description;
        $counter->save();

        return response()->json(['success'=>'Data Update Successfully','id'=>$counter->id]);


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

    public function billNo()
    {
        
        $this->authorize('isCounterSales');
        $codeArray=Counter_sale::select('BillNo')->orderBy('id','desc')->limit(1)->get();

        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="S01";
         }else{
             $code= $codeArray[0]->BillNo;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'S0'.(string)($last_digit+1);
         }

         return $code_value;
    }


    public function loadBatch($id)
    {
        $this->authorize('isCounterSales');
        return Stock::where('medicine_id',$id)->where('quantity','>',0)->orderBy('quantity','asc')->get();
    }

    public function barcodeLoadBatch($id)
    {
        $this->authorize('isCounterSales');
        $medicine = Medicine::where('barcode',$id)->first()->id;
        return Stock::where('medicine_id',$medicine)->select(['*','MRP as mrp'])->where('quantity','!=',0)->get();
    }


    public function edit($id)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Update');
        $counter_sales = DB::table('counter_sales')
        ->leftjoin('doctors','counter_sales.doctor_id','doctors.id')
        ->select([
            'counter_sales.*',
            'doctors.id as doctor',
        ])
        ->where('counter_sales.id',$id)
        ->first();

        $counter_sale_details = DB::table('counter_sale_details')
        ->join('medicines','counter_sale_details.medicine_id','medicines.id')
        ->join('stocks','counter_sale_details.stock_id','stocks.id')
        ->select([
            'counter_sale_details.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode',
            'stocks.batch_no as batch_no'
        ])
        ->where('counter_sale_details.counter_sale_id',$id)
        ->get();

        return response()->json(['counter_sales'=>$counter_sales,'counter_sale_details'=>$counter_sale_details]);
    }

    public function add(Request $request,$id)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Create');
        $this->validate($request,[
            'BillNo'=>'required',
            'total'=>'required',
            'paid'=>'required',
            'date'=>'required',
        ]);

        $counter=Counter_sale::findOrFail($id);
        for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if(!$request->stock_id[$i]){

                return response()->json(['error'=>'Please Enter Batch No ']);
               
            }
            else if(!$request->Expiry[$i]){
              
                return response()->json(['error'=>'Please Enter Expiry Date ']);
                   
             
            }
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
            else if(!$request->Mrp[$i]){
                
                return response()->json(['error'=>'Please Enter Mrp ']);
                  
            }
            else if(!$request->Amount[$i]){
              
                return response()->json(['error'=>'Please Enter Amount ']);
                 
            }
        }


        $user = auth('api')->user();

        for($i=0;$i<count($request->medicineId);$i++){           
            Counter_sale_detail::create(['counter_sale_id'=>$id,'medicine_id'=>$request->medicineId[$i],'stock_id'=>$request->stock_id[$i],'expiry_date'=>$request->Expiry[$i],'quantity'=>$request->Quantity[$i],'MRP'=>$request->Mrp[$i],'amount'=>$request->Amount[$i],'user_id' => $user->id,'status'=>1]);
            $stock =  Stock::find($request->stock_id[$i]);
            $stock->quantity = ($stock->quantity)-((int)$request->Quantity[$i]);
            $stock->save();
       }

       

        $counter->total = $request->total;
        $counter->remaind = $request->remaind;
        $counter->discount  = $request->discount;
        $counter->paid = $request->paid;

        $counter->save();


       return response()->json(['success'=>'New item add Successfully']);

    
        
    }


    public function return(Request $request,$id)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Create');
        $this->validate($request,[
            'total'=>'required',
            'paid'=>'required',
            'date'=>'required',
        ]);

        for($i=0;$i<count($request->medicineId);$i++){
            if($request->medicineId[$i] ==''){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if($request->stock_id[$i] ==''){

                return response()->json(['error'=>'Please Enter Batch No ']);
               
            }
            else if($request->Expiry[$i] ==''){
              
                return response()->json(['error'=>'Please Enter Expiry Date ']);
                   
             
            }
            else if($request->Quantity[$i] ==''){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
            else if($request->Mrp[$i] ==''){
                
                return response()->json(['error'=>'Please Enter Mrp ']);
                  
            }
            else if($request->Amount[$i] ==''){
              
                return response()->json(['error'=>'Please Enter Amount ']);
                 
            }
        }
        $isReturn = false;
        for($i=0;$i<count($request->medicineId);$i++){

           if($request->returnId[$i])
           {
                $sale = Counter_sale_detail::findOrFail($request->returnId[$i]);
                if($request->Quantity[$i] >  $sale->quantity)
                {
                    return response()->json(['error'=>'Please Enter Lower Quantity']);
                }
                $user = auth('api')->user();
                $quantity = $sale->quantity - ($request->Quantity[$i]);
                $stock = Stock::findOrFail($sale->stock_id);
                $returnAmount = ($quantity * $sale->MRP);
                CounterSaleReturn::create(['counter_sale_details_id'=>$request->returnId[$i],'medicine_id'=>$request->medicineId[$i],'stock_id'=>$sale->stock_id,'expiry_date'=>$request->Expiry[$i],'quantity'=>$quantity,'MRP'=>$request->Mrp[$i],'amount'=>$returnAmount,'user_id'=>$user->id,'date'=>Date('Y-m-d')]);
                $stock->quantity = $stock->quantity + $quantity;
                $stock->save();
                $sale->quantity = $request->Quantity[$i];
                $sale->amount = $request->Amount[$i];
                $sale->save();
                $isReturn = true;
           }
       }

       if($isReturn)
       {
        $counter=Counter_sale::findOrFail($id);
        $counter->total = $request->total;
        $counter->remaind = $request->remaind;
        $counter->discount  = $request->discount;
        $counter->paid = $request->paid;

        $counter->save();
        return response()->json(['success'=>'item return Successfully']);
       }else
       {
        return response()->json(['error'=>'Please check item you want to return']);
       }
    }

    public function cash(Request $request)
    {
        $this->authorize('isCounterSales');
        $this->authorize('Create');
        $counter = Counter_sale::findOrFail($request->id);

        if($request->cash > $counter->remaind)
        {
            return response()->json(['error'=>'Please enter cash less then remaind']);
        }

        $counter->remaind =  $counter->remaind - $request->cash;
        $counter->paid = $counter->paid + $request->cash;
        $counter->save();

        return response()->json(['success'=>'Cash add Successfully']);
    }

    public function findBatch($id)
    {
        $this->authorize('isCounterSales');
        return Stock::find($id);
    }


    public function counterSaleSearch($id)
    {
        $med  =  \Request::get('medicineView');
        $medicines = Medicine::leftjoin('groups','medicines.group_id','groups.id')
        ->join('stocks','stocks.medicine_id','medicines.id')
        ->select([
            'medicines.*',
            'groups.name as groups'
        ])
        ->where(function($query) use ($med){
            if(!$med)
            {
                $query->where('isactive','!=',0)
                ->where('approve','!=',0)
                ->where('approve','!=',2);
            } 
        })
        
        ->where('medicines.name','LIKE',"$id%")
        ->groupBy('medicines.id')
        ->take(20)->get();
        return $medicines;
    }
}
