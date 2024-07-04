<?php

namespace App\Http\Controllers\API;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
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
        $this->authorize('Read');
        $this->authorize('isMedicineGroup');
        
        if ($search = \Request::get('search')) {
            return DB::table('groups')
                ->join('users','groups.user_id','users.id')
                ->select([
                    'groups.*',
                    'users.name as username'
                ])
                ->where(function($query) use ($search){
                    $query->where('groups.code','LIKE',"$search%")
                            ->orWhere('groups.name','LIKE',"$search%");
                })
                ->whereNull('groups.deleted_at')
                ->latest()->paginate(20);
        }
        else{
            return DB::table('groups')
                ->join('users','groups.user_id','users.id')
                ->select([
                    'groups.*',
                    'users.name as username'
                ])
                ->orderBy('id','DESC')
                ->whereNull('groups.deleted_at')
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
        $this->authorize('isMedicineGroup');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'required'
        ]);

        $codeArray=Group::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="MG01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'MG0'.(string)($last_digit+1);
         }
        $user = auth('api')->user()->id;
        return Group::create([
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
        $this->authorize('isMedicineGroup');
        $this->authorize('Update');
        $Group = Group::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $Group->name = $request->name;
        $Group->user_id = $user;
        $Group->save();

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
        $this->authorize('isMedicineGroup');
        $this->authorize('Delete');
        Group::findOrFail($id)->delete();
        return 'success';
    }
}
