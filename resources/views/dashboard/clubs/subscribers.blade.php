@extends('layouts.dashboard')
@section('title', 'Club Subscribers')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Club Subscribers</span>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if($activeSubscriptions->isEmpty())
                    <div class="alert alert-info">
                        No active subscribers found.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Fitness Level</th>
                                    <th>Goal</th>
                                    <th>Subscription Plan</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeSubscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->name }}</td>
                                    <td>{{ $subscription->email }}</td>
                                    <td>{{ $subscription->phone_number }}</td>
                                    <td>
                                        @if($subscription->fitness_level)
                                        <span class="badge bg-{{ $subscription->fitness_level == 'beginner' ? 'primary' : ($subscription->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                            {{ ucfirst($subscription->fitness_level) }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">Not specified</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscription->goal }}</td>
                                    <td>{{ $subscription->plan_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subscription->payment_status == 'completed' || $subscription->payment_status == 'paid' ? 'success' : ($subscription->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($subscription->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#userDetails{{ $subscription->id }}">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>

                                <!-- User Details Modal -->
                                <div class="modal fade" id="userDetails{{ $subscription->id }}" tabindex="-1" aria-labelledby="userDetailsLabel{{ $subscription->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="userDetailsLabel{{ $subscription->id }}">{{ $subscription->name }}'s Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Create a detailed view for the user here -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Personal Information</h6>
                                                        <ul class="list-group list-group-flush mb-3">
                                                            <li class="list-group-item"><strong>Name:</strong> {{ $subscription->name }}</li>
                                                            <li class="list-group-item"><strong>Email:</strong> {{ $subscription->email }}</li>
                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $subscription->phone_number }}</li>
                                                            <li class="list-group-item"><strong>Gender:</strong> {{ $subscription->gender ? ucfirst($subscription->gender) : 'Not specified' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Fitness Information</h6>
                                                        <ul class="list-group list-group-flush mb-3">
                                                            <li class="list-group-item"><strong>Fitness Level:</strong> {{ $subscription->fitness_level ? ucfirst($subscription->fitness_level) : 'Not specified' }}</li>
                                                            <li class="list-group-item"><strong>Goal:</strong> {{ $subscription->goal }}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6>Subscription Information</h6>
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item"><strong>Plan:</strong> {{ $subscription->plan_name }}</li>
                                                            <li class="list-group-item"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d') }}</li>
                                                            <li class="list-group-item"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d') }}</li>
                                                            <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($subscription->payment_status) }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="#" class="btn btn-primary">Contact User</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection