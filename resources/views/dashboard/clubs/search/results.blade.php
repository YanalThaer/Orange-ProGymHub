@extends('layouts.dashboard')

@section('title', 'Club - Search')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12">
            <div class="bg-secondary rounded h-100 p-4 mb-4 text-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Search Results</h2>
                    <a href="{{ route('club.search') }}" class="btn btn-primary">
                        <i class="fa fa-search me-2"></i> New Search
                    </a>
                </div>
                <p class="mb-4">
                    Showing results for: <strong>"{{ $searchTerm }}"</strong>
                    @if($searchType !== 'all')
                    in <strong>{{ ucfirst(str_replace('_', ' ', $searchType)) }}</strong>
                    @endif
                </p>

                @php
                $hasResults = false;
                if (isset($results['coaches']) && $results['coaches']->isNotEmpty()) $hasResults = true;
                if (isset($results['users']) && $results['users']->isNotEmpty()) $hasResults = true;
                if (isset($results['subscription_plans']) && $results['subscription_plans']->isNotEmpty()) $hasResults = true;
                @endphp

                @if(!$hasResults)
                <div class="text-center text-warning mb-4">
                    <h4><i class="fa fa-exclamation-circle me-2"></i>No results found</h4>
                    <p>No matches found for your search. Please try different keywords or check if there are any records in your club.</p>
                    <a href="{{ route('club.search') }}" class="btn btn-primary mt-3">
                        <i class="fa fa-search me-2"></i> Try Another Search
                    </a>
                </div>
                @endif {{-- Coaches --}}
                @if(isset($results['coaches']) && $results['coaches']->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-dumbbell me-2"></i>Coaches ({{ $results['coaches']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Specialization</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['coaches'] as $index => $coach)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $coach->name }}</td>
                                    <td>{{ $coach->email }}</td>
                                    <td>{{ $coach->phone ?? 'N/A' }}</td>
                                    <td>{{ $coach->specialization ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('club.coaches.show', $coach->getEncodedId()) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Users/Members --}}
                @if(isset($results['users']) && $results['users']->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-users me-2"></i>Members ({{ $results['users']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Coach</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['users'] as $index => $user)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $user->coach->name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <a href="{{ route('club.users.show', $user->getEncodedId()) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Subscription Plans --}}
                @if(isset($results['subscription_plans']) && $results['subscription_plans']->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-file-invoice-dollar me-2"></i>Subscription Plans ({{ $results['subscription_plans']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['subscription_plans'] as $index => $plan)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ ucfirst($plan->type) }}</td>
                                    <td>{{ $plan->price }} {{ config('app.currency', 'USD') }}</td>
                                    <td>{{ $plan->duration_days }} days</td>
                                    <td>
                                        <a href="{{ route('club.subscription-plans.edit', $plan->getEncodedId()) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!$hasResults)
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12">
            <div class="bg-dark rounded h-100 p-4 mb-4 text-white">
                <h4 class="mb-3">Searching Tips</h4>
                <ul>
                    <li>Try using shorter search terms</li>
                    <li>Check for typos in your search</li>
                    <li>Try searching by partial name, email, or other details</li>
                    <li>Select a specific category (Coaches, Members, Subscription plans) to narrow your search</li>
                    <li>If you're looking for specific information, try alternative keywords</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hasResults = {
            {
                $hasResults ? 'true' : 'false'
            }
        };
        if (!hasResults) {
            const noResultsMessage = document.querySelector('.text-warning');
            if (noResultsMessage) {
                noResultsMessage.style.transition = 'all 0.3s';
                setTimeout(() => {
                    noResultsMessage.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        noResultsMessage.style.transform = 'scale(1)';
                    }, 300);
                }, 300);
            }
        }
    });
</script>
@endpush
@endsection