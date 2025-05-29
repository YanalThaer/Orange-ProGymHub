<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $coaches = \App\Models\Coach::with('club')
            ->whereNotNull('email_verified_at')
            ->latest()
            ->take(6)
            ->get();

        $clubs = \App\Models\Club::where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('coaches', 'clubs'));
    }

    public function fallback()
    {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->guard('club')->check()) {
            return redirect()->route('club.dashboard');
        } elseif (auth()->guard('coach')->check()) {
            return redirect()->route('coach.dashboard');
        } else {
            return redirect()->route('home');
        }
    }

    public function about()
    {
        return view('public.pages.about');
    }

    public function blog()
    {
        return view('public.pages.blog');
    }
    public function blogDetails($id)
    {
        return view('public.pages.blogdetails', ['id' => $id]);
    }
    public function contact()
    {
        return view('public.pages.contact');
    }

    public function contactSend(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        \Illuminate\Support\Facades\Mail::to(config('mail.from.address'))->send(
            new \App\Mail\ContactFormMail(
                $validated['name'],
                $validated['email'],
                $validated['subject'],
                $validated['message']
            )
        );

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    public function clubs(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Club::query();

        // Only show active clubs by default
        if ($status && $status != 'all') {
            $query->where('status', $status);
        } else {
            $query->where('status', 'active');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('bio', 'LIKE', '%' . $search . '%')
                    ->orWhere('city', 'LIKE', '%' . $search . '%')
                    ->orWhere('country', 'LIKE', '%' . $search . '%');
            });
        }

        $clubs = $query->latest()->paginate(12)->withQueryString();

        return view('public.clubs.clubs', compact('clubs', 'status', 'search'));
    }

    public function clubDetails(Club $club)
    {
        try {
            $club->load(['coaches', 'subscriptionPlans' => function ($query) {
                $query->where('is_active', true);
            }]);

            return view('public.clubs.club_details', compact('club'));
        } catch (\Exception $e) {
            abort(404, 'Club not found');
        }
    }

    public function elements()
    {
        return view('public.pages.elements');
    }

    public function gallery()
    {
        return view('public.pages.gallery');
    }

    public function pricing()
    {
        return view('public.pages.pricing');
    }

    public function profile($encodedId)
    {
        $userModel = new User();
        $user = $userModel->resolveRouteBinding($encodedId);

        if ($user) {
            return view('public.pages.profile', ['id' => $user->id, 'user' => $user]);
        }

        $coachModel = new \App\Models\Coach();
        $coach = $coachModel->resolveRouteBinding($encodedId);

        if ($coach) {
            $coach->load('club');
            return view('public.pages.coach_profile', ['coach' => $coach]);
        }

        return redirect()->route('home')->with('error', 'Profile not found.');
    }

    public function payment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to subscribe to a plan');
        }

        $encodedPlanId = $request->query('plan_id');
        $encodedClubId = $request->query('club_id');

        if (!$encodedPlanId || !$encodedClubId) {
            return redirect()->route('all_clubs')->with('error', 'Invalid subscription information.');
        }

        try {
            $clubModel = new Club();
            $planModel = new SubscriptionPlan();

            $club = $clubModel->resolveRouteBinding($encodedClubId);
            $plan = $planModel->resolveRouteBinding($encodedPlanId);

            if (!$club) {
                return redirect()->route('all_clubs')->with('error', 'The selected club was not found.');
            }

            if ($club->status !== 'active') {
                return redirect()->route('all_clubs')
                    ->with('error', 'The selected club is not currently accepting new subscriptions.');
            }

            if (!$plan || $plan->club_id != $club->id || !$plan->is_active) {
                return redirect()->route('club_details', $club->getEncodedId())
                    ->with('error', 'The selected subscription plan was not found or is inactive.');
            }

            $user = Auth::user();
            [$canSubscribe, $message] = $user->canSubscribe($club->id);
            $startDate = $user->getNewSubscriptionStartDate();
            $endDate = $startDate->copy()->addDays($plan->duration_days);

            $coaches = \App\Models\Coach::where('club_id', $club->id)
                ->whereNotNull('email_verified_at')
                ->orderBy('name')
                ->get();

            $userInfo = [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'goal' => $user->goal ?? 'Not specified',
                'height_cm' => $user->height_cm ?? 'Not specified',
                'weight_kg' => $user->weight_kg ?? 'Not specified',
                'target_weight_kg' => $user->target_weight_kg ?? 'Not specified',
                'gender' => $user->gender ?? 'Not specified',
                'fitness_level' => $user->fitness_level ?? 'Not specified',
            ];

            return view('public.pages.payment', compact('plan', 'club', 'canSubscribe', 'message', 'startDate', 'endDate', 'coaches', 'userInfo'));
        } catch (\Exception $e) {
            return redirect()->route('all_clubs')->with('error', 'The selected plan or club was not found. Error: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'club_id' => 'required|string',
            'coach_id' => 'nullable|exists:coaches,id',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'card_number' => 'required|string',
            'expiry_date' => 'required|string',
            'cvv' => 'required|string|min:3|max:4',
            'confirmed' => 'sometimes|boolean',
        ]);

        try {
            $user = Auth::user();

            $clubModel = new Club();
            $club = $clubModel->resolveRouteBinding($validated['club_id']);

            if (!$club) {
                return redirect()->route('all_clubs')->with('error', 'The selected club was not found.');
            }

            if ($club->status !== 'active') {
                return back()->with('error', 'This club is not currently accepting new subscriptions.')->withInput();
            }

            $plan = SubscriptionPlan::where('id', $validated['plan_id'])
                ->where('club_id', $club->id)
                ->firstOrFail();

            if (!$plan->is_active) {
                return back()->with('error', 'This subscription plan is currently unavailable.')->withInput();
            }

            [$isValid, $message, $startDate, $endDate] = $this->validateSubscription($user, $club, $plan);
            if (!$isValid) {
                return back()->with('error', $message)->withInput();
            }

            if (!isset($validated['confirmed']) || $validated['confirmed'] != '1') {
                return back()->with('error', 'You must confirm your subscription to proceed.')->withInput();
            }

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'club_id' => $club->id,
                'plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'payment_status' => 'completed',
                'payment_method' => 'credit_card',
            ]);

            $userData = [
                'club_id' => $club->id
            ];

            if (isset($validated['coach_id']) && !empty($validated['coach_id'])) {
                $userData['coach_id'] = $validated['coach_id'];

                \App\Models\CoachPayment::create([
                    'coach_id' => $validated['coach_id'],
                    'user_id' => $user->id,
                    'amount' => $plan->price,
                    'payment_date' => now(),
                    'payment_method' => 'credit_card',
                    'payment_status' => 'completed',
                ]);
            }

            $user->update($userData);

            $coachName = null;
            if (isset($validated['coach_id']) && !empty($validated['coach_id'])) {
                $coach = \App\Models\Coach::find($validated['coach_id']);
                if ($coach) {
                    $coachName = $coach->name;
                }
            }

            $notificationData = [
                'title' => 'New Subscription',
                'message' => 'User ' . $user->name . ' has subscribed to the plan "' . $plan->name . '"' .
                    ($coachName ? ' with coach ' . $coachName : '') . '.',
                'type' => 'new_subscription',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'plan_name' => $plan->name,
                'coach_name' => $coachName,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'created_at' => now()->toDateTimeString()
            ];

            \App\Models\Notification::create([
                'title' => $notificationData['title'],
                'message' => $notificationData['message'],
                'data' => json_encode($notificationData),
                'notifiable_type' => 'App\\Models\\Club',
                'notifiable_id' => $club->id,
                'read_at' => null,
                'type' => 'new_subscription'
            ]);

            try {
                \Illuminate\Support\Facades\Mail::send('emails.subscription_accepted', [
                    'userName' => $user->name,
                    'clubName' => $club->name,
                    'planName' => $plan->name,
                    'startDate' => $startDate->format('F j, Y'),
                    'endDate' => $endDate->format('F j, Y'),
                    'startDateIsToday' => $startDate->isToday(),
                    'coachName' => $coachName,
                    'profileUrl' => route('club.users.show', $user->getEncodedId())
                ], function ($message) use ($club) {
                    $message->to($club->email)
                        ->subject('New Subscription - ProGymHub');
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send email notification to club: ' . $e->getMessage());
            }

            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'title' => 'New User Subscription',
                    'message' => 'User ' . $user->name . ' has subscribed to ' . $club->name . ' with plan "' . $plan->name . '".',
                    'data' => json_encode([
                        'type' => 'new_subscription',
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'club_id' => $club->id,
                        'club_name' => $club->name,
                        'plan_id' => $plan->id,
                        'plan_name' => $plan->name,
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                    ]),
                    'notifiable_type' => 'App\\Models\\Admin',
                    'notifiable_id' => $admin->id,
                    'read_at' => null,
                    'type' => 'new_subscription'
                ]);

                try {
                    \Illuminate\Support\Facades\Mail::send('emails.admin_new_subscription', [
                        'adminName' => $admin->name,
                        'userName' => $user->name,
                        'clubName' => $club->name,
                        'planName' => $plan->name,
                        'startDate' => $startDate->format('F j, Y'),
                        'endDate' => $endDate->format('F j, Y')
                    ], function ($message) use ($admin) {
                        $message->to($admin->email)
                            ->subject('New User Subscription - ProGymHub');
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send email notification to admin: ' . $e->getMessage());
                }
            }

            if ($startDate->isToday()) {
                return redirect()->route('club_details', $club->getEncodedId())
                    ->with('success', 'Subscription completed successfully! Your subscription is now active until ' . $endDate->format('F j, Y'));
            } else {
                return redirect()->route('club_details', $club->getEncodedId())
                    ->with('success', 'Subscription completed successfully! Your subscription will start on ' . $startDate->format('F j, Y') . ' and be active until ' . $endDate->format('F j, Y'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing payment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Validate subscription eligibility
     * 
     * @param User $user
     * @param Club $club
     * @param SubscriptionPlan $plan
     * @return array [bool $valid, string $message, Carbon $startDate, Carbon $endDate]
     */
    private function validateSubscription($user, $club, $plan)
    {
        if ($club->status !== 'active') {
            return [false, 'This club is currently not accepting new subscriptions.', null, null];
        }

        if (!$plan->is_active) {
            return [false, 'This subscription plan is currently unavailable.', null, null];
        }

        [$canSubscribe, $message] = $user->canSubscribe($club->id);
        if (!$canSubscribe) {
            return [false, $message, null, null];
        }

        $startDate = $user->getNewSubscriptionStartDate();
        $endDate = $startDate->copy()->addDays($plan->duration_days);

        return [true, '', $startDate, $endDate];
    }

    /**
     * Show all coaches page with optional search
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function coaches(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Coach::with('club')
            ->whereNotNull('email_verified_at')
            ->where('employment_type', '!=', 'private');

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $coaches = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.pages.coaches', compact('coaches', 'search'));
    }
}
