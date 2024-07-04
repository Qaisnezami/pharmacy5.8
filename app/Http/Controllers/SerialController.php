<?php

namespace App\Http\Controllers;
use App\Serial;
use Illuminate\Http\Request;
use DB;

class SerialController extends Controller
{
    public function index()
    {
        function GetVolumeLabel($drive) {
            if (preg_match('#Volume Serial Number is (.*)\n#i', shell_exec('dir '.$drive.':'), $m)) {
            $volname = ' ('.$m[1].')';
            } else {
            $volname = '';
            }
            return $volname;
            }
            $serial = trim(str_replace("(","",str_replace(")","",GetVolumeLabel("c"))));
            
            $Serial = DB::table('serials')->first();
            
            if (md5($serial) == $Serial->serial) {
                return redirect('/dashboard');
            } else {
                return view('serial');
            }
        
    }
    
    public function store(Request $request)
    {
        $serial = Serial::first();
        $serial->serial = md5($request->serial);
        $serial->save();

        return redirect('/dashboard');
    }


}
