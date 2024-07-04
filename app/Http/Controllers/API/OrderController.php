<?php

namespace App\Http\Controllers\API;

use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isOrder');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('orders')
            ->join('suppliers','orders.supplier_id','suppliers.id')
            ->leftjoin('users','orders.user_id','users.id')
            ->select([
                'suppliers.name as supplier',
                'orders.*',
                'users.name as user'
            ])
            ->whereNull('orders.deleted_at')
            ->where(function($query) use ($search){
                $query->where('orders.BillNo','LIKE',"$search%")
                        ->orWhere('suppliers.name','LIKE',"$search%");
            })    
            ->paginate(20);
        }else
        {
            return DB::table('orders')
            ->join('suppliers','orders.supplier_id','suppliers.id')
            ->leftjoin('users','orders.user_id','users.id')
            ->select([
                'suppliers.name as supplier',
                'orders.*',
                'users.name as user'
            ])
            ->whereNull('orders.deleted_at')
                ->orderBy('orders.id','DESC')
                ->paginate(20);
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
        $this->authorize('isOrder');
        $this->authorize('Create');
        $this->validate($request,[
            'BillNo'=>'required',
            'supplier'=>'required',
            'date'=>'required'
        ]);

         for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
        }

        $user = auth('api')->user();
        $approve = 0;

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

        $request->merge(['BillNo'=>$code_value]);
        $order = Order::create([
            'BillNo'=>$request->BillNo,
            'supplier_id' => $request->supplier,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => $user->id,
            'approve' => $approve
        ]);

        for($i=0;$i<count($request->medicineId);$i++){
            OrderDetail::create(['order_id'=>$order->id,'medicine_id'=>$request->medicineId[$i],'quantity'=>$request->Quantity[$i],'approve'=>$approve]);
       }

       return response()->json(['success'=>'Order add Successfully','id'=>$order->id]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isOrder');
        $this->authorize('Read');
        $order = DB::table('orders')
        ->join('suppliers','orders.supplier_id','suppliers.id')
        ->select([
            'orders.*',
            'suppliers.name as supplier',
        ])
        ->where('orders.id',$id)
        ->first();

        $order_details = DB::table('order_details')
        ->join('medicines','order_details.medicine_id','medicines.id')
        ->leftjoin('groups','medicines.group_id','groups.id')
        ->select([
            'order_details.*',
            'medicines.name as medicines',
            'groups.name as group'
        ])
        ->where('order_details.order_id',$id)
        ->get();

        return response()->json(['order'=>$order,'order_details'=>$order_details]);
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
        $this->authorize('isOrder');
        $this->authorize('Update');
        $this->validate($request,[
            'BillNo'=>'required',
            'supplier'=>'required',
            'date'=>'required',
        ]);

        for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
        }

        $order = Order::findOrFail($id);
        
        $user = auth('api')->user();
        $approve = 0;
        $order->BillNo=$request->BillNo;
        $order->supplier_id = $request->supplier;
        $order->date = $request->date;
        $order->description = $request->description;
        $order->user_id = $user->id;
        $order->approve = $approve;
        $order->save();

        DB::table('order_details')->where('order_id',$id)->delete();

        for($i=0;$i<count($request->medicineId);$i++){
            OrderDetail::create(['order_id'=>$order->id,'medicine_id'=>$request->medicineId[$i],'quantity'=>$request->Quantity[$i],'approve'=>$approve]);
       }

       return response()->json(['success'=>'Order update Successfully','id'=>$order->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isOrder');
        $this->authorize('Delete');
        $order = Order::findOrFail($id)->delete();
        OrderDetail::where('order_id',$id)->delete();
        return 'success';
    }

    public function approved($state,$id)
    {
        $this->authorize('isOrder');
        $approved = Order::findOrFail($id);
        if($state==1)
        {
            $approved->approve = 1;
            $approved->save();

            DB::table('order_details')->where('order_id',$id)->update(['approve'=>1]);

        }else
        {
            $approved->approve = 2;
            $approved->save();
            DB::table('order_details')->where('order_id',$id)->update(['approve'=>2]);
        }
        return $approved->approve;

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

    public function edit($id)
    {
        $this->authorize('isOrder');
        $this->authorize('Update');
        $order = DB::table('orders')
        ->join('suppliers','orders.supplier_id','suppliers.id')
        ->select([
            'orders.*',
            'suppliers.name as supplier',
        ])
        ->where('orders.id',$id)
        ->first();

        $order_details = DB::table('order_details')
        ->join('medicines','order_details.medicine_id','medicines.id')
        ->leftjoin('groups','medicines.group_id','groups.id')
        ->select([
            'order_details.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode',
            'groups.name as group'
        ])
        ->where('order_details.order_id',$id)
        ->get();

        return response()->json(['order'=>$order,'order_details'=>$order_details]);
    }
}
