<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ClubMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('club')->check()) {
            $club = Auth::guard('club')->user();

            if (!$club->email_verified) {
                return redirect()->route('club.verify.email.form', $club->encoded_id)
                    ->with('error', 'You must verify your email address to access this page.');
            }

            return $next($request);
        }

        return redirect('/dashboard/admin')->with('error', 'لا يمكنك الوصول لهذه الصفحة');
    }
}
