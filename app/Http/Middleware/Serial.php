<?php

namespace App\Http\Middleware;
use DB;
use Closure;

class Serial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
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
                return $next($request);
            } else {
                return redirect('serial');
            }
        
    }
}
