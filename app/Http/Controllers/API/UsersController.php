<?php

namespace App\Http\Controllers\API;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
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
        $this->authorize('isUser');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return User::where(function($query) use ($search){
                $query->where('users.name','LIKE',"$search%")
                        ->orWhere('users.email','LIKE',"$search%");
            })->
            latest()->paginate(20);
        }else
        {

            return User::latest()->paginate(20);
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
        $this->authorize('isUser');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'Required',
            'email'=>'required|email|unique:users',
            'password'=>'min:6',
            'confirm_password'=>'min:6|required_with:password|same:password',
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'photo'=>$request->photo
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
        $this->authorize('isUser');
        $this->authorize('Update');
        $user = User::findOrFail($id);
        $this->validate($request,[
            'name'=>'Required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'min:6',
            'confirm_password'=>'min:6|required_with:password|same:password',
        ]);
        if(!empty($request->password)){
            $request->merge(['password'=>bcrypt($request->password)]);
        }else{
            $request->merge(['password' => $user->password]);
        }
        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ]);
        $user->save();
        return ['message'=>'Data update successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isUser');
        $this->authorize('Delete');
        $user = User::findOrFail($id);

        $user->delete();

        return ['message'=>'user Deleted'];
    }


    public function roles()
    {
        $this->authorize('isUser');
        return Role::all();
    }

    public function role($id)
    {
        $this->authorize('isUser');
        $user = User::findOrFail($id);
        $array=[];
       foreach( $user->roles as $role)
       {
            $array[] = $role->id;
       }
       return $array;
    }


    public function assignRole(Request $request,$id)
    {
        $this->authorize('isUser');
        $user = User::findOrFail($id);
        $user->roles()->sync($request->roles);
        $user->permissions()->sync($request->permissions);
    }

    public function permissions()
    {
        $this->authorize('isUser');
        return Permission::all();
    }

    public function userPermissions($id)
    {
       
        $this->authorize('isUser');
        $user = User::findOrFail($id);
        $array=[];
       foreach( $user->permissions as $permission)
       {
            $array[] = $permission->id;
       }
       return $array;
    }

    


}
