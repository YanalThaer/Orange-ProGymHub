<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

use App\Models\Admin;
use App\Models\Club;
use App\Models\Coach;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Redirect users to the correct dashboard based on their role.
     */

    public function showLoginForm(Request $request)
    {
        $redirectParams = [];
        
        if ($request->has('redirect_after_login')) {
            $redirectParams['redirect_after_login'] = $request->input('redirect_after_login');
        }
        
        if ($request->has('plan_id')) {
            $redirectParams['plan_id'] = $request->input('plan_id');
        }
        
        if ($request->has('club_id')) {
            $redirectParams['club_id'] = $request->input('club_id');
        }
        
        return view('auth.login', compact('redirectParams'));
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');
        
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin, $remember);
            
            if ($remember) {
                $request->session()->put('created_at', time());
                $request->session()->put('remember_me', true);
            }
            
            $admin->last_login_at = now();
            $admin->save();
            
            return redirect()->route('admin.dashboard');
        }

        $club = Club::where('email', $request->email)->first();
        if ($club && Hash::check($request->password, $club->password)) {
            Auth::guard('club')->login($club, $remember);
            
            if ($remember) {
                $request->session()->put('created_at', time());
                $request->session()->put('remember_me', true);
            }
            
            return redirect()->route('club.dashboard');
        }

        $coach = Coach::where('email', $request->email)->first();
        if ($coach && Hash::check($request->password, $coach->password)) {
            Auth::guard('coach')->login($coach, $remember);
            
            if ($remember) {
                $request->session()->put('created_at', time());
                $request->session()->put('remember_me', true);
            }
            
            return redirect()->route('coach.dashboard');
        }

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user, $remember);
            
            if ($remember) {
                $request->session()->put('created_at', time());
                $request->session()->put('remember_me', true);
            }
            
            if ($request->has('redirect_after_login') && $request->has('plan_id') && $request->has('club_id')) {
                return redirect()->route('payment', [
                    'plan_id' => $request->input('plan_id'),
                    'club_id' => $request->input('club_id')
                ]);
            }
            
            return redirect()->route('home');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Invalid login credentials');
    }

    /**
     * Handle logout for all guards.
     */
    public function destroy(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('club')->check()) {
            Auth::guard('club')->logout();
        } elseif (Auth::guard('coach')->check()) {
            Auth::guard('coach')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        return redirect('/login');
    }
}
