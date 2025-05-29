<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CoachMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('coach')->check()) {
            $coach = Auth::guard('coach')->user();

            if ($coach->verification_code == null || empty($coach->verification_code)) {
                return redirect()->route('coach.verify.email.form', $coach->encoded_id)
                    ->with('error', 'You must verify your email address to access this page.');
            }


            return $next($request);
        }

        return redirect('/dashboard/admin')->with('error', 'لا يمكنك الوصول لهذه الصفحة');
    }
}
