@extends('layouts.dashboard')

@section('title', 'Coach - Search')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12">
            <div class="bg-secondary rounded h-100 p-4 mb-4 text-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Search Results</h2>
                    <a href="{{ route('coach.search') }}" class="btn btn-primary">
                        <i class="fa fa-search me-2"></i> New Search
                    </a>
                </div>
                <p class="mb-4">
                    Showing results for: <strong>"{{ $searchTerm }}"</strong>
                </p>

                @php
                    $hasResults = false;
                    if (isset($clients) && $clients->isNotEmpty()) $hasResults = true;
                    if (isset($workoutPlans) && $workoutPlans->isNotEmpty()) $hasResults = true;
                    if (isset($progressRecords) && $progressRecords->isNotEmpty()) $hasResults = true;
                @endphp

                @if(!$hasResults)
                <div class="text-center text-warning mb-4">
                    <h4><i class="fa fa-exclamation-circle me-2"></i>No results found</h4>
                    <p>No matches found for your search. Please try different keywords.</p>
                </div>
                @endif

                {{-- Clients --}}
                @if(isset($clients) && $clients->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-users me-2"></i>Clients ({{ $clients->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $index => $client)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('coach.clients') }}" class="btn btn-sm btn-info">
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

                {{-- Workout Plans --}}
                @if(isset($workoutPlans) && $workoutPlans->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-dumbbell me-2"></i>Workout Plans ({{ $workoutPlans->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workoutPlans as $index => $plan)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $plan->title }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($plan->description, 50) }}</td>
                                    <td>{{ $plan->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
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

                {{-- Progress Records --}}
                @if(isset($progressRecords) && $progressRecords->isNotEmpty())
                <div class="mb-4">
                    <h3><i class="fa fa-chart-line me-2"></i>Progress Records ({{ $progressRecords->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Weight</th>
                                    <th>Body Fat</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progressRecords as $index => $record)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $record->user->name }}</td>
                                    <td>{{ $record->weight }} kg</td>
                                    <td>{{ $record->body_fat ?? 'N/A' }}%</td>
                                    <td>{{ $record->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
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
@endsection
