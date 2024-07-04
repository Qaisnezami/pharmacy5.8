<?php

namespace App\Http\Controllers\API;

use App\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClassificationsController extends Controller
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
        $this->authorize('isClassification');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('classifications')
                ->join('users','classifications.user_id','users.id')
                ->select([
                    'classifications.*',
                    'users.name as username'
                ])
                ->where(function($query) use ($search){
                    $query->where('classifications.code','LIKE',"$search%")
                            ->orWhere('classifications.name','LIKE',"$search%");
                })
                ->whereNull('classifications.deleted_at')
                ->latest()->paginate(20);
        }else{
            return DB::table('classifications')
                ->join('users','classifications.user_id','users.id')
                ->select([
                    'classifications.*',
                    'users.name as username'
                ])
                ->orderBy('id','DESC')
                ->whereNull('classifications.deleted_at')
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
        $this->authorize('isClassification');
        $this->validate($request,[
            'name'=>'required'
        ]);

        $codeArray=Classification::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="CF01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'CF0'.(string)($last_digit+1);
         }
        $user = auth('api')->user()->id;
        return Classification::create([
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
        $this->authorize('isClassification');
        $this->authorize('Update');
        $classification = Classification::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $classification->name = $request->name;
        $classification->user_id = $user;
        $classification->save();

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
        $this->authorize('isClassification');
        $this->authorize('Delete');
        Classification::findOrFail($id)->delete();
        return 'success';
    }
}
