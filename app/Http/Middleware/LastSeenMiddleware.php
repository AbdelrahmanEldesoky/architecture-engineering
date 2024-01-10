<?php

namespace App\Http\Middleware;

use App\Models\Employee\EmployeeTrack;
use App\Models\User;
use App\Models\ActivityLog;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
class LastSeenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::with('employee.employmentData')->active()->whereId(auth('api')->id())->first();
        $user_id = $user->id;
        $employee = EmployeeTrack::where('employee_id',$user->employee->id)->first();
        if(isset($employee)){
        $employee->update(['last_seen' => Carbon::now()]);
        }else{
          EmployeeTrack::create(['last_seen' => Carbon::now(),'employee_id'=>$user->employee->id]);
        }
        $route = $request->route();
        $route_name = $route->getName();
        $route_url = $request->url();
        $route_method = $route->methods[0];
        $route_use = $route->action['uses'];
        ActivityLog::create([
        'user_id' =>$user_id,
        'activity_loggable_id' =>NULL,
        'activity_loggable_type'=>$route_use,
        'type'  => 'api',
        'route'   => $route_url,
        'status' =>  $route_name,
        'action' => $route_method
        ]);

        return $next($request);
    }
}
