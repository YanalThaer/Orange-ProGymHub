<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $sessionCreatedAt = $request->session()->get('created_at');
            
            if (!$sessionCreatedAt) {
                $request->session()->put('created_at', time());
            } else {
                $isRemembered = $request->session()->get('remember_me', false);
                
                if ($isRemembered) {
                    $dayInSeconds = 86400; // 24 hours in seconds
                    $timeElapsed = time() - $sessionCreatedAt;
                    
                    if ($timeElapsed > $dayInSeconds) {
                        if (Auth::guard('admin')->check()) {
                            Auth::guard('admin')->logout();
                        } elseif (Auth::guard('club')->check()) {
                            Auth::guard('club')->logout();
                        } elseif (Auth::guard('coach')->check()) {
                            Auth::guard('coach')->logout();
                        } elseif (Auth::guard('web')->check()) {
                            Auth::guard('web')->logout();
                        }
                        
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        
                        return redirect('/login')->with('error', 'Your session has expired. Please login again.');
                    }
                }
            }
        }

        return $next($request);
    }
}