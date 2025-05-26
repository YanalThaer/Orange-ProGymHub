<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Coach;
use App\Models\Club;
use App\Models\Notification;
use App\Mail\UserDeletedMail;
use App\Mail\CoachDeletedMail;
use App\Mail\ClubMemberActionMail;
use App\Mail\ClubCoachActionMail;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display the admin's profile.
     *
     * @return \Illuminate\View\View
     */
    public function viewProfile()
    {
        $admin = auth()->guard('admin')->user();
        return view('dashboard.admin.profile.show', compact('admin'));
    }
    
    /**
     * Show the form for editing the admin's profile.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $admin = auth()->guard('admin')->user();
        return view('dashboard.admin.profile.edit', compact('admin'));
    }
    
    /**
     * Update the admin's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,'.$admin->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone_number = $request->phone_number;
        
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $admin->profile_picture = $path;
        }
        
        $admin->save();
        
        return redirect()->route('admin.profile')
                         ->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubs = Club::with('subscriptionPlans', 'users', 'coaches')->get();
        
        $coaches = Coach::with(['club', 'workoutPlans'])->latest()->take(10)->get();
        
        $users = User::with(['club', 'coach', 'userSubscription'])->latest()->take(10)->get();
        
        $totalUsers = User::count();
        $totalClubs = $clubs->count();
        $totalCoaches = Coach::count();
        $totalSubscriptionPlans = \App\Models\SubscriptionPlan::count();
        
        return view('dashboard.dashboard', compact(
            'clubs', 
            'coaches',
            'users',
            'totalUsers', 
            'totalClubs', 
            'totalCoaches',
            'totalSubscriptionPlans'
        ));
    }
    
    /**
     * Display all users in the system
     *
     * @return \Illuminate\View\View
     */
    public function allUsers()
    {
        $users = User::latest()->paginate(15);
        return view('dashboard.admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showUser($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $user = User::with(['club', 'coach'])->findOrFail($decoded_id);
        return view('dashboard.admin.users.show', compact('user'));
    }
    
    /**
     * Decode an encoded ID
     *
     * @param string $encoded_id
     * @return int
     */
    private function decodeId($encoded_id)
    {
        $encoded_id = str_replace(['-', '_'], ['+', '/'], $encoded_id);
        $paddingLength = strlen($encoded_id) % 4;
        if ($paddingLength) {
            $encoded_id .= str_repeat('=', 4 - $paddingLength);
        }
        
        $decoded = base64_decode($encoded_id);
        
        if (preg_match('/^club-(\d+)-\d+$/', $decoded, $matches)) {
            return (int)$matches[1];
        }
        
        if (preg_match('/^coach-(\d+)-\d+$/', $decoded, $matches)) {
            return (int)$matches[1];
        }
        
        return (int)$encoded_id;
    }

    /**
     * Delete user (soft delete)
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $user = User::with('club')->findOrFail($decoded_id);
        
        $club = $user->club;
        
        $user->delete();
        
        if ($user->email) {
            try {
                Mail::to($user->email)
                    ->send(new UserDeletedMail($user, 'deleted'));
            } catch (\Exception $e) {
                Log::error('Failed to send user deletion email: ' . $e->getMessage());
            }
        }
        
        if ($club) {
            try {
                Mail::to($club->email)
                    ->send(new ClubMemberActionMail($user, $club, 'deleted'));
            } catch (\Exception $e) {
                Log::error('Failed to send club notification: ' . $e->getMessage());
            }
            
            Notification::create([
                'type' => 'user_deleted',
                'title' => 'Member Deleted',
                'message' => "User {$user->name} has been deleted by an administrator.",
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id
            ]);
        }
        
        return redirect()->route('admin.users')
                         ->with('success', 'User deleted successfully');
    }

    /**
     * Show trashed users
     *
     * @return \Illuminate\View\View
     */
    public function trashedUsers()
    {
        $users = User::onlyTrashed()->paginate(15);
        return view('dashboard.admin.users.trashed', compact('users'));
    }
    
    /**
     * Restore deleted user
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreUser($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $user = User::with('club')->onlyTrashed()->findOrFail($decoded_id);
        $user->restore();
        
        if ($user->email) {
            try {
                Mail::to($user->email)
                    ->send(new UserDeletedMail($user, 'restored'));
            } catch (\Exception $e) {
                Log::error('Failed to send user restoration email: ' . $e->getMessage());
            }
        }
        
        // Send notification to club if user belongs to a club
        if ($user->club) {
            try {
                Mail::to($user->club->email)
                    ->send(new ClubMemberActionMail($user, $user->club, 'restored'));
            } catch (\Exception $e) {
                Log::error('Failed to send club notification: ' . $e->getMessage());
            }
            
            Notification::create([
                'type' => 'user_restored',
                'title' => 'Member Restored',
                'message' => "User {$user->name} has been restored by an administrator.",
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $user->club->id
            ]);
        }
        
        return redirect()->route('admin.users.trashed')
                         ->with('success', 'User restored successfully');
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
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
    
    /**
     * Display all coaches in the system
     *
     * @return \Illuminate\View\View
     */
    public function allCoaches()
    {
        // Get coaches with clubs assigned first, then coaches without clubs
        $coaches = Coach::with('club')
            ->orderByRaw('CASE WHEN club_id IS NOT NULL THEN 0 ELSE 1 END') // This prioritizes non-null club_id
            ->latest()
            ->paginate(15);
            
        return view('dashboard.admin.coaches.index', compact('coaches'));
    }

    /**
     * Display the specified coach.
     *
     * @param string $encoded_id
     * @return \Illuminate\View\View
     */
    public function showCoach($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $coach = Coach::with(['club', 'workoutPlans'])->findOrFail($decoded_id);
        
        if (is_string($coach->certifications)) {
            try {
                $coach->certifications = json_decode($coach->certifications, true) ?: [];
            } catch (\Exception $e) {
                $coach->certifications = [];
            }
        }
        
        if (is_string($coach->specializations)) {
            try {
                $coach->specializations = json_decode($coach->specializations, true) ?: [];
            } catch (\Exception $e) {
                $coach->specializations = [];
            }
        }
        
        $assignedUsers = User::with('userSubscription')
                          ->where('coach_id', $coach->id)
                          ->where('club_id', $coach->club_id)
                          ->get();
                                       
        return view('dashboard.admin.coaches.show', compact('coach', 'assignedUsers'));
    }

    /**
     * Delete coach (soft delete)
     *
     * @param string $encoded_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCoach($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $coach = Coach::with('club')->findOrFail($decoded_id);
        
        $club = $coach->club;
        
        $coach->delete();
        
        if ($coach->email) {
            try {
                Mail::to($coach->email)
                    ->send(new CoachDeletedMail($coach, 'deleted'));
            } catch (\Exception $e) {
                Log::error('Failed to send coach deletion email: ' . $e->getMessage());
            }
        }
        
        if ($club) {
            try {
                Mail::to($club->email)
                    ->send(new ClubCoachActionMail($coach, $club, 'deleted'));
            } catch (\Exception $e) {
                Log::error('Failed to send club notification: ' . $e->getMessage());
            }
            
            Notification::create([
                'type' => 'coach_deleted',
                'title' => 'Coach Deleted',
                'message' => "Coach {$coach->name} has been deleted by an administrator.",
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id
            ]);
        }
        
        return redirect()->route('admin.coaches')
                         ->with('success', 'Coach deleted successfully');
    }

    /**
     * Show trashed coaches
     *
     * @return \Illuminate\View\View
     */
    public function trashedCoaches()
    {
        $coaches = Coach::with('club')->onlyTrashed()->paginate(15);
        return view('dashboard.admin.coaches.trashed', compact('coaches'));
    }
    
    /**
     * Restore deleted coach
     *
     * @param string $encoded_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreCoach($encoded_id)
    {
        $decoded_id = $this->decodeId($encoded_id);
        $coach = Coach::with('club')->onlyTrashed()->findOrFail($decoded_id);
        $coach->restore();
        
        if ($coach->email) {
            try {
                Mail::to($coach->email)
                    ->send(new CoachDeletedMail($coach, 'restored'));
            } catch (\Exception $e) {
                Log::error('Failed to send coach restoration email: ' . $e->getMessage());
            }
        }
        
        if ($coach->club) {
            try {
                Mail::to($coach->club->email)
                    ->send(new ClubCoachActionMail($coach, $coach->club, 'restored'));
            } catch (\Exception $e) {
                Log::error('Failed to send club notification: ' . $e->getMessage());
            }
            
            Notification::create([
                'type' => 'coach_restored',
                'title' => 'Coach Restored',
                'message' => "Coach {$coach->name} has been restored by an administrator.",
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $coach->club->id
            ]);
        }
        
        return redirect()->route('admin.coaches.trashed')
                         ->with('success', 'Coach restored successfully');
    }
    
    /**
     * Display the search form for clubs, coaches, and users
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        return view('dashboard.admin.search.index');
    }
    
    /**
     * Process the search and display results
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function searchResults(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:2|max:100',
            'search_type' => 'required|in:all,clubs,coaches,users'
        ]);
        
        $searchTerm = $request->search_term;
        $searchType = $request->search_type;
        $results = [];
        
        if ($searchType === 'all' || $searchType === 'clubs') {
            $clubs = Club::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                ->orWhere('city', 'LIKE', "%{$searchTerm}%")
                ->get();
                
            $results['clubs'] = $clubs;
        }
        
        if ($searchType === 'all' || $searchType === 'coaches') {
            $coaches = Coach::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                ->orWhere('bio', 'LIKE', "%{$searchTerm}%")
                ->with('club')
                ->get();
                
            $results['coaches'] = $coaches;
        }
        
        if ($searchType === 'all' || $searchType === 'users') {
            $users = User::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('phone_number', 'LIKE', "%{$searchTerm}%")
                ->with(['club', 'coach'])
                ->get();
                
            $results['users'] = $users;
        }
        
        return view('dashboard.admin.search.results', compact('results', 'searchTerm', 'searchType'));
    }
}
