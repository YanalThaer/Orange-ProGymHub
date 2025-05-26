@extends('layouts.dashboard')

@section('title')
    Coach - Results
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded p-4">
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="mb-0">Search Results for "{{ $searchTerm }}"</h5>
                    <a href="{{ route('coach.search') }}" class="btn btn-primary">
                        <i class="fa fa-search me-2"></i>New Search
                    </a>
                </div>

                @if((isset($clients) && $clients->isEmpty()) && (isset($workoutPlans) && $workoutPlans->isEmpty()) && (isset($progressRecords) && $progressRecords->isEmpty()))
                <div class="alert alert-info">
                    No results found for "{{ $searchTerm }}". Please try a different search term.
                </div>
                @else
                
                    @if(isset($clients) && $clients->isNotEmpty())
                    <div class="mb-5">
                        <h6 class="mb-3">Clients ({{ $clients->count() }})</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-dark text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr>                                       
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('coach.clients') }}" class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($workoutPlans) && $workoutPlans->isNotEmpty())
                    <div class="mb-5">
                        <h6 class="mb-3">Workout Plans ({{ $workoutPlans->count() }})</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-dark text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workoutPlans as $plan)
                                    <tr>
                                        <td>{{ $plan->encodedId() }}</td>
                                        <td>{{ $plan->title }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($plan->description, 50) }}</td>
                                        <td>{{ $plan->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($progressRecords) && $progressRecords->isNotEmpty())
                    <div class="mb-5">
                        <h6 class="mb-3">Progress Records ({{ $progressRecords->count() }})</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-dark text-center">
                                <thead>
                                    <tr>                                        <th scope="col">ID</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Weight</th>
                                        <th scope="col">Body Fat</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($progressRecords as $record)
                                    <tr>                                        <td>{{ $record->encodedId() }}</td>
                                        <td>{{ $record->user->name }}</td>
                                        <td>{{ $record->weight }} kg</td>
                                        <td>{{ $record->body_fat ?? 'N/A' }}%</td>
                                        <td>{{ $record->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
