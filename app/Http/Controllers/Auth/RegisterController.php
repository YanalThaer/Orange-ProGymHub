<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Category;
use App\Models\Project;
use App\Models\Message;
use App\Models\Like;

class RegisterController extends Controller
{
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:20'],
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only('name', 'email', 'password', 'phone_number');

        $verificationCode = rand(100000, 999999);

        session([
            'register_data' => $data,
            'verification_code' => $verificationCode,
            'verification_expires_at' => now()->addMinutes(5),
        ]);

        Mail::raw("Your verification code is: $verificationCode", function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('Email Verification Code');
        });

        return redirect()->route('verify.email.form')->with('status', 'Verification code sent to your email.');
    }

    public function verifyEmail()
    {
        if (!session()->has('register_data')) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Please register first to verify your email.']);
        }
        
        return view('auth.verify-email')
            ->with('status', session('status'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $expiresAt = session('verification_expires_at');
        
        if (!$expiresAt || Carbon::now()->gt(Carbon::parse($expiresAt))) {
            session()->forget(['register_data', 'verification_code', 'verification_expires_at']);
            return redirect()->route('register')->withErrors(['code' => 'The verification code has expired. Please register again.']);
        }

        if ($request->code == session('verification_code')) {
            $data = session('register_data');

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'],
                'join_date' => now(),
                'goal' => '',
            ]);

            Auth::login($user);
            
            $request->session()->put('created_at', time());
            $request->session()->put('remember_me', true);

            session()->forget(['register_data', 'verification_code', 'verification_expires_at']);

            return redirect()->route('profile.complete.show')->with('status', 'Registration successful! Please complete your profile.');
        }

        return back()->withErrors(['code' => 'Invalid verification code']);
    }

    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     // dd('Google callback');
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //         // dd($googleUser);

    //         $user = User::where('provider_id', $googleUser->getId())->first();

    //         // dd($user);

    //         if (!$user) {
    //             $user = User::where('email', $googleUser->getEmail())->first();

    //             if (!$user) {
    //                 // dd('User not found');
    //                 $user = User::create([
    //                     'name' => $googleUser->getName(),
    //                     'email' => $googleUser->getEmail(),
    //                     'password' => Hash::make(uniqid()),
    //                     'provider' => 'google',
    //                     'provider_id' => $googleUser->getId(),
    //                     'phone_number' => 'Please update your phone number', // Default phone number
    //                     'join_date' => now(),
    //                     'goal' => '',
    //                 ]);

    //                 Profile::create([
    //                     'user_id' => $user->id,
    //                     'profile_picture' => $googleUser->getAvatar(),
    //                 ]);
    //                 // dd('User created');
    //             } else {
    //                 $user->update([
    //                     'provider' => 'google',
    //                     'provider_id' => $googleUser->getId(),
    //                 ]);
    //             }
    //         }

    //         Auth::login($user);

    //         return redirect()->route('home')->with('status', 'Registration successful!');
    //     } catch (\Exception $e) {
    //         return redirect()->route('login')->with('error', 'Failed to login with Google!');
    //     }
    // }
}
