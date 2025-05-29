<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Admin;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ClubController extends Controller
{
    /**
     * Display a listing of the clubs.
     */
    public function index()
    {
        $this->clearClubCreationSession();

        $club = Auth::guard('club')->user();

        $club->load('users', 'coaches', 'subscriptionPlans', 'userSubscriptions');

        return view('dashboard.dashboard', compact('club'));
    }

    /**
     * Clear club creation session data
     */
    private function clearClubCreationSession()
    {
        session()->forget(['club_data', 'verification_code', 'verification_expires_at']);
    }

    /**
     * Show the form for creating a new club.
     */
    public function create()
    {
        $this->clearClubCreationSession();

        $admins = Admin::all();
        return view('dashboard.clubs.create', compact('admins'));
    }

    /**
     * Store a newly created club in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clubs',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'bio' => 'nullable|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'has_parking' => 'nullable|boolean',
            'has_wifi' => 'nullable|boolean',
            'has_showers' => 'nullable|boolean',
            'has_lockers' => 'nullable|boolean',
            'has_pool' => 'nullable|boolean',
            'has_sauna' => 'nullable|boolean',
            'website' => 'nullable|url',
            'emergency_contact' => 'nullable|string',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'under_maintenance'])],
            'established_date' => 'nullable|date',
            'admin_id' => 'required|exists:admins,id',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'logo' => 'nullable|image|max:2048',
        ]);

        $social_media = $request->input('social_media', []);
        $working_days = $request->input('working_days', []);
        $special_hours = $request->input('special_hours', []);

        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $hashedPassword = Hash::make($validated['password']);

        $verificationCode = rand(100000, 999999);

        session([
            'club_data' => [
                ...$validated,
                'password' => $hashedPassword,
                'social_media' => $social_media,
                'working_days' => $working_days,
                'special_hours' => $special_hours,
                'logo' => $logoPath,
            ],
            'verification_code' => $verificationCode,
            'verification_expires_at' => now()->addMinutes(30),
        ]);

        Mail::raw("Your ProGymHub Club Account Verification Code is: $verificationCode\n\nThis code will expire in 30 minutes. Please verify your email to activate your club account.", function ($message) use ($validated) {
            $message->to($validated['email'])
                ->subject('ProGymHub Club Account Verification');
        });

        return redirect()->route('admin.club.verify.email.form')
            ->with('status', 'A verification code has been sent to the club\'s email (' . $validated['email'] . '). Please verify the email to complete club registration.');
    }

    /**
     * Send a password reset link to the club's email
     */
    private function sendPasswordResetLink(Club $club)
    {
        return \App\Http\Controllers\Auth\ClubPasswordResetHandlerController::sendPasswordResetLink($club);
    }

    /**
     * Send an account update notification email to the club and the admin
     */
    private function sendAccountUpdateNotification(Club $club, $updatedBy = null)
    {
        Mail::send('emails.club-account-updated', ['club' => $club], function ($message) use ($club) {
            $message->to($club->email)
                ->subject('Account Information Updated - ProGymHub');
        });

        if ($updatedBy === 'admin') {
            $club->customNotifications()->create([
                'type' => 'club_update',
                'title' => 'Club Profile Updated',
                'message' => 'Your club profile has been updated by an administrator.',
                'data' => ['updated_at' => now()->toDateTimeString()]
            ]);
        }

        if ($club->admin && $updatedBy === 'club') {
            Mail::send('emails.admin-club-account-updated', ['club' => $club, 'admin' => $club->admin], function ($message) use ($club) {
                $message->to($club->admin->email)
                    ->subject('Club Account Updated - ProGymHub: ' . $club->name);
            });

            $club->admin->customNotifications()->create([
                'type' => 'admin_notification',
                'title' => 'Club Updated Profile',
                'message' => "Club '{$club->name}' has updated their profile information.",
                'data' => ['club_id' => $club->id, 'updated_at' => now()->toDateTimeString()]
            ]);
        }
    }

    /**
     * Display the specified club.
     */
    public function show($encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            return redirect()->route('clubs.index')
                ->with('error', 'Club not found.');
        }

        $club->load('subscriptionPlans', 'coaches', 'users');

        return view('dashboard.clubs.show', compact('club'));
    }

    /**
     * Show the form for editing the specified club.
     */
    public function edit($encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            return redirect()->route('clubs.index')
                ->with('error', 'Club not found.');
        }

        $admins = Admin::all();
        return view('dashboard.clubs.edit', compact('club', 'admins'));
    }

    /**
     * Update the specified club in storage.
     */
    public function update(Request $request, $encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            return redirect()->route('clubs.index')
                ->with('error', 'Club not found.');
        }

        $isClubUser = Auth::guard('club')->check() && Auth::guard('club')->id() === $club->id;

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('clubs')->ignore($club->id)],
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'has_parking' => 'boolean',
            'has_wifi' => 'boolean',
            'has_showers' => 'boolean',
            'has_lockers' => 'boolean',
            'has_pool' => 'boolean',
            'has_sauna' => 'boolean',
            'website' => 'nullable|url',
            'emergency_contact' => 'nullable|string',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'under_maintenance'])],
            'established_date' => 'nullable|date',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'logo' => 'nullable|image|max:2048',
        ];

        if ($isClubUser) {
            $validationRules['password'] = 'nullable|string|min:8|confirmed';
        }

        $validated = $request->validate($validationRules);

        $social_media = $request->input('social_media', []);
        $working_days = $request->input('working_days', []);
        $special_hours = $request->input('special_hours', []);

        $validated['has_parking'] = $request->has('has_parking');
        $validated['has_wifi'] = $request->has('has_wifi');
        $validated['has_showers'] = $request->has('has_showers');
        $validated['has_lockers'] = $request->has('has_lockers');
        $validated['has_pool'] = $request->has('has_pool');
        $validated['has_sauna'] = $request->has('has_sauna');

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($club->logo && Storage::disk('public')->exists($club->logo)) {
                Storage::disk('public')->delete($club->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $updateData = [
            ...$validated,
            'social_media' => $social_media,
            'working_days' => $working_days,
            'special_hours' => $special_hours,
        ];

        if (Auth::guard('admin')->check()) {
            $updateData['admin_id'] = Auth::guard('admin')->id();
        }

        $club->update($updateData);

        $updatedBy = Auth::guard('admin')->check() ? 'admin' : 'club';
        $this->sendAccountUpdateNotification($club, $updatedBy);

        if ($isClubUser) {
            return redirect()->route('club.profile')
                ->with('success', 'Your club profile has been updated successfully. An email notification has been sent.');
        } else {
            return redirect()->route('clubs.show', $club)
                ->with('success', 'Club updated successfully. An email notification has been sent to the club and admin.');
        }
    }

    /**
     * Remove the specified club from storage.
     */
    public function destroy($encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            return redirect()->route('clubs.index')
                ->with('error', 'Club not found.');
        }

        $admin = Auth::guard('admin')->user();

        Mail::send('emails.club-deletion-notification', ['club' => $club, 'admin' => $admin], function ($message) use ($club, $admin) {
            $message->to($club->email)
                ->subject('Club Account Deletion Notice - ProGymHub');
        });

        $club->delete();

        return redirect()->route('admin.clubs')
            ->with('success', 'Club deleted successfully. A notification email has been sent to the club.');
    }

    /**
     * Display clubs by filter
     */
    public function filter(Request $request)
    {
        $query = Club::query();

        if ($request->has('has_parking')) {
            $query->where('has_parking', true);
        }

        if ($request->has('has_wifi')) {
            $query->where('has_wifi', true);
        }

        if ($request->has('has_showers')) {
            $query->where('has_showers', true);
        }

        if ($request->has('has_lockers')) {
            $query->where('has_lockers', true);
        }

        if ($request->has('has_pool')) {
            $query->where('has_pool', true);
        }

        if ($request->has('has_sauna')) {
            $query->where('has_sauna', true);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        $clubs = $query->get();

        return view('clubs.index', compact('clubs'));
    }

    /**
     * Display nearby clubs based on user location
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'distance' => 'nullable|numeric|min:1|max:50',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $distance = $request->distance ?? 10;

        $clubs = Club::all()
            ->filter(function ($club) use ($latitude, $longitude, $distance) {
                if (empty($club->location)) {
                    return false;
                }

                list($clubLat, $clubLng) = explode(',', $club->location);

                $earthRadius = 6371;
                $latDelta = deg2rad($clubLat - $latitude);
                $lngDelta = deg2rad($clubLng - $longitude);

                $a = sin($latDelta / 2) * sin($latDelta / 2) +
                    cos(deg2rad($latitude)) * cos(deg2rad($clubLat)) *
                    sin($lngDelta / 2) * sin($lngDelta / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $calculatedDistance = $earthRadius * $c;

                return $calculatedDistance <= $distance;
            });

        return response()->json($clubs);
    }

    /**
     * Display a listing of the clubs for admin.
     */
    public function adminClubs()
    {
        // First get all clubs
        $activeClubs = Club::where('status', 'active')->get();
        $inactiveClubs = Club::where('status', 'inactive')->get();
        $maintenanceClubs = Club::where('status', 'under_maintenance')->get();

        $allClubs = $activeClubs->concat($inactiveClubs)->concat($maintenanceClubs)->values();

        $page = request()->get('page', 1);
        $perPage = 10;

        $items = $allClubs->slice(($page - 1) * $perPage, $perPage)->values();

        $clubs = new LengthAwarePaginator(
            $items,
            $allClubs->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.clubs.index', compact('clubs'));
    }

    /**
     * Display a listing of the soft-deleted clubs for admin.
     */
    public function trashedClubs()
    {
        $clubs = Club::onlyTrashed()->paginate(10);
        return view('dashboard.clubs.trashed', compact('clubs'));
    }

    /**
     * Restore a soft-deleted club.
     */
    public function restore($encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            $decodedId = null;
            $value = str_replace(['-', '_'], ['+', '/'], $encodedId);
            $paddingLength = strlen($value) % 4;
            if ($paddingLength) {
                $value .= str_repeat('=', 4 - $paddingLength);
            }
            $decoded = base64_decode($value);
            if (preg_match('/^club-(\d+)-\d+$/', $decoded, $matches)) {
                $decodedId = $matches[1];
            }
            if ($decodedId) {
                $club = Club::onlyTrashed()->find($decodedId);
            }
        }

        if (!$club) {
            return redirect()->route('admin.trashed-clubs')
                ->with('error', 'Club not found.');
        }

        $admin = Auth::guard('admin')->user();

        $club->restore();

        Mail::send('emails.club-restoration-notification', ['club' => $club, 'admin' => $admin], function ($message) use ($club, $admin) {
            $message->to($club->email)
                ->subject('Club Account Restored - ProGymHub');
        });

        return redirect()->route('admin.trashed-clubs')
            ->with('success', 'Club restored successfully. A notification email has been sent to the club.');
    }

    /**
     * Display the club profile details for the logged-in club.
     */
    public function profile()
    {
        $club = Auth::guard('club')->user();
        return view('dashboard.clubs.profile', compact('club'));
    }

    /**
     * Force delete a soft-deleted club.
     */
    public function forceDelete($encodedId)
    {
        $clubModel = new Club();
        $club = $clubModel->resolveRouteBinding($encodedId);

        if (!$club) {
            return redirect()->route('admin.trashed-clubs')
                ->with('error', 'Club not found.');
        }

        if ($club->logo && Storage::disk('public')->exists($club->logo)) {
            Storage::disk('public')->delete($club->logo);
        }

        $club->forceDelete();

        return redirect()->route('admin.trashed-clubs')
            ->with('success', 'Club permanently deleted.');
    }

    /**
     * Display a listing of the subscription plans.
     */
    public function subscriptionPlans()
    {
        /** @var \App\Models\Club $club */
        $club = Auth::guard('club')->user();
        $subscriptionPlans = $club->subscriptionPlans()->latest()->get();

        return view('dashboard.clubs.subscription_plans.index', compact('subscriptionPlans'));
    }

    /**
     * Show the form for creating a new subscription plan.
     */
    public function createSubscriptionPlan()
    {
        return view('dashboard.clubs.subscription_plans.create');
    }

    /**
     * Store a newly created subscription plan in storage.
     */
    public function storeSubscriptionPlan(Request $request)
    {
        $club = Auth::guard('club')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'type' => ['required', Rule::in(['monthly', 'quarterly', 'yearly', 'custom'])],
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $validated['club_id'] = $club->id;

        $subscriptionPlan = SubscriptionPlan::create($validated);

        $admins = Admin::all();
        foreach ($admins as $admin) {
            $notification = new \App\Models\Notification([
                'type' => 'new_subscription_plan',
                'title' => 'New Subscription Plan Added',
                'message' => 'Club ' . $club->name . ' has added a new subscription plan: ' . $subscriptionPlan->name,
                'data' => [
                    'club_id' => $club->id,
                    'plan_id' => $subscriptionPlan->id,
                    'plan_name' => $subscriptionPlan->name,
                    'plan_type' => $subscriptionPlan->type,
                    'plan_price' => $subscriptionPlan->price,
                ],
                'notifiable_type' => 'App\\Models\\Admin',
                'notifiable_id' => $admin->id
            ]);
            $notification->save();

            try {
                Mail::send('emails.new_subscription_plan', [
                    'admin' => $admin,
                    'club' => $club,
                    'plan' => $subscriptionPlan
                ], function ($message) use ($admin, $club) {
                    $message->to($admin->email)
                        ->subject('New Subscription Plan Added by ' . $club->name);
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send email to admin: ' . $e->getMessage());
            }
        }

        return redirect()->route('club.subscription-plans')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Show the form for editing the specified subscription plan.
     */
    public function editSubscriptionPlan($encodedId)
    {
        $planModel = new SubscriptionPlan();
        $plan = $planModel->resolveRouteBinding($encodedId);

        if (!$plan) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'Subscription plan not found.');
        }

        $club = Auth::guard('club')->user();

        if ($plan->club_id !== $club->id) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'You are not authorized to edit this subscription plan.');
        }

        return view('dashboard.clubs.subscription_plans.edit', compact('plan'));
    }

    /**
     * Update the specified subscription plan in storage.
     */
    public function updateSubscriptionPlan(Request $request, $encodedId)
    {
        $planModel = new SubscriptionPlan();
        $plan = $planModel->resolveRouteBinding($encodedId);

        if (!$plan) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'Subscription plan not found.');
        }

        $club = Auth::guard('club')->user();

        if ($plan->club_id !== $club->id) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'You are not authorized to update this subscription plan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'type' => ['required', Rule::in(['monthly', 'quarterly', 'yearly', 'custom'])],
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('club.subscription-plans')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * Remove the specified subscription plan from storage.
     */
    public function deleteSubscriptionPlan($encodedId)
    {
        $planModel = new SubscriptionPlan();
        $plan = $planModel->resolveRouteBinding($encodedId);

        if (!$plan) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'Subscription plan not found.');
        }

        $club = Auth::guard('club')->user();

        if ($plan->club_id !== $club->id) {
            return redirect()->route('club.subscription-plans')
                ->with('error', 'You are not authorized to delete this subscription plan.');
        }

        $plan->delete();

        return redirect()->route('club.subscription-plans')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    /**
     * Display a listing of the soft-deleted subscription plans.
     */
    public function trashedSubscriptionPlans()
    {
        $club = Auth::guard('club')->user();
        $trashedPlans = SubscriptionPlan::onlyTrashed()
            ->where('club_id', $club->id)
            ->latest()
            ->get();

        return view('dashboard.clubs.subscription_plans.trashed', compact('trashedPlans'));
    }

    /**
     * Restore a soft-deleted subscription plan.
     */
    public function restoreSubscriptionPlan($encodedId)
    {
        $club = Auth::guard('club')->user();

        $planModel = new SubscriptionPlan();
        $decodedPlan = $planModel->resolveRouteBinding($encodedId);

        if (!$decodedPlan) {
            try {
                $value = str_replace(['-', '_'], ['+', '/'], $encodedId);
                $paddingLength = strlen($value) % 4;
                if ($paddingLength) {
                    $value .= str_repeat('=', 4 - $paddingLength);
                }

                $decoded = base64_decode($value);

                if (preg_match('/^club-(\d+)-\d+$/', $decoded, $matches)) {
                    $id = $matches[1];
                    $plan = SubscriptionPlan::onlyTrashed()->where('id', $id)->first();

                    if ($plan) {
                        if ($plan->club_id !== $club->id) {
                            return redirect()->route('club.subscription-plans.trashed')
                                ->with('error', 'You are not authorized to restore this subscription plan.');
                        }

                        $plan->restore();

                        return redirect()->route('club.subscription-plans.trashed')
                            ->with('success', 'Subscription plan restored successfully.');
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error decoding subscription plan ID:', [
                    'encodedId' => $encodedId,
                    'exception' => $e->getMessage()
                ]);
            }

            return redirect()->route('club.subscription-plans.trashed')
                ->with('error', 'Subscription plan not found.');
        }

        $plan = SubscriptionPlan::onlyTrashed()->findOrFail($decodedPlan->id);

        if ($plan->club_id !== $club->id) {
            return redirect()->route('club.subscription-plans.trashed')
                ->with('error', 'You are not authorized to restore this subscription plan.');
        }

        $plan->restore();

        return redirect()->route('club.subscription-plans.trashed')
            ->with('success', 'Subscription plan restored successfully.');
    }

    /**
     * Display users for the club to manage
     */
    public function users(Request $request)
    {
        $club = Auth::guard('club')->user();
        $filter = $request->query('filter', 'all');

        if ($filter === 'active') {
            // Users who have active subscriptions
            $query = \App\Models\User::query()
                ->whereHas('subscriptions', function ($query) use ($club) {
                    $query->where('club_id', $club->id);
                })
                ->with(['subscriptions' => function ($query) use ($club) {
                    $query->where('club_id', $club->id)
                        ->with('plan', 'club');
                }]);
        } elseif ($filter === 'inactive') {
            // Users who have no subscription but belong to this club
            $query = \App\Models\User::query()
                ->where('club_id', $club->id)
                ->whereDoesntHave('subscriptions')
                ->with('club');
        } else {
            // Default behavior - all users
            $query = \App\Models\User::query()
                ->where(function ($q) use ($club) {
                    $q->where('club_id', $club->id)
                        ->orWhereHas('subscriptions', function ($sq) use ($club) {
                            $sq->where('club_id', $club->id);
                        });
                })
                ->with(['subscriptions' => function ($query) {
                    $query->with('plan', 'club');
                }]);
        }

        $users = $query->orderBy('name')->paginate(10);

        $users->map(function ($user) {
            $subscription = $user->subscriptions->first();
            $user->plan_name = $subscription->plan->name ?? 'No Plan';
            $user->start_date = $subscription->start_date ?? null;
            $user->end_date = $subscription->end_date ?? null;
            $user->payment_status = $subscription->payment_status ?? null;
            $user->club_name = $subscription->club->name ?? ($user->club ? $user->club->name : 'None');
            $user->club_status = $subscription->club->status ?? ($user->club ? $user->club->status : 'N/A');
            return $user;
        });

        return view('dashboard.clubs.users.index', compact('users', 'filter'));
    }

    /**
     * Show form to create a new user
     */
    public function createUser()
    {
        $club = Auth::guard('club')->user();

        $subscriptionPlans = SubscriptionPlan::where('club_id', $club->id)
            ->where('is_active', true)
            ->get();

        $coaches = \App\Models\Coach::where('club_id', $club->id)->get();

        return view('dashboard.clubs.users.create', compact('subscriptionPlans', 'coaches'));
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        $club = Auth::guard('club')->user();

        // If club is inactive, only restrict if they're trying to add a subscription
        if ($club->status !== 'active' && $request->has('plan_id') && !empty($request->plan_id)) {
            return redirect()->back()->with('error', 'You cannot create new user subscriptions while your club is inactive. You may create users without subscriptions.')->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'target_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'bmi' => ['nullable', 'numeric', 'min:0'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'goal' => ['nullable', 'string', 'max:255'],
            'health_conditions' => ['nullable', 'string'],
            'injuries' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'medications' => ['nullable', 'string'],
            'fitness_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced'],
            'training_days_per_week' => ['nullable', 'integer', 'min:0', 'max:7'],
            'preferred_training_time' => ['nullable', 'string'],
            'preferred_workout_duration' => ['nullable', 'string'],
            'exercise_preferences' => ['nullable', 'string'],
            'exercise_dislikes' => ['nullable', 'string'],
            'diet_preference' => ['nullable', 'string'],
            'meals_per_day' => ['nullable', 'integer', 'min:1', 'max:10'],
            'food_preferences' => ['nullable', 'string'],
            'food_dislikes' => ['nullable', 'string'],
            'plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'coach_id' => ['nullable', 'exists:coaches,id'],
        ]);

        if (!empty($validated['plan_id'])) {
            $plan = SubscriptionPlan::find($validated['plan_id']);
            if (!$plan || $plan->club_id !== $club->id) {
                return redirect()->back()->with('error', 'The selected subscription plan is invalid.')->withInput();
            }

            if (!$plan->is_active) {
                return redirect()->back()->with('error', 'The selected subscription plan is currently unavailable.')->withInput();
            }
        }

        if (!empty($validated['coach_id'])) {
            $coach = \App\Models\Coach::find($validated['coach_id']);
            if (!$coach || $coach->club_id !== $club->id) {
                return redirect()->back()->with('error', 'The selected coach is invalid.')->withInput();
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone_number' => $validated['phone_number'],
            'join_date' => now(),
            'goal' => empty($validated['goal']) ? 'General fitness' : $validated['goal'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'height_cm' => $validated['height'] ?? null,
            'weight_kg' => $validated['weight'] ?? null,
            'target_weight_kg' => $validated['target_weight_kg'] ?? null,
            'bmi' => $validated['bmi'] ?? null,
            'body_fat_percentage' => $validated['body_fat_percentage'] ?? null,
            'health_conditions' => $validated['health_conditions'] ?? null,
            'injuries' => $validated['injuries'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'medications' => $validated['medications'] ?? null,
            'coach_id' => $validated['coach_id'] ?? null,

            'fitness_level' => $validated['fitness_level'] ?? 'beginner',
            'training_days_per_week' => $validated['training_days_per_week'] ?? 3,
            'preferred_training_time' => $validated['preferred_training_time'] ?? 'evening',
            'preferred_workout_duration' => $validated['preferred_workout_duration'] ?? '60',
            'exercise_preferences' => $validated['exercise_preferences'] ?? 'General fitness exercises',
            'exercise_dislikes' => $validated['exercise_dislikes'] ?? null,

            'diet_preference' => $validated['diet_preference'] ?? 'no_restriction',
            'meals_per_day' => $validated['meals_per_day'] ?? 3,
            'food_preferences' => $validated['food_preferences'] ?? null,
            'food_dislikes' => $validated['food_dislikes'] ?? null,

            'club_id' => $club->id,
        ];

        $verificationCode = rand(100000, 999999);

        session([
            'user_data' => $userData,
            'plan_id' => $validated['plan_id'] ?? null,
            'user_verification_code' => $verificationCode,
            'user_verification_expires_at' => now()->addMinutes(30)
        ]);

        try {
            Mail::raw("Your ProGymHub User Account Verification Code is: $verificationCode\n\nThis code will expire in 30 minutes. Please verify your email to activate your account.", function ($message) use ($userData) {
                $message->to($userData['email'])
                    ->subject('ProGymHub User Account Verification');
            });

            return redirect()->route('club.users.verify.email.form')
                ->with('status', 'A verification code has been sent to the email address (' . $userData['email'] . '). Please verify to complete registration.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending verification email:', [
                'exception' => $e->getMessage(),
                'email' => $userData['email']
            ]);

            return redirect()->back()
                ->with('error', 'Failed to send verification email. Please try again or contact support.')
                ->withInput();
        }
    }

    /**
     * Show the verification form for user email
     */
    public function showVerifyUserEmail()
    {
        if (!session()->has('user_data')) {
            return redirect()->route('club.users.create')
                ->with('error', 'No pending user registration found.');
        }

        $userData = session('user_data');
        return view('dashboard.clubs.users.verify-email', compact('userData'));
    }

    /**
     * Verify user email with code and create user
     */
    public function verifyUserEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:6',
        ]);

        if (!session()->has('user_data') || !session()->has('user_verification_code') || !session()->has('user_verification_expires_at')) {
            return redirect()->route('club.users.create')
                ->with('error', 'Verification session expired. Please try again.');
        }

        $userData = session('user_data');
        $planId = session('plan_id');
        $verificationCode = session('user_verification_code');
        $expiresAt = session('user_verification_expires_at');

        if (now()->isAfter($expiresAt)) {
            session()->forget(['user_data', 'plan_id', 'user_verification_code', 'user_verification_expires_at']);

            return redirect()->route('club.users.create')
                ->with('error', 'Verification code has expired. Please try again.');
        }

        if ((string)$verificationCode !== (string)$request->verification_code) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        $club = Auth::guard('club')->user();

        DB::beginTransaction();

        try {
            $user = new \App\Models\User();
            foreach ($userData as $key => $value) {
                $user->{$key} = $value;
            }

            $user->save();

            // Only process subscription plan if club is active
            if (!empty($planId) && $club->status === 'active') {
                $plan = SubscriptionPlan::find($planId);

                $otherActiveSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
                    ->where('club_id', '!=', $club->id)
                    ->where('end_date', '>=', now())
                    ->first();

                $startDate = now();
                if ($otherActiveSubscription) {
                    $daysRemaining = now()->diffInDays($otherActiveSubscription->end_date, false);
                    if ($daysRemaining > 3) {
                        DB::rollBack();
                        return redirect()->route('club.users.create')
                            ->with('error', "This user has an active subscription at another club that ends in {$daysRemaining} days. You can only create a subscription in the last 3 days of their current subscription.");
                    }
                    $startDate = $otherActiveSubscription->end_date->copy()->addDay();
                }

                $endDate = $startDate->copy()->addDays($plan->duration_days);

                \App\Models\UserSubscription::create([
                    'user_id' => $user->id,
                    'club_id' => $club->id,
                    'plan_id' => $plan->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'payment_status' => 'completed',
                    'payment_method' => 'manual',
                ]);
            }

            DB::commit();

            try {
                Mail::send('emails.user-welcome', [
                    'user' => $user,
                    'club' => $club
                ], function ($message) use ($user, $club) {
                    $message->to($user->email)
                        ->subject('Welcome to ' . $club->name . ' - ProGymHub');
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send welcome email to user:', [
                    'exception' => $e->getMessage(),
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'title' => 'Welcome to ' . $club->name,
                'message' => 'Your account has been successfully created and verified. You can now enjoy all our services!',
                'read_at' => null,
                'type' => 'user_registered',
                'data' => [
                    'club_id' => $club->id,
                    'registered_at' => now()->toDateTimeString()
                ]
            ]);

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'title' => 'New User Registered',
                'message' => 'A new user (' . $user->name . ') has registered with your club.',
                'read_at' => null,
                'type' => 'new_user_registered',
                'data' => [
                    'user_id' => $user->id,
                    'registered_at' => now()->toDateTimeString()
                ]
            ]);

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'title' => 'New User Registered',
                    'message' => 'Club ' . $club->name . ' has registered a new user (' . $user->name . ').',
                    'read_at' => null,
                    'type' => 'user_registered',
                    'data' => [
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                        'registered_at' => now()->toDateTimeString()
                    ]
                ]);

                try {
                    Mail::send('emails.new-user-admin', [
                        'user' => $user,
                        'admin' => $admin,
                        'club' => $club
                    ], function ($message) use ($admin, $user, $club) {
                        $message->to($admin->email)
                            ->subject('New User Registered - ' . $user->name . ' at ' . $club->name);
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send email to admin:', [
                        'exception' => $e->getMessage(),
                        'admin_id' => $admin->id,
                        'email' => $admin->email
                    ]);
                }
            }

            if (!empty($user->coach_id)) {
                $coach = \App\Models\Coach::find($user->coach_id);
                if ($coach) {
                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\Coach',
                        'notifiable_id' => $coach->id,
                        'title' => 'New Client Assigned',
                        'message' => 'You have been assigned to a new client: ' . $user->name,
                        'read_at' => null,
                        'type' => 'new_client_assigned',
                        'data' => [
                            'user_id' => $user->id,
                            'assigned_at' => now()->toDateTimeString()
                        ]
                    ]);

                    try {
                        Mail::send('emails.coach-new-client', [
                            'user' => $user,
                            'coach' => $coach,
                            'club' => $club
                        ], function ($message) use ($coach, $user) {
                            $message->to($coach->email)
                                ->subject('New Client Assigned - ' . $user->name);
                        });
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send email to coach:', [
                            'exception' => $e->getMessage(),
                            'coach_id' => $coach->id,
                            'email' => $coach->email
                        ]);
                    }
                }
            }

            session()->forget(['user_data', 'plan_id', 'user_verification_code', 'user_verification_expires_at']);

            return redirect()->route('club.users')
                ->with('success', 'User verified and created successfully. Notifications have been sent to all parties.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error creating verified user:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => $userData,
                'exception_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            $errorMessage = 'Error creating user after verification. ';
            if (app()->environment('local', 'development', 'testing')) {
                $errorMessage .= $e->getMessage();
            } else {
                $errorMessage .= 'Please try again or contact support.';
            }

            return redirect()->route('club.users.create')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Resend user verification code
     */
    public function resendUserVerification()
    {
        if (!session()->has('user_data')) {
            return redirect()->route('club.users.create')
                ->with('error', 'No pending user registration found.');
        }

        $userData = session('user_data');

        $verificationCode = rand(100000, 999999);

        session([
            'user_verification_code' => $verificationCode,
            'user_verification_expires_at' => now()->addMinutes(30)
        ]);

        try {
            Mail::raw("Your ProGymHub User Account Verification Code is: $verificationCode\n\nThis code will expire in 30 minutes. Please verify your email to activate your account.", function ($message) use ($userData) {
                $message->to($userData['email'])
                    ->subject('ProGymHub User Account Verification - New Code');
            });

            return redirect()->route('club.users.verify.email.form')
                ->with('status', 'A new verification code has been sent to ' . $userData['email'] . '.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending verification email:', [
                'exception' => $e->getMessage(),
                'email' => $userData['email']
            ]);

            return redirect()->back()
                ->with('error', 'Failed to send verification email. Please try again or contact support.');
        }
    }

    /**
     * Show form to edit a user
     */
    public function editUser($encodedId)
    {
        $club = Auth::guard('club')->user();

        $userModel = new \App\Models\User();
        $user = $userModel->resolveRouteBinding($encodedId);

        if (!$user) {
            return redirect()->route('club.users')
                ->with('error', 'User not found.');
        }

        $userSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->latest()
            ->first();

        $hasSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->exists();

        if (!$hasSubscription && $user->club_id != $club->id) {
            return redirect()->route('club.users')
                ->with('error', 'You are not authorized to edit this user.');
        }

        $subscriptionPlans = SubscriptionPlan::where('club_id', $club->id)
            ->where('is_active', true)
            ->get();

        $coaches = \App\Models\Coach::where('club_id', $club->id)->get();

        return view('dashboard.clubs.users.edit', compact('user', 'userSubscription', 'subscriptionPlans', 'coaches'));
    }

    /**
     * Update a user
     */
    public function updateUser(Request $request, $encodedId)
    {
        $club = Auth::guard('club')->user();

        if ($club->status !== 'active' && $request->has('plan_id') && !empty($request->plan_id)) {
            return redirect()->back()->with('error', 'You cannot modify user subscriptions while your club is inactive. You may update other user information.')->withInput();
        }

        $userModel = new \App\Models\User();
        $user = $userModel->resolveRouteBinding($encodedId);

        if (!$user) {
            return redirect()->route('club.users')
                ->with('error', 'User not found.');
        }

        $hasSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->exists();

        if (!$hasSubscription && $user->club_id != $club->id) {
            return redirect()->route('club.users')
                ->with('error', 'You are not authorized to edit this user.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'bmi' => ['nullable', 'numeric', 'min:0'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'fitness_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced'],
            'goal' => ['nullable', 'string', 'max:255'],
            'health_conditions' => ['nullable', 'string'],
            'injuries' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'training_preferences' => ['nullable', 'string'],
            'training_availability' => ['nullable', 'string'],
            'diet_preferences' => ['nullable', 'string'],
            'food_restrictions' => ['nullable', 'string'],
            'plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'coach_id' => ['nullable', 'exists:coaches,id'],
        ]);

        if (!empty($validated['plan_id'])) {
            $plan = SubscriptionPlan::find($validated['plan_id']);
            if (!$plan || $plan->club_id !== $club->id) {
                return redirect()->back()->with('error', 'The selected subscription plan is invalid.')->withInput();
            }

            if (!$plan->is_active) {
                return redirect()->back()->with('error', 'The selected subscription plan is currently unavailable.')->withInput();
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $userData = [];

        $userData['name'] = $validated['name'];
        $userData['email'] = $validated['email'];
        $userData['phone_number'] = $validated['phone_number'];
        if (isset($validated['password'])) {
            $userData['password'] = $validated['password'];
        }

        $userData['goal'] = empty($validated['goal']) ? 'General fitness' : $validated['goal'];
        $userData['date_of_birth'] = $validated['date_of_birth'] ?? null;
        $userData['gender'] = $validated['gender'] ?? null;
        $userData['height_cm'] = $validated['height'] ?? null;
        $userData['weight_kg'] = $validated['weight'] ?? null;
        $userData['bmi'] = $validated['bmi'] ?? null;
        $userData['body_fat_percentage'] = $validated['body_fat_percentage'] ?? null;

        $userData['health_conditions'] = $validated['health_conditions'] ?? null;
        $userData['injuries'] = $validated['injuries'] ?? null;
        $userData['allergies'] = $validated['allergies'] ?? null;
        $userData['fitness_level'] = $validated['fitness_level'] ?? null;
        $userData['coach_id'] = $validated['coach_id'] ?? null;

        if (isset($validated['training_preferences'])) {
            $userData['exercise_preferences'] = $validated['training_preferences'];
        }

        if (isset($validated['training_availability'])) {
            $userData['preferred_training_time'] = $validated['training_availability'];
        }

        if (isset($validated['diet_preferences'])) {
            $userData['diet_preference'] = $validated['diet_preferences'];
        }

        if (isset($validated['food_restrictions'])) {
            $userData['food_dislikes'] = $validated['food_restrictions'];
        }

        DB::beginTransaction();

        try {
            \Illuminate\Support\Facades\Log::info('Attempting to update user with data:', [
                'user_id' => $user->id,
                'user_data' => $userData
            ]);

            $notificationFailure = false;

            $originalUserData = $user->getOriginal();

            $user->update($userData);

            \Illuminate\Support\Facades\Log::info('User updated successfully:', ['user_id' => $user->id]);

            $updatedFields = [];
            foreach ($userData as $key => $value) {
                if ($key === 'password') {
                    continue;
                }

                if (isset($originalUserData[$key]) && $originalUserData[$key] != $value) {
                    $displayValue = $value;

                    if ($key === 'date_of_birth' && !is_null($value)) {
                        $displayValue = \Carbon\Carbon::parse($value)->format('F d, Y');
                    }

                    if (is_bool($value)) {
                        $displayValue = $value ? 'Yes' : 'No';
                    }

                    $updatedFields[$key] = $displayValue ?: 'Not specified';
                }
            }

            if (!empty($updatedFields)) {
                try {
                    Mail::send('emails.user-updated', [
                        'user' => $user,
                        'club' => $club,
                        'updatedFields' => $updatedFields
                    ], function ($message) use ($user, $club) {
                        $message->to($user->email)
                            ->subject('Account Update Notification - ' . $club->name);
                    });

                    Mail::send('emails.user-updated-club', [
                        'user' => $user,
                        'club' => $club,
                        'updatedFields' => $updatedFields
                    ], function ($message) use ($user, $club) {
                        $message->to($club->email)
                            ->subject('User Account Updated - ' . $user->name);
                    });

                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\User',
                        'notifiable_id' => $user->id,
                        'title' => 'Account Information Updated',
                        'message' => 'Your account information has been updated by ' . $club->name . '.',
                        'read_at' => null,
                        'type' => 'account_updated',
                        'data' => [
                            'club_id' => $club->id,
                            'updated_at' => now()->toDateTimeString(),
                            'updated_fields' => array_keys($updatedFields)
                        ]
                    ]);

                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\Club',
                        'notifiable_id' => $club->id,
                        'title' => 'User Account Updated',
                        'message' => 'User ' . $user->name . ' information has been updated.',
                        'read_at' => null,
                        'type' => 'user_updated',
                        'data' => [
                            'user_id' => $user->id,
                            'updated_at' => now()->toDateTimeString(),
                            'updated_fields' => array_keys($updatedFields)
                        ]
                    ]);

                    if (!empty($user->coach_id)) {
                        $coach = \App\Models\Coach::find($user->coach_id);
                        if ($coach) {
                            \App\Models\Notification::create([
                                'notifiable_type' => 'App\\Models\\Coach',
                                'notifiable_id' => $coach->id,
                                'title' => 'Client Information Updated',
                                'message' => 'Your client ' . $user->name . ' information has been updated.',
                                'read_at' => null,
                                'type' => 'client_updated',
                                'data' => [
                                    'user_id' => $user->id,
                                    'updated_at' => now()->toDateTimeString(),
                                    'updated_fields' => array_keys($updatedFields)
                                ]
                            ]);
                        }
                    }

                    if (isset($updatedFields['coach_id']) && !empty($originalUserData['coach_id'])) {
                        if ($originalUserData['coach_id'] != $updatedFields['coach_id']) {
                            $previousCoach = \App\Models\Coach::find($originalUserData['coach_id']);
                            if ($previousCoach) {
                                \App\Models\Notification::create([
                                    'notifiable_type' => 'App\\Models\\Coach',
                                    'notifiable_id' => $previousCoach->id,
                                    'title' => 'Client Reassigned',
                                    'message' => 'Client ' . $user->name . ' has been reassigned to another coach.',
                                    'read_at' => null,
                                    'type' => 'client_reassigned',
                                    'data' => [
                                        'user_id' => $user->id,
                                        'updated_at' => now()->toDateTimeString()
                                    ]
                                ]);
                            }

                            $newCoach = \App\Models\Coach::find($userData['coach_id']);
                            if ($newCoach) {
                                \App\Models\Notification::create([
                                    'notifiable_type' => 'App\\Models\\Coach',
                                    'notifiable_id' => $newCoach->id,
                                    'title' => 'New Client Assigned',
                                    'message' => 'You have been assigned a new client: ' . $user->name,
                                    'read_at' => null,
                                    'type' => 'client_assigned',
                                    'data' => [
                                        'user_id' => $user->id,
                                        'updated_at' => now()->toDateTimeString()
                                    ]
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send user update notifications:', [
                        'exception' => $e->getMessage(),
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);

                    $notificationFailure = true;
                }
            }

            if (!empty($validated['plan_id']) && $club->status === 'active') {
                $plan = SubscriptionPlan::find($validated['plan_id']);

                $existingSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
                    ->where('club_id', $club->id)
                    ->where('end_date', '>=', now())
                    ->first();
                $otherActiveSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
                    ->where('club_id', '!=', $club->id)
                    ->where('end_date', '>=', now())
                    ->first();

                $startDate = now();
                if ($otherActiveSubscription) {
                    $daysRemaining = now()->diffInDays($otherActiveSubscription->end_date, false);
                    if ($daysRemaining > 3) {
                        return redirect()->back()->with('error', "This user has an active subscription at another club that ends in {$daysRemaining} days. You can only update their subscription in the last 3 days of their current subscription.")->withInput();
                    }
                    $startDate = $otherActiveSubscription->end_date->copy()->addDay();
                }

                $endDate = $startDate->copy()->addDays($plan->duration_days);

                if ($existingSubscription) {
                    $existingSubscription->update([
                        'plan_id' => $plan->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                } else {
                    \App\Models\UserSubscription::create([
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                        'plan_id' => $plan->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'payment_status' => 'completed',
                        'payment_method' => 'manual',
                    ]);
                }
            }

            DB::commit();

            $successMessage = 'User updated successfully.';
            if (!empty($updatedFields)) {
                $successMessage .= $notificationFailure
                    ? ' Some notification emails could not be sent.'
                    : ' Notification emails have been sent to the user and club.';
            }

            return redirect()->route('club.users')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error updating user:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => $userData,
                'exception_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            $errorMessage = 'Error updating user. ';
            if (app()->environment('local', 'development', 'testing')) {
                $errorMessage .= $e->getMessage();
            } else {
                $errorMessage .= 'Please try again or contact support.';
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Soft delete a user
     */
    public function deleteUser($encodedId)
    {
        $club = Auth::guard('club')->user();

        $userModel = new \App\Models\User();
        $user = $userModel->resolveRouteBinding($encodedId);

        if (!$user) {
            return redirect()->route('club.users')
                ->with('error', 'User not found.');
        }

        $hasSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->exists();

        if (!$hasSubscription && $user->club_id != $club->id) {
            return redirect()->route('club.users')
                ->with('error', 'You are not authorized to delete this user.');
        }

        try {
            Mail::send('emails.user-deleted', [
                'user' => $user,
                'club' => $club
            ], function ($message) use ($user, $club) {
                $message->to($user->email)
                    ->subject('Account Deactivation Notice - ' . $club->name);
            });

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'title' => 'Account Deactivated',
                'message' => 'Your account at ' . $club->name . ' has been deactivated.',
                'read_at' => null,
                'type' => 'account_deactivated',
                'data' => [
                    'club_id' => $club->id,
                    'deactivated_at' => now()->toDateTimeString()
                ]
            ]);

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'title' => 'User Account Deactivated',
                'message' => 'User ' . $user->name . ' has been deactivated.',
                'read_at' => null,
                'type' => 'user_deactivated',
                'data' => [
                    'user_id' => $user->id,
                    'deactivated_at' => now()->toDateTimeString()
                ]
            ]);

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'title' => 'User Account Deactivated',
                    'message' => 'Club ' . $club->name . ' has deactivated user ' . $user->name . '.',
                    'read_at' => null,
                    'type' => 'user_deactivated',
                    'data' => [
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                        'deactivated_at' => now()->toDateTimeString()
                    ]
                ]);

                try {
                    Mail::send('emails.user-deleted-admin', [
                        'user' => $user,
                        'admin' => $admin,
                        'club' => $club
                    ], function ($message) use ($admin, $user, $club) {
                        $message->to($admin->email)
                            ->subject('User Account Deactivated - ' . $user->name . ' at ' . $club->name);
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send user deactivation email to admin:', [
                        'exception' => $e->getMessage(),
                        'admin_id' => $admin->id,
                        'email' => $admin->email
                    ]);
                }
            }

            if (!empty($user->coach_id)) {
                $coach = \App\Models\Coach::find($user->coach_id);
                if ($coach) {
                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\Coach',
                        'notifiable_id' => $coach->id,
                        'title' => 'Client Account Deactivated',
                        'message' => 'Your client ' . $user->name . ' has been deactivated.',
                        'read_at' => null,
                        'type' => 'client_deactivated',
                        'data' => [
                            'user_id' => $user->id,
                            'deactivated_at' => now()->toDateTimeString()
                        ]
                    ]);
                }
            }

            $user->delete();

            return redirect()->route('club.users')
                ->with('success', 'User deleted successfully. Notifications have been sent.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting user:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'exception_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            $user->delete();

            return redirect()->route('club.users')
                ->with('success', 'User deleted successfully, but there were issues sending some notifications.');
        }
    }

    /**
     * Update the email/notification portions of the application when something goes wrong
     */
    private function handleNotificationFailure($e, $type = 'general')
    {
        \Illuminate\Support\Facades\Log::error("Failed to send {$type} notifications:", [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'exception_class' => get_class($e),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return "The operation was successful, but there were issues sending some notifications.";
    }

    /**
     * Restore a soft-deleted user
     */
    public function restoreUser($encodedId)
    {
        $club = Auth::guard('club')->user();

        try {
            $value = str_replace(['-', '_'], ['+', '/'], $encodedId);
            $paddingLength = strlen($value) % 4;
            if ($paddingLength) {
                $value .= str_repeat('=', 4 - $paddingLength);
            }

            $decoded = base64_decode($value);

            if (preg_match('/^club-(\d+)-\d+$/', $decoded, $matches)) {
                $id = $matches[1];

                $user = \App\Models\User::withTrashed()->find($id);

                if (!$user) {
                    return redirect()->route('club.users.trashed')
                        ->with('error', 'User not found.');
                }

                $hasSubscription = \App\Models\UserSubscription::where('user_id', $user->id)
                    ->where('club_id', $club->id)
                    ->exists();

                // Check if user is either associated via subscription OR belongs to the club directly
                if (!$hasSubscription && $user->club_id != $club->id) {
                    return redirect()->route('club.users.trashed')
                        ->with('error', 'You are not authorized to restore this user.');
                }

                try {
                    $user->restore();

                    Mail::send('emails.user-restored', [
                        'user' => $user,
                        'club' => $club
                    ], function ($message) use ($user, $club) {
                        $message->to($user->email)
                            ->subject('Account Restoration Notice - ' . $club->name);
                    });

                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\User',
                        'notifiable_id' => $user->id,
                        'title' => 'Account Restored',
                        'message' => 'Your account at ' . $club->name . ' has been reactivated.',
                        'read_at' => null,
                        'type' => 'account_restored',
                        'data' => [
                            'club_id' => $club->id,
                            'restored_at' => now()->toDateTimeString()
                        ]
                    ]);

                    \App\Models\Notification::create([
                        'notifiable_type' => 'App\\Models\\Club',
                        'notifiable_id' => $club->id,
                        'title' => 'User Account Restored',
                        'message' => 'User ' . $user->name . ' has been reactivated.',
                        'read_at' => null,
                        'type' => 'user_restored',
                        'data' => [
                            'user_id' => $user->id,
                            'restored_at' => now()->toDateTimeString()
                        ]
                    ]);

                    $admins = \App\Models\Admin::all();
                    foreach ($admins as $admin) {
                        \App\Models\Notification::create([
                            'notifiable_type' => 'App\\Models\\Admin',
                            'notifiable_id' => $admin->id,
                            'title' => 'User Account Restored',
                            'message' => 'Club ' . $club->name . ' has reactivated user ' . $user->name . '.',
                            'read_at' => null,
                            'type' => 'user_restored',
                            'data' => [
                                'user_id' => $user->id,
                                'club_id' => $club->id,
                                'restored_at' => now()->toDateTimeString()
                            ]
                        ]);

                        try {
                            Mail::send('emails.user-restored-admin', [
                                'user' => $user,
                                'admin' => $admin,
                                'club' => $club
                            ], function ($message) use ($admin, $user, $club) {
                                $message->to($admin->email)
                                    ->subject('User Account Restored - ' . $user->name . ' at ' . $club->name);
                            });
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to send user restoration email to admin:', [
                                'exception' => $e->getMessage(),
                                'admin_id' => $admin->id,
                                'email' => $admin->email
                            ]);
                        }
                    }

                    if (!empty($user->coach_id)) {
                        $coach = \App\Models\Coach::find($user->coach_id);
                        if ($coach) {
                            \App\Models\Notification::create([
                                'notifiable_type' => 'App\\Models\\Coach',
                                'notifiable_id' => $coach->id,
                                'title' => 'Client Account Restored',
                                'message' => 'Your client ' . $user->name . ' has been reactivated.',
                                'read_at' => null,
                                'type' => 'client_restored',
                                'data' => [
                                    'user_id' => $user->id,
                                    'restored_at' => now()->toDateTimeString()
                                ]
                            ]);
                        }
                    }

                    return redirect()->route('club.users')
                        ->with('success', 'User restored successfully. Notifications have been sent.');
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error during user restoration process:', [
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'user_id' => $user->id,
                        'exception_class' => get_class($e),
                        'line' => $e->getLine(),
                        'file' => $e->getFile()
                    ]);

                    return redirect()->route('club.users')
                        ->with('success', 'User restored successfully, but there were issues sending some notifications.');
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error restoring user:', [
                'exception' => $e->getMessage(),
                'encoded_id' => $encodedId
            ]);
        }

        return redirect()->route('club.users.trashed')
            ->with('error', 'Unable to restore user. Invalid identifier.');
    }

    /**
     * Display trashed users for the club
     */
    public function trashedUsers()
    {
        $club = Auth::guard('club')->user();

        // Get users who either belong to the club directly OR have subscriptions with the club
        $users = \App\Models\User::withTrashed()
            ->where(function ($query) use ($club) {
                $query->where('club_id', $club->id)
                    ->orWhereHas('subscriptions', function ($subquery) use ($club) {
                        $subquery->where('club_id', $club->id);
                    });
            })
            ->onlyTrashed() // Only get deleted users
            ->with(['subscriptions' => function ($query) use ($club) {
                $query->where('club_id', $club->id)
                    ->with('plan');
            }])
            ->orderBy('name')
            ->paginate(10);

        $users->getCollection()->transform(function ($user) {
            $subscription = $user->subscriptions->first();

            $user->plan_name = $subscription->plan->name ?? 'No Plan';
            $user->start_date = optional($subscription)->start_date;
            $user->end_date = optional($subscription)->end_date;
            $user->payment_status = optional($subscription)->payment_status;

            return $user;
        });

        return view('dashboard.clubs.users.trashed', compact('users'));
    }

    public function showUser($encodedId)
    {
        $club = Auth::guard('club')->user();

        $userModel = new \App\Models\User();
        $user = $userModel->resolveRouteBinding($encodedId);

        if (!$user) {
            return redirect()->route('club.users')
                ->with('error', 'User not found.');
        }

        $userSubscriptions = \App\Models\UserSubscription::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->with('plan')
            ->latest()
            ->get();

        // Check if user is either associated via subscription OR belongs to the club directly
        if ($userSubscriptions->isEmpty() && $user->club_id != $club->id) {
            return redirect()->route('club.users')
                ->with('error', 'You are not authorized to view this user.');
        }

        $activeSubscription = $userSubscriptions->first(function ($subscription) {
            return $subscription->end_date >= now();
        });

        return view('dashboard.clubs.users.show', compact('user', 'userSubscriptions', 'activeSubscription'));
    }

    /**
     * Display a listing of the coaches for the current club.
     */
    public function coaches()
    {
        $club = Auth::guard('club')->user();
        $coaches = \App\Models\Coach::where('club_id', $club->id)->latest()->paginate(10);

        return view('dashboard.clubs.coaches.index', compact('coaches'));
    }

    /**
     * Show the form for creating a new coach.
     */
    public function createCoach()
    {
        return view('dashboard.clubs.coaches.create');
    }

    /**
     * Store a newly created coach in storage.
     */
    public function storeCoach(Request $request)
    {
        $club = Auth::guard('club')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:coaches',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'bio' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience_years' => 'nullable|integer|min:0',
            'certifications' => 'nullable|array',
            'specializations' => 'nullable|array',
            'employment_type' => 'nullable|string|max:50',
            'working_hours' => 'nullable|array',
        ]);

        $coachData = $request->except(['profile_image', 'password', 'certifications', 'specializations', 'working_hours']);
        $coachData['password'] = Hash::make($request->password);
        $coachData['club_id'] = $club->id;

        $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $coachData['verification_code'] = $verification_code;
        $coachData['email_verified_at'] = null;

        if ($request->has('certifications')) {
            $coachData['certifications'] = json_encode($request->certifications);
        }
        if ($request->has('specializations')) {
            $coachData['specializations'] = json_encode($request->specializations);
        }
        if ($request->has('working_hours')) {
            $coachData['working_hours'] = json_encode($request->working_hours);
        }

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('coach-profiles', 'public');
            $coachData['profile_image'] = $profileImagePath;
        }

        session([
            'pending_coach_data' => $coachData,
            'pending_coach_expires_at' => now()->addMinutes(30)
        ]);

        $tempCoach = new \App\Models\Coach($coachData);

        Mail::send('emails.coach-verification', [
            'coach' => $tempCoach,
            'verification_code' => $verification_code,
            'club' => $club
        ], function ($message) use ($coachData) {
            $message->to($coachData['email'])
                ->subject('Verify your email - ProGymHub Coach Account');
        });

        $encodedTempId = base64_encode('temp-coach-' . time());
        $encodedTempId = str_replace(['+', '/', '='], ['-', '_', ''], $encodedTempId);

        session(['temp_coach_id' => $encodedTempId]);

        return redirect()->route('club.coach.verify.form', $encodedTempId)
            ->with('status', 'A verification code has been sent to the coach\'s email (' . $coachData['email'] . '). Please enter the code to complete the registration.');
    }

    /**
     * Display the specified coach.
     */
    public function showCoach($encoded_id)
    {
        $club = Auth::guard('club')->user();
        $coach = (new \App\Models\Coach)->resolveRouteBinding($encoded_id);

        if (!$coach) {
            return redirect()->route('club.coaches')
                ->with('error', 'Coach not found.');
        }

        if ($coach->club_id != $club->id) {
            return redirect()->route('club.coaches')
                ->with('error', 'You are not authorized to view this coach.');
        }

        return view('dashboard.clubs.coaches.show', compact('coach'));
    }

    /**
     * Show the form for editing the specified coach.
     */
    public function editCoach($encoded_id)
    {
        $club = Auth::guard('club')->user();
        $coach = (new \App\Models\Coach)->resolveRouteBinding($encoded_id);

        if (!$coach) {
            return redirect()->route('club.coaches')
                ->with('error', 'Coach not found.');
        }

        if ($coach->club_id != $club->id) {
            return redirect()->route('club.coaches')
                ->with('error', 'You are not authorized to edit this coach.');
        }

        return view('dashboard.clubs.coaches.edit', compact('coach'));
    }

    /**
     * Update the specified coach in storage.
     */
    public function updateCoach(Request $request, $encoded_id)
    {
        $club = Auth::guard('club')->user();
        $coach = (new \App\Models\Coach)->resolveRouteBinding($encoded_id);

        if (!$coach) {
            return redirect()->route('club.coaches')
                ->with('error', 'Coach not found.');
        }

        if ($coach->club_id != $club->id) {
            return redirect()->route('club.coaches')
                ->with('error', 'You are not authorized to update this coach.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('coaches')->ignore($coach->id)],
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'bio' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience_years' => 'nullable|integer|min:0',
            'certifications' => 'nullable|array',
            'specializations' => 'nullable|array',
            'employment_type' => 'nullable|string|max:50',
            'working_hours' => 'nullable|array',
        ]);

        $data = $request->except(['profile_image', 'password', 'certifications', 'specializations', 'working_hours']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->has('certifications')) {
            $data['certifications'] = json_encode($request->certifications);
        }
        if ($request->has('specializations')) {
            $data['specializations'] = json_encode($request->specializations);
        }
        if ($request->has('working_hours')) {
            $data['working_hours'] = json_encode($request->working_hours);
        }

        if ($request->hasFile('profile_image')) {
            if ($coach->profile_image) {
                Storage::disk('public')->delete($coach->profile_image);
            }

            $path = $request->file('profile_image')->store('coach-profiles', 'public');
            $data['profile_image'] = $path;
        }

        $coach->update($data);

        Mail::send('emails.coach-updated', [
            'coach' => $coach,
            'club' => $club
        ], function ($message) use ($coach) {
            $message->to($coach->email)
                ->subject('Your Profile Has Been Updated - ProGymHub');
        });

        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Admin',
                'notifiable_id' => $admin->id,
                'title' => 'Coach Profile Updated',
                'message' => "Club {$club->name} has updated coach {$coach->name}'s profile.",
                'read_at' => null,
                'type' => 'coach_updated',
                'data' => [
                    'coach_id' => $coach->id,
                    'club_id' => $club->id,
                    'updated_at' => now()->toDateTimeString()
                ]
            ]);

            if ($admin->email) {
                Mail::send('emails.coach-updated-admin', [
                    'coach' => $coach,
                    'admin' => $admin,
                    'club' => $club
                ], function ($message) use ($admin, $coach, $club) {
                    $message->to($admin->email)
                        ->subject('Coach Profile Updated - ' . $coach->name . ' at ' . $club->name);
                });
            }
        }

        \App\Models\Notification::create([
            'notifiable_type' => 'App\\Models\\Club',
            'notifiable_id' => $club->id,
            'title' => 'Coach Profile Updated',
            'message' => "You have updated coach {$coach->name}'s profile.",
            'read_at' => null,
            'type' => 'coach_updated',
            'data' => [
                'coach_id' => $coach->id,
                'updated_at' => now()->toDateTimeString()
            ]
        ]);

        Mail::send('emails.coach-updated-club', [
            'coach' => $coach,
            'club' => $club
        ], function ($message) use ($club, $coach) {
            $message->to($club->email)
                ->subject('Coach Profile Update Confirmation - ' . $coach->name);
        });

        return redirect()->route('club.coaches')
            ->with('success', 'Coach updated successfully. Notification emails have been sent.');
    }

    /**
     * Show the verification form for confirming the coach's email.
     */
    public function showCoachVerificationForm($tempId)
    {
        if (!session()->has('pending_coach_data') || !session()->has('temp_coach_id')) {
            return redirect()->route('club.coaches')
                ->with('error', 'No pending coach registration found or session expired.');
        }

        if (session('temp_coach_id') !== $tempId) {
            return redirect()->route('club.coaches')
                ->with('error', 'Invalid verification session.');
        }

        if (now()->isAfter(session('pending_coach_expires_at'))) {
            session()->forget(['pending_coach_data', 'temp_coach_id', 'pending_coach_expires_at']);
            return redirect()->route('club.coaches')
                ->with('error', 'Verification session has expired. Please register the coach again.');
        }

        $coachData = session('pending_coach_data');

        return view('dashboard.clubs.coaches.verify', [
            'tempId' => $tempId,
            'coachEmail' => $coachData['email'],
            'coachName' => $coachData['name']
        ]);
    }

    /**
     * Verify the coach's email with the provided verification code.
     */
    public function verifyCoachEmail(Request $request, $tempId)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        // Get the verification code from the input - only set this once
        $verificationCode = $request->verification_code;

        // For debugging
        \Illuminate\Support\Facades\Log::info('Verification code received:', [
            'code' => $verificationCode
        ]);

        if (!session()->has('pending_coach_data') || !session()->has('temp_coach_id')) {
            return redirect()->route('club.coaches')
                ->with('error', 'No pending coach registration found or session expired.');
        }

        if (session('temp_coach_id') !== $tempId) {
            return redirect()->route('club.coaches')
                ->with('error', 'Invalid verification session.');
        }

        if (now()->isAfter(session('pending_coach_expires_at'))) {
            session()->forget(['pending_coach_data', 'temp_coach_id', 'pending_coach_expires_at']);
            return redirect()->route('club.coaches')
                ->with('error', 'Verification session has expired. Please register the coach again.');
        }

        $coachData = session('pending_coach_data');

        if ($coachData['verification_code'] !== $verificationCode) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        try {
            $coachData['email_verified_at'] = now();

            // Add logging before coach creation
            \Illuminate\Support\Facades\Log::info('Attempting to create coach with data:', [
                'email' => $coachData['email'],
                'name' => $coachData['name'],
                'verified_at' => $coachData['email_verified_at'],
                'club_id' => $coachData['club_id']
            ]);

            $coach = \App\Models\Coach::create($coachData);

            // Log successful coach creation
            \Illuminate\Support\Facades\Log::info('Coach created successfully:', [
                'id' => $coach->id,
                'email' => $coach->email
            ]);

            $club = Auth::guard('club')->user();

            session()->forget(['pending_coach_data', 'temp_coach_id', 'pending_coach_expires_at']);

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'title' => 'New Coach Registered',
                    'message' => "Club {$club->name} has registered a new coach named {$coach->name}. Email verified successfully.",
                    'read_at' => null,
                    'type' => 'coach_registered',
                    'data' => [
                        'coach_id' => $coach->id,
                        'club_id' => $club->id,
                        'registered_at' => now()->toDateTimeString()
                    ]
                ]);

                if ($admin->email) {
                    Mail::send('emails.new-coach-admin', [
                        'coach' => $coach,
                        'admin' => $admin,
                        'club' => $club
                    ], function ($message) use ($admin, $coach, $club) {
                        $message->to($admin->email)
                            ->subject('New Coach Registration - ' . $coach->name . ' at ' . $club->name);
                    });
                }
            }

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'title' => 'Coach Email Verified',
                'message' => "Your coach {$coach->name} has verified their email successfully.",
                'read_at' => null,
                'type' => 'coach_verified',
                'data' => [
                    'coach_id' => $coach->id,
                    'verified_at' => now()->toDateTimeString()
                ]
            ]);

            Mail::send('emails.coach-verified-club', [
                'coach' => $coach,
                'club' => $club
            ], function ($message) use ($club, $coach) {
                $message->to($club->email)
                    ->subject('Coach Email Verified - ' . $coach->name);
            });

            return redirect()->route('club.coaches')
                ->with('success', 'Coach verified and added successfully.');
        } catch (\Exception $e) {
            // Log the detailed exception for debugging
            \Illuminate\Support\Facades\Log::error('Error creating coach:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'coachData' => array_except($coachData, ['password']), // Log data but exclude password
            ]);

            // Show a friendlier message to the user
            return back()
                ->with('error', 'An error occurred while creating the coach. Please try again or contact support.');
        }
    }

    /**
     * Resend verification code to the pending coach's email.
     */
    public function resendCoachVerificationCode($tempId)
    {
        if (!session()->has('pending_coach_data') || !session()->has('temp_coach_id')) {
            return redirect()->route('club.coaches')
                ->with('error', 'No pending coach registration found or session expired.');
        }

        if (session('temp_coach_id') !== $tempId) {
            return redirect()->route('club.coaches')
                ->with('error', 'Invalid verification session.');
        }

        session(['pending_coach_expires_at' => now()->addMinutes(30)]);

        $coachData = session('pending_coach_data');

        $verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $coachData['verification_code'] = $verification_code;
        session(['pending_coach_data' => $coachData]);

        $club = Auth::guard('club')->user();
        $tempCoach = new \App\Models\Coach($coachData);

        Mail::send('emails.coach-verification', [
            'coach' => $tempCoach,
            'verification_code' => $verification_code,
            'club' => $club
        ], function ($message) use ($coachData) {
            $message->to($coachData['email'])
                ->subject('Verify your email - ProGymHub Coach Account');
        });

        return redirect()->route('club.coach.verify.form', $tempId)
            ->with('success', 'A new verification code has been sent to ' . $coachData['email']);
    }

    /**
     * Display a listing of the deleted coaches for the current club.
     */
    public function trashedCoaches()
    {
        $club = Auth::guard('club')->user();
        $coaches = \App\Models\Coach::where('club_id', $club->id)
            ->onlyTrashed()
            ->latest()
            ->paginate(10);

        return view('dashboard.clubs.coaches.trashed', compact('coaches'));
    }

    /**
     * Remove the specified coach from storage (soft delete).
     */
    public function deleteCoach($encoded_id)
    {
        $club = Auth::guard('club')->user();
        $coach = (new \App\Models\Coach)->resolveRouteBinding($encoded_id);

        if (!$coach) {
            return redirect()->route('club.coaches')
                ->with('error', 'Coach not found.');
        }

        if ($coach->club_id != $club->id) {
            return redirect()->route('club.coaches')
                ->with('error', 'You are not authorized to delete this coach.');
        }

        try {
            $coach->delete();

            Mail::send('emails.coach-deleted', [
                'coach' => $coach,
                'club' => $club
            ], function ($message) use ($coach) {
                $message->to($coach->email)
                    ->subject('Coach Account Deletion Notice - ProGymHub');
            });

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'title' => 'Coach Deleted',
                    'message' => "Club {$club->name} has deleted coach {$coach->name}.",
                    'read_at' => null,
                    'type' => 'coach_deleted',
                    'data' => [
                        'coach_id' => $coach->id,
                        'club_id' => $club->id,
                        'deleted_at' => now()->toDateTimeString()
                    ]
                ]);

                if ($admin->email) {
                    Mail::send('emails.coach-deleted-admin', [
                        'coach' => $coach,
                        'admin' => $admin,
                        'club' => $club
                    ], function ($message) use ($admin, $coach, $club) {
                        $message->to($admin->email)
                            ->subject('Coach Deletion Notification - ' . $coach->name . ' at ' . $club->name);
                    });
                }
            }

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'title' => 'Coach Deleted',
                'message' => "You have deleted coach {$coach->name} from your club.",
                'read_at' => null,
                'type' => 'coach_deleted',
                'data' => [
                    'coach_id' => $coach->id,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ]);

            Mail::send('emails.coach-deleted-club', [
                'coach' => $coach,
                'club' => $club
            ], function ($message) use ($club, $coach) {
                $message->to($club->email)
                    ->subject('Coach Deletion Confirmation - ' . $coach->name);
            });

            return redirect()->route('club.coaches')
                ->with('success', 'Coach deleted successfully. Notification emails have been sent.');
        } catch (\Exception $e) {
            return redirect()->route('club.coaches')
                ->with('error', 'An error occurred while deleting the coach: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted coach
     */
    public function restoreCoach($encoded_id)
    {
        $club = Auth::guard('club')->user();

        try {
            $coach = (new \App\Models\Coach)->resolveRouteBinding($encoded_id);

            if (!$coach) {
                $value = str_replace(['-', '_'], ['+', '/'], $encoded_id);
                $paddingLength = strlen($value) % 4;
                if ($paddingLength) {
                    $value .= str_repeat('=', 4 - $paddingLength);
                }

                $decoded = base64_decode($value);

                if (preg_match('/^coach-(\d+)-\d+$/', $decoded, $matches)) {
                    $id = $matches[1];

                    $coach = \App\Models\Coach::withTrashed()->find($id);
                }
            }

            if (!$coach) {
                return redirect()->route('club.coaches.trashed')
                    ->with('error', 'Coach not found.');
            }

            if ($coach->club_id != $club->id) {
                return redirect()->route('club.coaches.trashed')
                    ->with('error', 'You are not authorized to restore this coach.');
            }

            $coach->restore();

            Mail::send('emails.coach-restored', [
                'coach' => $coach,
                'club' => $club
            ], function ($message) use ($coach) {
                $message->to($coach->email)
                    ->subject('Your Coach Account Has Been Restored - ProGymHub');
            });

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'title' => 'Coach Restored',
                    'message' => "Club {$club->name} has restored coach {$coach->name}.",
                    'read_at' => null,
                    'type' => 'coach_restored',
                    'data' => [
                        'coach_id' => $coach->id,
                        'club_id' => $club->id,
                        'restored_at' => now()->toDateTimeString()
                    ]
                ]);

                if ($admin->email) {
                    Mail::send('emails.coach-restored-admin', [
                        'coach' => $coach,
                        'admin' => $admin,
                        'club' => $club
                    ], function ($message) use ($admin, $coach, $club) {
                        $message->to($admin->email)
                            ->subject('Coach Restoration Notification - ' . $coach->name . ' at ' . $club->name);
                    });
                }
            }

            \App\Models\Notification::create([
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'title' => 'Coach Restored',
                'message' => "You have restored coach {$coach->name} to your club.",
                'read_at' => null,
                'type' => 'coach_restored',
                'data' => [
                    'coach_id' => $coach->id,
                    'restored_at' => now()->toDateTimeString()
                ]
            ]);

            Mail::send('emails.coach-restored-club', [
                'coach' => $coach,
                'club' => $club
            ], function ($message) use ($club, $coach) {
                $message->to($club->email)
                    ->subject('Coach Restoration Confirmation - ' . $coach->name);
            });

            return redirect()->route('club.coaches')
                ->with('success', 'Coach restored successfully. Notification emails have been sent.');
        } catch (\Exception $e) {
            return redirect()->route('club.coaches.trashed')
                ->with('error', 'An error occurred while restoring the coach: ' . $e->getMessage());
        }
    }

    /**
     * Display search form for club users
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        return view('dashboard.clubs.search.index');
    }

    /**
     * Process the search and display results for club users
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function searchResults(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:2|max:100',
            'search_type' => 'required|in:all,coaches,users,subscription_plans'
        ]);

        $searchTerm = $request->search_term;
        $searchType = $request->search_type;
        $results = [];
        $club = Auth::guard('club')->user();

        if ($searchType === 'all' || $searchType === 'coaches') {
            $coaches = $club->coaches()
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('bio', 'LIKE', "%{$searchTerm}%");
                })
                ->get();

            $results['coaches'] = $coaches;
        }

        if ($searchType === 'all' || $searchType === 'users') {
            $users = $club->users()
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('phone_number', 'LIKE', "%{$searchTerm}%");
                })
                ->with('coach')
                ->get();

            $results['users'] = $users;
        }

        if ($searchType === 'all' || $searchType === 'subscription_plans') {
            $plans = $club->subscriptionPlans()
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('type', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('price', 'LIKE', "%{$searchTerm}%");
                })
                ->get();

            $results['subscription_plans'] = $plans;
        }

        return view('dashboard.clubs.search.results', compact('results', 'searchTerm', 'searchType'));
    }
}
