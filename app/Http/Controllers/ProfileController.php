<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('App\Http\Middleware\ProfileCompletionCheck');
    }

    /**
     * Show user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.view', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }    
    
    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Profile update request received', [
                'user_id' => $user->id,
                'request_method' => $request->method(),
                'request_data' => $request->all()
            ]);
            
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'current_password' => ['nullable', 'string', 'min:8'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed', 'different:current_password'],
                'goal' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['nullable', 'date'],
                'gender' => ['nullable', 'in:male,female'],
                'height_cm' => ['nullable', 'numeric', 'min:50', 'max:300'],
                'weight_kg' => ['nullable', 'numeric', 'min:20', 'max:500'],
                'target_weight_kg' => ['nullable', 'numeric', 'min:20', 'max:500'],
                'health_conditions' => ['nullable', 'string'],
                'injuries' => ['nullable', 'string'],
                'allergies' => ['nullable', 'string'],
                'medications' => ['nullable', 'string'],
                'fitness_level' => ['required', 'string'],
                'training_days_per_week' => ['nullable', 'integer', 'min:0', 'max:7'],
                'preferred_training_time' => ['nullable', 'string'],
                'preferred_workout_duration' => ['nullable', 'string'],
                'exercise_preferences' => ['nullable', 'string'],
                'exercise_dislikes' => ['nullable', 'string'],
                'diet_preference' => ['nullable', 'string'],
                'meals_per_day' => ['nullable', 'integer', 'min:1', 'max:10'],
                'food_preferences' => ['nullable', 'string'],
                'food_dislikes' => ['nullable', 'string'],
            ];
            
            $request->validate($rules);
            
            $data = $request->only(array_keys($rules));
            
            if (!empty($request->current_password) && !empty($request->password)) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->route('profile.edit')
                        ->withErrors(['current_password' => 'The current password is incorrect.'])
                        ->withInput();
                }
                
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
                unset($data['current_password']);
            }
            
            if (!empty($data['height_cm']) && !empty($data['weight_kg'])) {
                $heightInMeters = $data['height_cm'] / 100;
                $data['bmi'] = round($data['weight_kg'] / ($heightInMeters * $heightInMeters), 2);
            }
            \Log::info('Processed data before saving:', $data);
            
            $fieldsToUpdate = collect($data)
                ->filter(function ($value, $key) use ($request) {
                    return $request->has($key) && 
                           ($value !== null && $value !== '') && 
                           $key !== 'current_password' && 
                           $key !== 'password_confirmation';
                })
                ->toArray();
                
            \Log::info('Fields actually being updated:', $fieldsToUpdate);
            
            foreach ($fieldsToUpdate as $key => $value) {
                $user->$key = $value;
            }
            
            $result = $user->save();
            
            \Log::info('Profile update result: ' . ($result ? 'Success' : 'Failed') . ' for user ' . $user->id);
            
            if (!$result) {
                throw new \Exception('Failed to save user data');
            }
            
            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Error updating user profile: ' . $e->getMessage());
            
            if (isset($user)) {
                \Log::error('User ID: ' . $user->id);
            }
            
            if (isset($data)) {
                \Log::error('Data: ' . json_encode($data));
            }
            
            return redirect()->route('profile.edit')
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }
}
