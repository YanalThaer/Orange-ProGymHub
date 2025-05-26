<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileCompletionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the profile completion form.
     *
     * @return \Illuminate\View\View
     */    public function show()
    {
        $user = Auth::user();
        
        if ($this->isProfileComplete($user)) {
            return redirect()->route('home');
        }
        
        $firstVisit = !session()->has('profile_completion_visited');
        if ($firstVisit) {
            session(['profile_completion_visited' => true]);
        }
        
        return view('auth.profile-completion', [
            'firstVisit' => $firstVisit
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'height_cm' => 'nullable|numeric|min:50|max:300',
            'weight_kg' => 'nullable|numeric|min:20|max:500',
            'target_weight_kg' => 'nullable|numeric|min:20|max:500',
            'health_conditions' => 'nullable|string',
            'injuries' => 'nullable|string',
            'allergies' => 'nullable|string',
            'fitness_level' => 'nullable|in:beginner,intermediate,advanced',
            'training_days_per_week' => 'nullable|integer|min:1|max:7',
            'preferred_training_time' => 'nullable|string|max:50',
            'preferred_workout_duration' => 'nullable|string|max:50',
            'exercise_preferences' => 'nullable|string',
            'diet_preference' => 'nullable|in:no_restriction,vegetarian,vegan,keto,paleo,mediterranean,other',
            'meals_per_day' => 'nullable|integer|min:1|max:10',
            'food_preferences' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        $data = $validator->validated();
        
        if (!empty($data['height_cm']) && !empty($data['weight_kg'])) {
            $heightInMeters = $data['height_cm'] / 100;
            $data['bmi'] = round($data['weight_kg'] / ($heightInMeters * $heightInMeters), 2);
        }
        
        $user->update($data);

        return redirect()->route('home')->with('status', 'Profile information saved successfully!');
    }

    /**
     * Check if the user's profile is already completed
     *
     * @param  \App\Models\User  $user
     * @return bool
     */    /**
     * Mark the profile completion as skipped.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function skip(Request $request)
    {
        session(['skip_profile_completion' => true]);
        
        return redirect()->route('home')->with('status', 'You can complete your profile later from your account settings.');
    }

    /**
     * Check if the user's profile is already completed
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function isProfileComplete($user)
    {
        return !empty($user->goal) && 
               !empty($user->fitness_level) &&
               !empty($user->weight_kg) && 
               !empty($user->height_cm);
    }
}
