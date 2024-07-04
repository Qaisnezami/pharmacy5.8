<?php

namespace App\Http\Controllers\API;

use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DoctorsController extends Controller
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
        $this->authorize('isDoctor');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('doctors')
                ->join('users','doctors.user_id','users.id')
                ->select([
                    'doctors.*',
                    'users.name as username'
                ])
                ->where(function($query) use ($search){
                    $query->where('doctors.code','LIKE',"$search%")
                            ->orWhere('doctors.name','LIKE',"$search%");
                })
                ->whereNull('doctors.deleted_at')
                ->latest()->paginate(20);
        }else{
            return DB::table('doctors')
                ->join('users','doctors.user_id','users.id')
                ->select([
                    'doctors.*',
                    'users.name as username'
                ])
                ->orderBy('id','DESC')
                ->whereNull('doctors.deleted_at')
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
        $this->authorize('Create');
        $this->authorize('isDoctor');
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'specialist'=>'required',
            'gender'=>'required',
        ]);

        $codeArray=Doctor::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="D01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'D0'.(string)($last_digit+1);
         }
        $user = auth('api')->user()->id;
        return Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialist' => $request->specialist,
            'gender' => $request->gender,
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
        $this->authorize('isCounterSales');

        return response()->json(['data'=>Doctor::whereNull('deleted_at')->select(['name','id as code'])->get()]);
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
        $this->authorize('isDoctor');
        $this->authorize('Update');
        $doctor = Doctor::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'specialist'=>'required',
            'gender'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->phone = $request->phone;
        $doctor->specialist = $request->specialist;
        $doctor->gender=$request->gender;
        $doctor->user_id = $user;
        $doctor->save();

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
        $this->authorize('isDoctor');
        $this->authorize('Delete');
       Doctor::findOrFail($id)->delete();
        return 'success';
    }
}
