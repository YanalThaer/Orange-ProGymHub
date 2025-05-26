<?php

namespace App\Http\Controllers;

use App\Mail\CoachProfileUpdatedMail;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Club;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Models\Progress;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coach = Auth::guard('coach')->user();
        $clubInfo = null;
        $totalClubCoaches = 0;
        $totalClubMembers = 0;
        $coachClients = 0;
        $coachWorkoutPlans = 0;
        $myClients = collect();
        $workoutPlans = collect();
        $clientProgress = [];
        $planClients = [];
        
        $coachClients = User::where('coach_id', $coach->id)->count();
        $myClients = User::where('coach_id', $coach->id)->get();
        
        $coachWorkoutPlans = WorkoutPlan::where('coach_id', $coach->id)->count();
        $workoutPlans = WorkoutPlan::where('coach_id', $coach->id)->get();
        
        foreach ($myClients as $client) {
            $progressEntries = Progress::where('user_id', $client->id)->latest()->take(5)->get();
            if ($progressEntries->count() > 0) {
                $clientProgress[$client->id] = rand(10, 90);
            }
        }
        
        foreach ($workoutPlans as $plan) {
            $planClients[$plan->id] = User::where('coach_id', $coach->id)
                ->whereHas('workoutPlans', function($query) use ($plan) {
                    $query->where('id', $plan->id);
                })->get();
        }
        
        if ($coach->club_id) {
            $clubInfo = $coach->club;
            $totalClubCoaches = Coach::where('club_id', $coach->club_id)->count();
            $totalClubMembers = User::where('club_id', $coach->club_id)->count();
        }
        
        return view('dashboard.dashboard', compact(
            'coach', 
            'clubInfo', 
            'totalClubCoaches', 
            'totalClubMembers', 
            'coachClients',
            'coachWorkoutPlans',
            'myClients',
            'workoutPlans',
            'clientProgress',
            'planClients'
        ));
    }

    /**
     * Show the coach profile.
     */
    public function profile()
    {
        $coach = Auth::guard('coach')->user();
        return view('dashboard.coach.profile', compact('coach'));
    }

    /**
     * Show the form for editing the coach profile.
     */
    public function editProfile()
    {
        $coach = Auth::guard('coach')->user();
        return view('dashboard.coach.edit-profile', compact('coach'));
    }

    /**
     * Update the coach profile.
     */
    public function updateProfile(Request $request)
    {
        $coach = Auth::guard('coach')->user();
        
        $oldData = $coach->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:coaches,email,' . $coach->id,
            'phone' => 'required|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'experience_years' => 'nullable|integer|min:0',
            'certifications' => 'nullable|array',
            'specializations' => 'nullable|array',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'working_hours' => 'nullable|array',
            'employment_type' => 'nullable|string|in:full-time,part-time,contract,freelance',
        ]);
        
        if ($request->hasFile('profile_image')) {
            if ($coach->profile_image && Storage::disk('public')->exists($coach->profile_image)) {
                Storage::disk('public')->delete($coach->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('coach-profiles', 'public');
            $validated['profile_image'] = $imagePath;
        }
        
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        if (isset($validated['certifications']) && is_array($validated['certifications'])) {
            $validated['certifications'] = array_values(array_filter($validated['certifications'], function($value) {
                return !empty(trim($value));
            }));
        }
        
        if (isset($validated['specializations']) && is_array($validated['specializations'])) {
            $validated['specializations'] = array_values(array_filter($validated['specializations'], function($value) {
                return !empty(trim($value));
            }));
        }
        
        $workingHours = $request->input('working_hours', []);
        $processedWorkingHours = [];
        
        foreach ($workingHours as $day => $hours) {
            if (empty($hours) || (is_array($hours) && (empty($hours[0]) || $hours[0] === null))) {
                $processedWorkingHours[$day] = [null];
            } else {
                $processedWorkingHours[$day] = $hours;
            }
        }
        
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($daysOfWeek as $day) {
            if (!isset($processedWorkingHours[$day])) {
                $processedWorkingHours[$day] = [null];
            }
        }
        
        $validated['working_hours'] = $processedWorkingHours;
        
        $coach->update($validated);
        
        $changedFields = $this->identifyChangedFields($oldData, $coach->refresh()->toArray());
        
        if (!empty($changedFields)) {
            $this->sendProfileUpdateNotification($coach, $oldData, $changedFields);
        }
        
        return redirect()->route('coach.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoachRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coach $coach)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoachRequest $request, Coach $coach)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coach $coach)
    {
        //
    }
    
    /**
     * Identify which fields were changed in the coach profile.
     *
     * @param array $oldData
     * @param array $newData
     * @return array
     */
    private function identifyChangedFields(array $oldData, array $newData): array
    {
        $changedFields = [];
        
        $trackableFields = [
            'name', 'email', 'phone', 'location', 'bio', 'gender',
            'experience_years', 'certifications', 'specializations',
            'profile_image', 'password', 'working_hours', 'employment_type'
        ];
        
        foreach ($trackableFields as $field) {
            if (!isset($oldData[$field]) && !isset($newData[$field])) {
                continue;
            }
            
            if (isset($oldData[$field]) && isset($newData[$field])) {
                $oldValue = is_array($oldData[$field]) ? json_encode($oldData[$field]) : $oldData[$field];
                $newValue = is_array($newData[$field]) ? json_encode($newData[$field]) : $newData[$field];
                
                if ($oldValue !== $newValue) {
                    $changedFields[] = $field;
                }
            } 
            elseif (!isset($oldData[$field]) && isset($newData[$field])) {
                $changedFields[] = $field;
            } 
            elseif (isset($oldData[$field]) && !isset($newData[$field])) {
                $changedFields[] = $field;
            }
        }
        
        return $changedFields;
    }
    
    /**
     * Send profile update notification email.
     *
     * @param Coach $coach
     * @param array $oldData
     * @param array $changedFields
     * @return void
     */
    /**
     * Display club details for a coach
     */
    public function clubDetails()
    {
        $coach = Auth::guard('coach')->user();
        
        if (!$coach->club_id) {
            return redirect()->route('coach.dashboard')->with('error', 'You are not assigned to any club.');
        }
        
        $club = Club::find($coach->club_id);
        if (!$club) {
            return redirect()->route('coach.dashboard')->with('error', 'Club not found.');
        }
        
        $totalClubCoaches = Coach::where('club_id', $coach->club_id)->count();
        
        return view('dashboard.coach.club-details', compact('club', 'totalClubCoaches'));
    }
    
    /**
     * Display clients assigned to the coach.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function clients()
    {
        // dd('clients');
        $coach = Auth::guard('coach')->user();
        
        $clients = User::where('coach_id', $coach->id)->get();
        
        $allUsers = collect();
        $clubUsers = collect();
        $hasClub = false;
        
        if ($coach->club_id) {
            $hasClub = true;
            $clubUsers = User::where(function($query) use ($coach) {
                $query->where('club_id', $coach->club_id)
                      ->where(function($q) use ($coach) {
                          $q->where('coach_id', '!=', $coach->id)
                            ->orWhereNull('coach_id');
                      });
            })->get();
        } else {
            $allUsers = User::whereNull('coach_id')
                ->get();
        }
        
        // $clientWorkoutPlans = [];
        $clientProgress = [];
        
        foreach ($clients as $client) {
            // $workoutPlans = WorkoutPlan::where('coach_id', $coach->id)
            //     ->whereHas('users', function($query) use ($client) {
            //         $query->where('user_id', $client->id);
            //     })
            //     ->get();
                
            // $clientWorkoutPlans[$client->id] = $workoutPlans;
            
            $progress = Progress::where('user_id', $client->id)
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($progress) {
                $clientProgress[$client->id] = $progress;
            }
        }
        
        return view('dashboard.coach.clients', compact(
            'clients', 
            // 'clientWorkoutPlans', 
            'clientProgress',
            'allUsers',
            'clubUsers',
            'hasClub'
        ));
    }
    
        /**
     * Assign a user to the current coach
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignClient(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $coach = Auth::guard('coach')->user();
        $userId = $request->input('user_id');
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('coach.clients')->with('error', 'User not found.');
        }
        
        if ($user->coach_id) {
            return redirect()->route('coach.clients')->with('error', 'User is already assigned to a coach.');
        }
        
        if ($coach->club_id && $user->club_id != $coach->club_id) {
            return redirect()->route('coach.clients')->with('error', 'You can only assign users from your club.');
        }
        
        $user->coach_id = $coach->id;
        $user->save();
        
        return redirect()->route('coach.clients')->with('success', 'User has been assigned to you successfully.');
    }
    
    private function sendProfileUpdateNotification(Coach $coach, array $oldData, array $changedFields): void
    {
        $admin = Admin::first(); 
        $adminEmail = $admin->email;
        
        $club = null;
        if ($coach->club_id) {
            $club = Club::find($coach->club_id);
        }
        
        $mail = new CoachProfileUpdatedMail($coach, $oldData, $changedFields, $club);
        
        Mail::to($adminEmail)->send($mail);
        
        if ($club && $club->email && $club->email != $adminEmail) {
            Mail::to($club->email)->send($mail);
        }
    }
    
    /**
     * Display search form for coach
     */
    public function search()
    {
        return view('dashboard.coaches.search.index');
    }
    
    /**
     * Process the search and display results for coach
     */
    public function searchResults(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:2|max:100',
            'search_type' => 'required|in:all,clients,workout_plans,progress'
        ]);
        
        $coach = Auth::guard('coach')->user();
        $searchTerm = $request->search_term;
        $searchType = $request->search_type;
        
        $clients = collect();
        $workoutPlans = collect();
        $progressRecords = collect();
        
        if ($searchType === 'all' || $searchType === 'clients') {
            $clients = User::where('coach_id', $coach->id)
                ->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('phone_number', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('goal', 'LIKE', "%{$searchTerm}%");
                })
                ->get();
        }
        
        if ($searchType === 'all' || $searchType === 'workout_plans') {
            $workoutPlans = WorkoutPlan::where('coach_id', $coach->id)
                ->where(function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                })
                ->get();
        }
        
        if ($searchType === 'all' || $searchType === 'progress') {
            $clientIds = User::where('coach_id', $coach->id)->pluck('id');
            $progressRecords = Progress::whereIn('user_id', $clientIds)
                ->where(function($query) use ($searchTerm) {
                    $query->where('weight', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('notes', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('body_fat', 'LIKE', "%{$searchTerm}%");
                })
                ->with('user')
                ->get();
        }
        
        return view('dashboard.coaches.search.results', compact('searchTerm', 'searchType', 'clients', 'workoutPlans', 'progressRecords'));
    }
}
