<div class="gym-price-box">
    <h5>Your Subscriptions</h5>
    <div class="mt-3">
        @forelse(Auth::user()->subscriptions()->where('club_id', $club->id)->where('end_date', '>=', now())->get() as $subscription)
            <div class="subscription-plan mb-3 p-3" style="background-color: #2a2a2a; border-radius: 8px;">
                <div class="d-flex justify-content-between">
                    <h6 class="text-white mb-2">{{ $subscription->plan->name }}</h6>
                    <span class="badge bg-success">Active</span>
                </div>
                <p class="small text-light mb-2">
                    Valid until: {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <span class="text-success font-weight-bold">
                            <i class="fas fa-check-circle"></i> Subscription Active
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">You have no active subscriptions with this club.</p>
        @endforelse
    </div>
</div>
