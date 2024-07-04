<?php

namespace App\Http\Controllers\API;

use App\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManufacturersController extends Controller
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
        $this->authorize('isManufacturer');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('manufacturers')
                ->join('users','manufacturers.user_id','users.id')
                ->select([
                    'manufacturers.*',
                    'users.name as username'
                ])
                ->where(function($query) use ($search){
                    $query->where('manufacturers.code','LIKE',"$search%")
                            ->orWhere('manufacturers.name','LIKE',"$search%");
                })
                ->whereNull('manufacturers.deleted_at')
                ->latest()->paginate(20);
        }else{
            return DB::table('manufacturers')
                ->join('users','manufacturers.user_id','users.id')
                ->select([
                    'manufacturers.*',
                    'users.name as username'
                ])
                ->orderBy('id','DESC')
                ->whereNull('manufacturers.deleted_at')
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
        $this->authorize('isManufacturer');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'required'
        ]);

        $codeArray=Manufacturer::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="MF01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'MF0'.(string)($last_digit+1);
         }
        $user = auth('api')->user()->id;
        return Manufacturer::create([
            'name' => $request->name,
            'user_id' => $user,
            'code' => $code_value,
        ]);
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
        $this->authorize('isManufacturer');
        $this->authorize('Update');
        $Manufacturer = Manufacturer::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $Manufacturer->name = $request->name;
        $Manufacturer->user_id = $user;
        $Manufacturer->save();

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isManufacturer');
        $this->authorize('Delete');
        Manufacturer::findOrFail($id)->delete();
        return 'success';
    }
}
