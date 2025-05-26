<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileCompletionCheck
{
    /**
     * Routes that should be excluded from the middleware.
     *
     * @var array
     */    protected $except = [
        'profile.complete.show', 
        'profile.complete',
        'profile.skip',
        'logoutusers',
        'profile.show',
        'profile.edit',
        'profile.update'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            foreach ($this->except as $route) {
                if ($request->routeIs($route)) {
                    return $next($request);
                }
            }
            
            if ((empty($user->goal) || empty($user->fitness_level)) && !session('skip_profile_completion')) {
                if (!session('profile_completion_visited')) {
                    return redirect()->route('profile.complete.show')
                        ->with('status', 'Please complete your profile before continuing.');
                }
            }
        }

        return $next($request);
    }
}
