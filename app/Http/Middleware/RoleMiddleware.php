<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();
 
        if (!$user) {
            return redirect()->route('login');
        }
 
        if (!in_array($user->role, $roles)) {
            // Students trying to access supervisor/coordinator routes
            if ($user->isStudent()) {
                abort(403, 'Access reserved to coordinate and supervisors.');
            }
 
            // Supervisors/coordinators trying to access student-only routes
            if (in_array('student', $roles)) {
                abort(403, 'Access reserved to students.');
            }
 
            abort(403, 'Acess denied.');
        }
 
        return $next($request);
    }
}