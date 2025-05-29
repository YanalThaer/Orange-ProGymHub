@extends('layouts.dashboard')
@section('title', 'Admin - Search')
@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12">
            <div class="bg-secondary rounded h-100 p-4 mb-4 text-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Search Results</h2>
                    <a href="{{ route('admin.search') }}" class="btn btn-primary">
                        <i class="fa fa-search me-2"></i> New Search
                    </a>
                </div>
                <p class="mb-4">
                    Showing results for: <strong>"{{ $searchTerm }}"</strong>
                    @if($searchType !== 'all')
                    in <strong>{{ ucfirst($searchType) }}</strong>
                    @endif
                </p>
                @php
                $hasResults = false;
                if (isset($results['clubs']) && $results['clubs']->count() > 0) $hasResults = true;
                if (isset($results['coaches']) && $results['coaches']->count() > 0) $hasResults = true;
                if (isset($results['users']) && $results['users']->count() > 0) $hasResults = true;
                @endphp
                @if(!$hasResults)
                <div class="text-center text-warning mb-4">
                    <h4><i class="fa fa-exclamation-circle me-2"></i>No results found</h4>
                    <p>No matches found for your search criteria. Please try with different keywords or search options.</p>
                </div>
                @endif
                @if(isset($results['clubs']) && $results['clubs']->count() > 0)
                <div class="mb-4">
                    <h3><i class="fa fa-th me-2"></i>Clubs ({{ $results['clubs']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Club Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['clubs'] as $index => $club)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $club->name }}</td>
                                    <td>{{ $club->email }}</td>
                                    <td>{{ $club->location }}, {{ $club->city }}</td>
                                    <td>
                                        @if($club->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                        @elseif($club->status == 'inactive')
                                        <span class="badge bg-warning text-dark">Inactive</span>
                                        @elseif($club->status == 'under_maintenance')
                                        <span class="badge bg-info">Under Maintenance</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($club->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('clubs.show', $club->getEncodedId()) }}" class="btn btn-sm btn-info">
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
                @if(isset($results['coaches']) && $results['coaches']->count() > 0)
                <div class="mb-4">
                    <h3><i class="fa fa-dumbbell me-2"></i>Coaches ({{ $results['coaches']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Club</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['coaches'] as $index => $coach)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $coach->name }}</td>
                                    <td>{{ $coach->email }}</td>
                                    <td>{{ $coach->club->name ?? 'Not Assigned' }}</td>
                                    <td>{{ $coach->experience_years ?? 'N/A' }} years</td>
                                    <td>
                                        <a href="{{ route('admin.coaches.show', $coach->getEncodedId()) }}" class="btn btn-sm btn-info">
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
                @if(isset($results['users']) && $results['users']->count() > 0)
                <div>
                    <h3><i class="fa fa-users me-2"></i>Users ({{ $results['users']->count() }})</h3>
                    <div class="table-responsive">
                        <table class="table table-hover text-white mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Club</th>
                                    <th>Coach</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['users'] as $index => $user)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->club->name ?? 'Not Assigned' }}</td>
                                    <td>{{ $user->coach->name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user->getEncodedId()) }}" class="btn btn-sm btn-info">
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